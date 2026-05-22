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
                            <h3 class="text-center">Import Excel Data</h3>
                        </div>
                        <form action="{{ route('attendance.import.store') }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf

                            @if (session('success'))
                                <div class="alert alert-secondary text-center" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Import Excel File</label>
                                        <input type="file" class="form-control" name="file" id="">
                                        @error('file')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
