@extends('layout.index')
@if(Auth::user()->role === 'user')
    @extends('front.nav')
@else
    @extends('admin.nav')
@endif

@section('home')
<div class="container mx-auto mt-5 mb-5 p-4 bg-white rounded shadow" style="max-width: 800px; @if(Auth::user()->role !== 'user') margin-left: 260px; @endif">
    <h2 class="text-2xl font-bold mb-4">Chat: Help Request #{{ $help->id }}</h2>
    
    <div class="mb-4 p-3 bg-gray-100 rounded" style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <p><strong>Customer:</strong> {{ $help->c_name }} ({{ $help->c_number }})</p>
        <p><strong>Status:</strong> <span class="uppercase font-bold">{{ $help->status }}</span></p>
        <p><strong>Reason:</strong> {{ $help->help_reason }}</p>
    </div>

    <div class="chat-box bg-gray-50 border p-4 rounded mb-4" style="height: 400px; overflow-y: auto; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        @if($help->messages->isEmpty())
            <p class="text-center text-gray-500 mt-10" style="text-align: center; margin-top: 40px; color: #6b7280;">No messages yet. Start the conversation!</p>
        @else
            @foreach($help->messages as $msg)
                <div class="mb-3 {{ $msg->sender_id == Auth::id() ? 'text-right' : 'text-left' }}" style="margin-bottom: 12px; text-align: {{ $msg->sender_id == Auth::id() ? 'right' : 'left' }};">
                    <div class="inline-block p-2 rounded {{ $msg->sender_id == Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black' }}" style="max-width: 70%; display: inline-block; text-align: left; padding: 8px 12px; border-radius: 8px; {{ $msg->sender_id == Auth::id() ? 'background-color: #3b82f6; color: white;' : 'background-color: #e5e7eb; color: black;' }}">
                        <div style="font-size: 0.8rem; opacity: 0.8; margin-bottom: 4px;">
                            {{ $msg->sender->name }} - {{ $msg->created_at->format('d M h:i A') }}
                        </div>
                        <div>{{ $msg->message }}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <form action="{{ route('help.sendMessage', $help->id) }}" method="POST">
        @csrf
        <div class="flex gap-2" style="display: flex; gap: 10px;">
            <input type="text" name="message" class="w-full p-2 border rounded form-control flex-grow-1" placeholder="Type your message here..." required style="flex-grow: 1; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded btn btn-primary" style="background-color: #2563eb; color: white; padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer;">Send</button>
        </div>
    </form>
</div>
@endsection
