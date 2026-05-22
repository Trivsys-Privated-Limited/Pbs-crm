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

    <div class="w-[100%] mx-auto mt-5 mb-5">
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border border-gray-300">S.NO</th>
                    <th class="px-4 py-2 border border-gray-300">AGENT NAME</th>
                    <th class="px-4 py-2 border border-gray-300">START DATE</th>
                    <th class="px-4 py-2 border border-gray-300">END DATE</th>
                    <th class="px-4 py-2 border border-gray-300">REQUEST RESON</th>
                    <th class="px-4 py-2 border border-gray-300">STATUS</th>
                    <th class="px-4 py-2 border border-gray-300">REJECT RESON</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach ($leaves as $index => $leave)
                    <tr class="odd:bg-gray-50 even:bg-white">
                        <td class="px-4 py-2 border border-gray-300 customer"> {{ $index + 1 }} </td>
                        <td class="px-4 py-2 border border-gray-300 customer"> {{ $leave->user->name }} </td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $leave->start_date }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $leave->end_date }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $leave->reason }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">
                            @if ($leave->status === 'Pending')
                                <span class="bg-warning py-1 px-2 rounded text-white">Pending</span>
                            @elseif($leave->status === 'Approved')
                                <span class="bg-success py-1 px-2 rounded text-white">Approved</span>
                            @else
                                <span class="bg-danger py-1 px-2 rounded text-white">Rejected</span>
                            @endif
                        </td>
                        
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $leave->reason_to_reject}}</td>
                    </tr>
                @endforeach
                @if ($leaves->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No Leave Record Found</td>
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
                    rows[i].style.display = "none";
                }
            }
        }
    </script>
@endsection
