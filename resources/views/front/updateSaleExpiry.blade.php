@extends('layout.index')
@extends('front.nav')
@section('home')
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="text-center">Update Sale Expiry</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('updateSaleExpiry', $customer->id) }}" method="POST" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 mt-2">
                                    <label for="exampleInputEmail1">Registration Date</label>
                                    <input type="date" class="form-control" name="date" id="expiry_date" placeholder="Enter Months">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 mt-2">
                                    <label for="exampleInputEmail1">DURATION</label>
                                    <input type="number" class="form-control" name="expiry_date" id="expiry_date" placeholder="Enter Months">
                                    @error('expiry_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 mt-2">
                                    <label for="exampleInputEmail1">Price</label>
                                    <input type="number" class="form-control" name="price" id="price">
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                
                                <div class="col-12 mt-2">
                                    <label for="exampleInputEmail1">Remarks</label>
                                    <input type="text" class="form-control" name="remarks" id="remarks">
                                    @error('remarks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>




                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
