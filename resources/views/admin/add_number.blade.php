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
                            <h3 class="text-center">Add New Numbers</h3>
                        </div>
                        <form action="{{ route('storeNumbers') }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Enter Numbers (one per line, only +1 numbers
                                            allowed)</label>
                                        <textarea class="form-control" name="customerNumber" rows="10" placeholder="+1 408 XXX XXXX  " id="floatingTextarea"></textarea>
                                        @error('customerNumber')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewNumbersTable') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
