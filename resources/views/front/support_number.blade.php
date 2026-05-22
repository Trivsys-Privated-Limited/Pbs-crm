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
                            {{ $customer->name }}
                        </td>

                        <td class="px-4 py-2 border">
                            {{ $customer->number }}
                        </td>

                        <form action="{{ route('storeSupportNumber', $customer->id) }}" method="POST">
                            @csrf

                            {{-- STATUS --}}
                            @if ($customer->status == '')
                                <td class="px-4 py-2 border">
                                    <select name="status" class="w-full border px-2 py-1 rounded" required>
                                        <option value="">-- Select --</option>
                                        <option value="Stisfied">Stisfied</option>
                                        <option value="Non Satisfied">Non Satisfied</option>
                                        <option value="Not Answering">Not Answering</option>
                                        <option value="Call me Back">Call me Back</option>
                                    </select>
                                </td>
                            @else
                                <td class="px-4 py-2 border">
                                    @if ($customer->status == 'Stisfied')
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
                                    @else
                                        <span class="px-3 py-1 text-white text-sm rounded-full bg-blue-600">
                                            {{ $customer->status }}
                                        </span>
                                    @endif
                                </td>
                            @endif

                            {{-- REMARKS --}}
                            @if ($customer->remarks == '')
                                <td class="px-4 py-2 border">
                                    <input type="text" name="remarks" class="w-full border px-2 py-1 rounded"
                                        placeholder="Enter remarks" required>
                                </td>
                            @else
                                <td class="px-4 py-2 border">
                                    {{ $customer->remarks }}
                                </td>
                            @endif

                            {{-- ACTION --}}
                            <td class="px-4 py-2 border text-center">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                    Save
                                </button>
                            </td>
                        </form>

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
        {{-- Pagination Links --}}
        <div class="mt-5 ">
            {{ $supportNumbers->links() }}
        </div>
    </div>
@endsection
