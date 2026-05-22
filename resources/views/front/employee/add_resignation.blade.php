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
                <form action="{{ route('resignation.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <h2 class="text-xl font-bold text-center mb-2">Request Resignation Form</h2>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Resignation Date</label>
                        <input type="date" class="form-control" name="resignation_date"
                            id="exampleInputEmail1"aria-describedby="emailHelp">
                        @error('resignation_date')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Resignation Reason</label>
                        <textarea class="form-control" name="reason" placeholder="Enetr Resignation  Reason" id="floatingTextarea"></textarea>
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
