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
                <form action="{{ route('leave.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <h2 class="text-xl font-bold text-center mb-2">Request Leave Form</h2>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date"
                            id="exampleInputEmail1"aria-describedby="emailHelp">
                        @error('start_date')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" id="exampleInputPassword1">
                        @error('end_date')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Upload Image (Optional)</label>
                        <input type="file" class="form-control" name="img" placeholder="Enter Customer Email"
                            id="exampleInputPassword1">
                        @error('img')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Leave Reason</label>
                        <textarea class="form-control" name="reason" placeholder="Enetr Leave  Reason" id="floatingTextarea"></textarea>
                        @error('reason')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
