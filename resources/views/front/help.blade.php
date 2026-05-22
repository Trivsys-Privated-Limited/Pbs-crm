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
                <form action="{{ route('storeHelpRequest') }}" method="POST" autocomplete="off">
                    @csrf
                    <h2 class="text-xl font-bold text-center mb-2">Help Request Form</h2>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Cutomer Name</label>
                        <input type="text" class="form-control" name="customer_name" id="exampleInputEmail1"
                            placeholder="Enter Customer Name" aria-describedby="emailHelp">
                        @error('customer_name')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Customer Number</label>
                        <input type="number" class="form-control" name="customer_number"
                            placeholder="Enter Customer Number" id="exampleInputPassword1">
                        @error('customer_number')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Customer Email</label>
                        <input type="email" class="form-control" name="customer_email" placeholder="Enter Customer Email"
                            id="exampleInputPassword1">
                        @error('customer_email')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Mac Address</label>
                        <input type="text" class="form-control" name="m_address" placeholder="Enter Make Address"
                            value="00:1a:79:" id="exampleInputPassword1">
                        @error('m_address')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Help Reason</label>
                        <textarea class="form-control" name="help_reason" placeholder="Enetr Help Request Reason" id="floatingTextarea"></textarea>
                        @error('help_reason')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
