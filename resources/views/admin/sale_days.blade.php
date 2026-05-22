@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-12   mt-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="text-center">Add Customer Sale Days</h3>
                        </div>
                        <form action="{{ route('addSaleCustomerStatus', $customer->id) }}" method="POST"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Mac Address</label>
                                        <input type="text" class="form-control" name="make_address"
                                            id="exampleInputEmail1" placeholder="Enter Customer Make Address"
                                            value="00:177:79:">
                                        @error('make_address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" id="exampleInputEmail1"
                                            placeholder="Enter Customer Email">
                                        @error('start_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Expri Date</label>
                                        <input type="date" class="form-control" name="end_date" id="exampleInputEmail1"
                                            placeholder="Enter Customer Email">
                                        @error('end_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewAgentSaleTable') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
