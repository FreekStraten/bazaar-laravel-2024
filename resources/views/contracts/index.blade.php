<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Contracts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Upload Contract</h3>
                    <form  action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="contract_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Contract Name</label>
                            <input type="text" id="contract_name" name="contract_name" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="contract_file" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Contract File</label>
                            <input type="file" id="contract_file" name="contract_file" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                        </div>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Upload Contract</button>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Manage Contracts</h3>
                    <table class="table-auto w-full">
                        <thead>
                        <tr>
                            <th class="border px-4 py-2">Contract Name</th>
                            <th class="border px-4 py-2">Uploaded By</th>
                            <th class="border px-4 py-2">Uploaded At</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($contracts as $contract)
                            <tr>
                                <td class="border px-4 py-2">{{ $contract->contract_name }}</td>
                                <td class="border px-4 py-2">{{ $contract->user->name }}</td>
                                <td class="border px-4 py-2">{{ $contract->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="border px-4 py-2">
                                    @if ($contract->approved)
                                        <span class="text-green-500">Approved</span>
                                    @else
                                        <span class="text-red-500">Pending</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">
                                    @if (!$contract->approved)
                                        <a href="{{ route('contracts.approve', $contract->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Approve</a>
                                        <a href="{{ route('contracts.reject', $contract->id) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reject</a>
                                    @endif
                                    <a href="{{ route('contracts.download', $contract->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Download</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
