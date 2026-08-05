@extends('layout.index')
@extends('front.nav')

@section('home')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Edit Customer Detail</h2>

    <form action="{{ route('storeSupportNumber', $customer->id) }}" method="POST">
        @csrf

        {{-- Customer Name (Read-only / Disabled) --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Customer Name</label>
            <input type="text" 
                   value="{{ $customer->name ?? 'No Name' }}" 
                   class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 focus:outline-none" 
                   disabled>
        </div>

        {{-- Select Status --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Select Status</label>
            <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                <option value="">-- Select Status --</option>
                <option value="Satisfied" {{ $customer->status == 'Satisfied' || $customer->status == 'Satisfied' ? 'selected' : '' }}>Satisfied</option>
                <option value="Non Satisfied" {{ $customer->status == 'Non Satisfied' ? 'selected' : '' }}>Non Satisfied</option>
                <option value="Not Answering" {{ $customer->status == 'Not Answering' ? 'selected' : '' }}>Not Answering</option>
                <option value="Call me Back" {{ $customer->status == 'Call me Back' ? 'selected' : '' }}>Call me Back</option>
            </select>
        </div>

        {{-- Remarks --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Remarks</label>
            <textarea name="remarks" 
                      rows="3" 
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" 
                      placeholder="Enter remarks" required>{{ $customer->remarks }}</textarea>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-2">
            <a href="{{ url()->previous() }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium">
                Back
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium">
                Update
            </button>
        </div>
    </form>
</div>
@endsection