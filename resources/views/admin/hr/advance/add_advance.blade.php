@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    {{-- <section class="content"> --}}
    <div class="content-wrapper">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-12   mt-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="text-center">Add Advance</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('advance.store') }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Select Employee</label>
                                        <select class="form-select" aria-label="Default select example" name="employee">
                                            <option selected disabled>-- Select Employee --</option>
                                            @foreach ($users as $index => $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Advance Amount</label>
                                        <input class="form-control" type="number" name="amount"
                                            placeholder="Enter Advance Amount">
                                        @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2 ">
                                        <label for="exampleInputEmail1">Monthly Amount</label>
                                        <input class="form-control" type="number" name="monthly_amount"
                                            placeholder="Enter Monthly Amount">
                                        @error('monthly_amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-6 mt-2 ">
                                        <label for="exampleInputEmail1">Start Month</label>
                                        <input class="form-control" type="month" name="start_month"
                                            placeholder="Enter Monthly Amount">
                                        @error('start_month')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a href="" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save Advance Recode</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </section> --}}


    {{-- <script>
        let advance_duration = document.getElementById('advance_duration');
        let monthly_amount = document.getElementById('monthly_amount');
        advance_duration.addEventListener('change', function() {
            if (this.value === 'One Time Pyment') {
                monthly_amount.style.display = 'none';
            } else if (this.value === 'Monthly') {
                monthly_amount.style.display = 'block';
            }
        });
    </script> --}}
@endsection
