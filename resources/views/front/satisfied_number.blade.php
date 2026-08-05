@extends('layout.index')
@extends('front.nav')

@section('home')
 <div class="w-full h-[80px] flex justify-center place-items-center bg-green-600">
    <span class="text-white text-2xl font-bold">Satisfied Customer Numbers</span>
</div>
    <div class="w-full mx-auto mt-5 mb-5 overflow-x-auto">
       <!-- <h2 class="text-xl font-bold mb-4 text-green-700">Satisfied Support Numbers</h2> -->
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
                        <td class="px-4 py-2 border text-center">{{ $key + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $customer->name ?? 'No Name' }}</td>
                        <td class="px-4 py-2 border">{{ $customer->number }}</td>
                        <td class="px-4 py-2 border text-center">
                            <span class="px-3 py-1 text-white text-sm rounded-full bg-green-600">
                                {{ $customer->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border">{{ $customer->remarks ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('editSupportNumber', $customer->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded inline-flex items-center gap-1 text-sm font-medium">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-gray-500">
                            No Satisfied Numbers Found
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
