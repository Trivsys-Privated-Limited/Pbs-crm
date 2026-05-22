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
                            <h3 class="text-center">Update Customer Details</h3>
                        </div>
                        <form action="{{ route('cutomerUPdateDetailTrialStore', $customer->id) }}" method="POST"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Customer Name</label>
                                        <input type="text" class="form-control" name="customer_name"
                                            id="exampleInputEmail1" placeholder="Enter Customer Name"
                                            value="{{ $customer->customer_name }}">
                                        @error('customer_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Customer Email</label>
                                        <input type="text" class="form-control" name="customer_email"
                                            id="exampleInputEmail1" placeholder="Enter Customer Email"
                                            value="{{ $customer->customer_email }}">
                                        @error('customer_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Customer Phone</label>
                                        <input type="text" class="form-control" name="customer_number"
                                            id="exampleInputEmail1" placeholder="Enter Customer Phone Number"
                                            value="{{ $customer->customer_number }} ">
                                        @error('customer_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Price</label>
                                        <input type="text" class="form-control" name="price" id="exampleInputEmail1"
                                            placeholder="Enter Price" value="{{ $customer->price }}">
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">status</label>
                                        <select class="form-select" name="status" aria-label="Default select example">
                                            <option value="{{ $customer->status }}" selected>{{ $customer->status }}
                                            </option>
                                            <option value="sale">Sale</option>
                                            <option value="lead">Lead</option>
                                            <option value="trial">Trial</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-6 mt-2">
                                        <label for="floatingTextarea">Remarks</label>
                                        <textarea class="form-control" name="remarks" placeholder="Enter Remarks" id="floatingTextarea">{{ $customer->remarks }}</textarea>
                                        @error('remarks')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Mac Address</label>
                                        <input type="text" class="form-control" name="make_address" id="exampleInputEmail1"
                                            placeholder="Enter Mac Address" value="{{ $customer->make_address }}">
                                        @error('make_address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">CUSTOMER REGISTRATION DATE</label>
                                        <input type="date" class="form-control" name="date" id="exampleInputEmail1"
                                            placeholder="Enter Mac Address" value="{{ $customer->regitr_date }}">
                                        @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewAgentTrialTable') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
