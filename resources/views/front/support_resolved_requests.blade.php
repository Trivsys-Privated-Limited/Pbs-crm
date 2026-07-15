@extends('layout.index')
@extends('front.nav')

@section('home')
<div class="w-full h-[80px] flex justify-center place-items-center bg-green-600">
    <span class="text-white text-2xl font-bold">Resolved Help Requests</span>
</div>

<div class="w-[100%] mx-auto mt-5 mb-5 overflow-x-auto px-3">
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

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
                <th class="px-4 py-2 border border-gray-300">AGENT NAME</th>
                <th class="px-4 py-2 border border-gray-300">DATE</th>
                <th class="px-4 py-2 border border-gray-300">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($helpRequestDetail as $index => $data)
                <tr class="odd:bg-gray-50 even:bg-white">
                    <td class="px-4 py-2 border border-gray-300 text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $data->c_name }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $data->c_number }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $data->make_address }}</td>

                    {{-- Yahan status dropdown ki zaroorat nahi kyunky yeh siraf resolved hain --}}
                    <td class="px-4 py-2 border border-gray-300 text-center">
                        <span class="bg-success py-1 px-2 rounded text-white">Resolved</span>
                    </td>

                    <td class="px-4 py-2 border border-gray-300">{{ $data->help_reason }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $data->remarks ?: 'N/A' }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $data->user_name }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($data->created_at)->format('d M, Y') }}</td>
                    <td class="px-4 py-2 border border-gray-300 text-center">
                        <a href="{{ route('help.chat', $data->id) }}" class="btn btn-primary btn-sm">Chat</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-5 text-gray-500">No Resolved Requests Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection