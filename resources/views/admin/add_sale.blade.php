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
                            <h3 class="text-center">Add New Customer Details</h3>
                        </div>
                        <form action="{{ route('saveNewAgentSale') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Customer Name</label>
                                        <input type="text" class="form-control" name="customer_name"
                                            id="exampleInputEmail1" placeholder="Enter Customer Name" value="{{ old('customer_name') }}">
                                        @error('customer_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Customer Email (optional)</label>
                                        <input type="text" class="form-control" name="customer_email"
                                            id="exampleInputEmail1" placeholder="Enter Customer Email" value="{{ old('customer_email') }}">
                                        @error('customer_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Customer Phone</label>
                                        <input type="number" class="form-control" name="customer_number"
                                            id="exampleInputEmail1" placeholder="Enter Customer Phone Number" value="{{ old('customer_number') }}">
                                        @error('customer_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Price</label>
                                        <input type="number" class="form-control" name="price" id="exampleInputEmail1"
                                            placeholder="Enter Price" value="{{ old('customer_price') }}">
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Select Status</label>
                                        <select class="form-select" name="status" aria-label="Default select example">
                                            <option>--Select Status--</option>
                                            <option value="sale">Sale</option>
                                            <option value="lead">Lead</option>
                                            <option value="trial">Trial</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-6 mt-2">
                                        <label for="floatingTextarea">Remarks</label>
                                        <textarea class="form-control" name="remarks" placeholder="Enter Remarks" id="floatingTextarea"></textarea>
                                        @error('remarks')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="floatingTextarea">Mac Address (optional)</label>
                                        <input type="text" class="form-control" name="make_address"
                                            id="exampleInputEmail1" placeholder="Enter Mac Address">
                                        @error('make_address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">CUSTOMER REGISTRATION DATE</label>
                                        <input type="date" class="form-control" name="date" id="exampleInputEmail1"
                                            placeholder="Enter Mac Address">
                                        @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Mac Address Expiry Date (optional)</label>
                                        <input type="date" class="form-control" name="expiry_date"
                                            id="exampleInputEmail1" placeholder="Enter Mac Address">
                                        @error('expiry_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Select Agent Name</label>
                                        <select class="form-select" name="agent" aria-label="Default select example">
                                            <option>--Select Agent--</option>
                                            @foreach ($allAgent as $agent)
                                             <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('agent')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewAgentSaleTable') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
