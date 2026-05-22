@extends('layout.index')
@extends('front.nav')

@section('home')
    <div class="container">
        <div class="row mt-7">
            <div class="col-6 mx-auto">
                @if (session('success'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('storeUpdateTrialData',$customer->id) }}" method="POST" autocomplete="off">
                    @csrf
                    <h2 class="text-xl font-bold text-center mb-2">Update Customer Details</h2>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Price</label>
                        <input type="number" class="form-control" name="price" placeholder="Enter Price"
                            value="{{ $customer->price }}" id="exampleInputPassword1">
                        @error('price')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        @php
                            $date = \Carbon\Carbon::parse($customer->regitr_date)->format('Y-m-d');
                        @endphp
                        <label for="exampleInputPassword1" class="form-label">Customer Registration Date</label>
                        <input type="date" class="form-control" name="date" placeholder="Enter Price" value="{{  $date }}" id="exampleInputPassword1">
                        @error('date')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" placeholder="Enetr Remarks" id="floatingTextarea">{{ $customer->remarks }}</textarea>
                        @error('remarks')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Renewal</label>
                        <input type="number" class="form-control" name="renewal" placeholder="Enter Renewal" value="{{ $customer->renewal }}" id="floatingTextarea">
                        @error('renewal')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <a href="{{ route('customerTrialTable') }}" class="btn btn-primary">Back</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
