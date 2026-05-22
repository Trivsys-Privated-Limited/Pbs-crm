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
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER NAME</th>
                    <th class="px-4 py-2 border border-gray-300">CUSTOMER NUMBER</th>
                    <th class="px-4 py-2 border border-gray-300">MAC ADDRESS</th>
                    <th class="px-4 py-2 border border-gray-300">STATUS</th>
                    <th class="px-4 py-2 border border-gray-300">HELP REASON</th>
                    <th class="px-4 py-2 border border-gray-300">ADMIN REMARKS</th>
                    <th class="px-4 py-2 border border-gray-300">DATE</th>
                    <th class="px-4 py-2 border border-gray-300">ACTION</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach ($helpRequestDetail as $index => $data)
                    <tr class="odd:bg-gray-50 even:bg-white">
                        <td class="px-4 py-2 border border-gray-300 customer"> {{ $index + 1 }} </td>
                        <td class="px-4 py-2 border border-gray-300 customer"> {{ $data->c_name }} </td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $data->c_number }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $data->make_address }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">
                            @if ($data->status === 'pending')
                                <span class="bg-warning py-1 px-2 rounded text-white">Pending</span>
                            @elseif($data->status === 'resolve')
                                <span class="bg-success py-1 px-2 rounded text-white">Resolved</span>
                            @elseif($data->status === 'working')
                                <span class="bg-primary py-1 px-2 rounded text-white">Working</span>
                            @else
                                <span class="bg-danger py-1 px-2 rounded text-white">Refund</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $data->help_reason }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">{{ $data->remarks ?: 'N/A' }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">
                            {{ \Carbon\Carbon::parse($data->created_at)->format('d M, Y') }}</td>
                        <td class="px-4 py-2 border border-gray-300 customer">
                            @if ($data->status == 'pending' || $data->status == 'working')
                                <a href="{{ route('updateRemarksForm', $data->id) }}"
                                    class="btn btn-primary btn-sm">update</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($helpRequestDetail->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No Help Record Found</td>
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
