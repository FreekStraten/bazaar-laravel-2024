<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

class BusinessAccountController extends Controller
{
    /**
     * Display a listing of business accounts and contracts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all the business accounts with their addresses and contracts
        $businessAccounts = User::where('user_type', 'business')
            ->with('address')
            ->with('contracts')
            ->orderBy('name')
            ->paginate(10);

        // Get all the contracts
        $contracts = Contract::with('user')->paginate(10);

        return view('business-accounts.index', compact('businessAccounts', 'contracts'));
    }

    /**
     * Store a new contract.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeContract(Request $request)
    {
        $validatedData = $request->validate([
            'contract_name' => 'required|string',
            'contract_file' => 'required|file|mimes:pdf',
        ]);

        $pdfFile = $request->file('contract_file')->getRealPath();
        $userId = $this->extractUserId($pdfFile);

        // Log the user ID
        Log::info('User ID extracted from the PDF: ' . $userId);

        // Create a new Contract model instance
        $contract = new Contract([
            'contract_name' => $validatedData['contract_name'],
            'contract_file' => $request->file('contract_file')->store('contracts'),
            'user_id' => $userId,
        ]);
        $contract->save();

        return redirect()->route('business-accounts.index')->with('success', 'Contract uploaded successfully.');
    }
    private function extractUserId($pdfFile)
    {
        $parser = new Parser();
        $pdfContent = file_get_contents($pdfFile);

        try {
            $pdf = $parser->parseContent($pdfContent);
            $text = $pdf->getText();
            $lines = explode("\n", $text);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), 'User Id')) {
                    $parts = explode(' ', trim($line));
                    $userId = end($parts);
                    return (int) preg_replace('/\D/', '', $userId); // Return only the integer value
                }
            }
        } catch (\Exception $e) {
            Log::error('Error extracting user ID from PDF: ' . $e->getMessage());
        }

        return null;
    }


    /**
     * Approve a contract.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveContract($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->approved = 1;
        $contract->save();

        return redirect()->route('business-accounts.index')->with('success', 'Contract approved successfully.');
    }

    /**
     * Reject a contract.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectContract($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->approved = 2;
        $contract->save();

        return redirect()->route('business-accounts.index')->with('success', 'Contract rejected successfully.');
    }

    /**
     * Download a business account contract as PDF.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadContract($id)
    {
        $user = User::findOrFail($id);

        $dompdf = new Dompdf();
        $html = view('business-accounts.business-registration-contract', compact('user'))->render();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $fileName = $user->name . '_BusinessRegisterContract.pdf';

        return $dompdf->stream($fileName);
    }
}
