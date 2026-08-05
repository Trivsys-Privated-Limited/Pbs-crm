@extends('layout.index')
@extends('front.nav')

@section('home')
    <div class="w-full mx-auto mt-5 mb-5 overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-200 mx-auto">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border">S.NO</th>
                    <th class="px-4 py-2 border">CUSTOMER NAME</th>
                    <th class="px-4 py-2 border">PHONE NUMBER</th>
                    <th class="px-4 py-2 border">STATUS</th>
                    <th class="px-4 py-2 border">REMARKS</th>
                    <th class="px-4 py-2 border">ACTION</th>
                </tr>
            </thead>

            <tbody>
                @forelse($supportNumbers as $key => $customer)
                    <tr class="odd:bg-gray-50 even:bg-white">

                        <td class="px-4 py-2 border text-center">
                            {{ $key + 1 }}
                        </td>

                        <td class="px-4 py-2 border">
                            {{ $customer->name ?? 'No Name' }}
                        </td>

                        <td class="px-4 py-2 border">
                            {{ $customer->number }}
                        </td>

                        {{-- STATUS BADGES --}}
                        <td class="px-4 py-2 border text-center">
                            @if ($customer->status == 'Satisfied' || $customer->status == 'Satisfied')
                                <span class="px-3 py-1 text-white text-sm rounded-full bg-green-600">
                                    {{ $customer->status }}
                                </span>
                            @elseif($customer->status == 'Non Satisfied')
                                <span class="px-3 py-1 text-white text-sm rounded-full bg-red-600">
                                    {{ $customer->status }}
                                </span>
                            @elseif($customer->status == 'Not Answering')
                                <span class="px-3 py-1 text-white text-sm rounded-full bg-yellow-500">
                                    {{ $customer->status }}
                                </span>
                            @elseif($customer->status)
                                <span class="px-3 py-1 text-white text-sm rounded-full bg-blue-600">
                                    {{ $customer->status }}
                                </span>
                            @else
                                <span class="text-gray-400 font-italic">N/A</span>
                            @endif
                        </td>

                        {{-- REMARKS --}}
                        <td class="px-4 py-2 border">
                            {{ $customer->remarks ?? 'N/A' }}
                        </td>

                        {{-- ACTION (EDIT BUTTON) --}}
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('editSupportNumber', $customer->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded inline-flex items-center gap-1 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-gray-500">
                            No Support Numbers Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-5">
            {{ $supportNumbers->links() }}
        </div>
    </div>
@endsection