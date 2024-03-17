<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Business Accounts and Contracts') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Upload Contract</h3>
                    <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="contract_name"
                                   class="block font-medium text-sm text-gray-700 dark:text-gray-300">Contract
                                Name</label>
                            <input type="text" id="contract_name" name="contract_name"
                                   class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full"
                                   required>
                        </div>
                        <div class="mb-4">
                            <label for="contract_file"
                                   class="block font-medium text-sm text-gray-700 dark:text-gray-300">Contract
                                File</label>
                            <div class="relative">
                                <x-secondary-button type="button"
                                                    onclick="document.getElementById('contract_file').click()">
                                    <span id="file-name">Choose File</span>
                                </x-secondary-button>
                                <input type="file" id="contract_file" name="contract_file" class="sr-only"
                                       onchange="updateFileName(this)" required>
                            </div>
                        </div>

                        <x-primary-button type="submit">
                            {{ __('Upload') }}
                        </x-primary-button>

                        <script>
                            function updateFileName(input) {
                                const fileNameElement = document.getElementById('file-name');
                                if (input.files.length > 0) {
                                    fileNameElement.textContent = input.files[0].name;
                                } else {
                                    fileNameElement.textContent = 'Choose File';
                                }
                            }
                        </script>

                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Manage Contracts</h3>

                    @if($contracts->isEmpty())
                        <tr>
                            <td class="border px-4 py-2" colspan="5">No contracts found.</td>
                        </tr>
                    @else

                    <table class="table-auto w-full">
                        <thead>
                        <tr>
                            <th class="border px-4 py-2">Contract Name</th>
                            <th class="border px-4 py-2">Uploaded By</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($contracts as $contract)
                                <tr>
                                    <td class="border px-4 py-2">{{ $contract->contract_name }}</td>
                                    <td class="border px-4 py-2">{{ $contract->user->name }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($contract->approved)
                                            <span class="text-green-500">Approved</span>
                                        @else
                                            <span class="text-red-500">Pending</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if (!$contract->approved)
                                            <a href="{{ route('contracts.approve', $contract->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-green-600 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Approve</a>
                                            <a href="{{ route('contracts.reject', $contract->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-red-600 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Reject</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $contracts->links() }}
                    @endif
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Business Accounts</h3>
                    <table class="table-auto w-full">
                        <thead>
                        <tr>
                            <th class="border px-4 py-2">{{__('Name')}}</th>
                            <th class="border px-4 py-2">{{__('Email')}}</th>
                            <th class="border px-4 py-2">{{__('Street')}}</th>
                            <th class="border px-4 py-2">{{__('HouseNumber')}}</th>
                            <th class="border px-4 py-2">{{__('City')}}</th>
                            <th class="border px-4 py-2">{{__('ZipCode')}}</th>
                            <th class="border px-4 py-2">{{__('action.Action')}} </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($businessAccounts as $account)
                            <tr>
                                <td class="border px-4 py-2">{{ $account->name }}</td>
                                <td class="border px-4 py-2">{{ $account->email }}</td>
                                <td class="border px-4 py-2">{{ $account->address->street }}</td>
                                <td class="border px-4 py-2">{{ $account->address->house_number }}</td>
                                <td class="border px-4 py-2">{{ $account->address->city }}</td>
                                <td class="border px-4 py-2">{{ $account->address->zip_code }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('business-accounts.export-pdf', ['id' => $account->id]) }}"
                                       class='inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150'>
                                        {{ __('Export PDF') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $businessAccounts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
