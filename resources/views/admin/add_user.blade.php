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
                            <h3 class="text-center">Add New User</h3>
                        </div>
                        <form action="{{ route('storeUserdetail') }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Name</label>
                                        <input type="text" class="form-control" name="user_name" id="exampleInputEmail1"
                                            placeholder="Enter User Name" value="{{ old('user_name') }}">
                                        @error('user_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input type="text" class="form-control" name="user_email" id="exampleInputEmail1"
                                            placeholder="Enter User Email" value="{{ old('user_email') }}">
                                        @error('user_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1"> Phone</label>
                                        <input type="Number" class="form-control" name="user_phone" id="exampleInputEmail1"
                                            placeholder="Enter User Phone Number" value="{{ old('user_phone') }}">
                                        @error('user_phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1"> Address</label>
                                        <input type="text" class="form-control" name="user_address"
                                            id="exampleInputEmail1" placeholder="Enter User Address"
                                            value="{{ old('user_address') }}">
                                        @error('user_address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Role</label>
                                        <select class="form-select" name="role" aria-label="Default select example">
                                            <option selected>-- Select Role --</option>
                                            <option value="user">user</option>
                                            <option value="support">support</option>
                                        </select>
                                        @error('role')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>




                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1"> Password</label>
                                        <input type="text" class="form-control" name="user_password"
                                            id="exampleInputEmail1" placeholder="Enter Password"
                                            value="{{ old('user_password') }}">
                                        @error('user_password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-12 mt-2">
                                        <label for="userPasswordConfirmation">Confirm Password</label>
                                        <input type="password" class="form-control" name="user_password_confirmation"
                                            id="userPasswordConfirmation" placeholder="Enter Confirm password">
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewUserTable') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
