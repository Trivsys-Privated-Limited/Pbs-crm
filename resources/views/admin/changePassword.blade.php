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
                            <h3 class="text-center">Change Password</h3>
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                   {{session('success')}}
                                </div>
                            @endif
                        </div>
                        <form action="{{ route('changePasswordStore',$admin) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Enter New Password</label>
                                        <input type="text" class="form-control" name="password" id="exampleInputEmail1"
                                            placeholder="Enter New Password" value="">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="userPasswordConfirmation">Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                            id="userPasswordConfirmation" placeholder="Enter Confirm password">
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
