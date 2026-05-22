@extends('layout.index')
@extends('front.nav')
@section('home')
    {{-- search customer details --}}
    <div class="w- full h-[80px] flex justify-center place-items-center bg-[#1D4ED8] ">
        <input type="text" name="" onkeyup="searchTable()" id="searchInput" placeholder="Search Customer"
            class="w-[50%] py-2 px-3 outline-none border-0 rounded">
    </div>
    {{-- search customer details --}}



    {{-- Show Customer Details --}}

    <div class="w-full mx-auto mt-5 mb-5 overflow-x-auto">
        <table class="min-w-[600px] table-auto border-collapse border border-gray-200">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border border-gray-300">S.NO</th>
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER NAME</th>
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER NUMBER</th>
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER EMAIL</th>
                    <th class="px-4 py-2 border border-gray-300">PRICE</th>
                    <th class="px-4 py-2 border border-gray-300 hidden sm:table-cell">REMARKS</th>
                    <th class="px-4 py-2 border border-gray-300">STATUS</th>
                    <th class="px-4 py-2 border border-gray-300 hidden sm:table-cell">CUSTOMER REGISTRATION DATE</th>
                    <th class="px-4 py-2 border border-gray-300 hidden md:table-cell">AGENT NAME</th>
                    <th class="px-4 py-2 border border-gray-300 hidden md:table-cell">RENEWAL</th>
                    <th class="px-4 py-2 border border-gray-300 hidden md:table-cell">ACTION</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach ($customers as $index => $customer)
                    <tr class="odd:bg-gray-50 even:bg-white">
                        <td class="px-4 py-2 border border-gray-300 customer"> {{ $index + 1 }} </td>
                        <td class="px-4 py-2 border border-gray-300 customer"> {{ $customer->customer_name }} </td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $customer->customer_number }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $customer->customer_email }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">${{ $customer->price }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer hidden sm:table-cell">{{ $customer->remarks }}
                        </td>
                        <td class="px-4 py-2 border border-gray-300 customer">
                            <span class="bg-success py-1 px-2 rounded font-bold text-xl text-white"> {{ $customer->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border border-gray-300 customer hidden sm:table-cell">
                            @if ($customer->regitr_date)
                                {{ \Carbon\Carbon::parse($customer->regitr_date)->format('d M, Y') }}
                            @else
                                No Registration Date
                            @endif
                        </td>
                        <td class="px-4 py-2 border border-gray-300 customer hidden md:table-cell">
                            {{ $customer['user']->name }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer hidden md:table-cell">
                            @if ($customer->regitr_date)
                                {{ \Carbon\Carbon::parse($customer->expiry_date)->format('d M, Y') }}
                            @else
                                No Expiry Date
                            @endif
                        </td>
                        <td class="px-4 py-2 border border-gray-300 customer hidden md:table-cell ">
                            <a href="{{ route('viewUpdateSaleExpiryForm', $customer->id) }}" class="btn btn-sm btn-primary mt-2">Update Expiry</a>
                        </td>
                    </tr>
                @endforeach

                @if ($customers->isEmpty())
                    <tr>
                        <td colspan="10" class="text-center">No Sale Record Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{-- Show Customer Details --}}


    <script>
        function searchTable() {
            const searchInput = document.getElementById("searchInput").value.toLowerCase();
            const tableBody = document.getElementById("tableBody");
            const rows = tableBody.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                let customerName = rows[i].getElementsByTagName("td")[1].textContent.toLowerCase();
                let customerNumber = rows[i].getElementsByTagName("td")[2].textContent.toLowerCase();
                if (customerName.includes(searchInput) || customerNumber.includes(searchInput)) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none"; // Hide rows that don't match
                }
            }
        }
    </script>
@endsection
