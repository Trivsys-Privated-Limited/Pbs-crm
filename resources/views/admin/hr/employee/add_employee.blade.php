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
                            <h3 class="text-center">Add Employee Recode</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Employee Name</label>
                                        <select class="form-control" name="employee_name"
                                            aria-label="Default select example">
                                            <option value="">-- Select Employee Name --</option>
                                            @foreach ($getAllUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Upload Employee Resume</label>
                                        <input class="form-control" type="file" name="resume" id="formFile">
                                        @error('resume')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Upload Employee Offer Letter (Optional)</label>
                                        <input class="form-control" type="file" name="offer_letter" id="formFile">
                                        @error('offer_letter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Upload Employee CNIC</label>
                                        <input class="form-control" type="file" name="cnic" id="formFile">
                                        @error('cnic')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Upload Employee Profile Image (Optional)</label>
                                        <input class="form-control" type="file" name="profile_img" id="formFile">
                                        @error('profile_img')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Employee Salary</label>
                                        <input type="number" class="form-control" name="salary" id="exampleInputEmail1"
                                            placeholder="Enter Employee Salary">
                                        @error('salary')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Employee Total Monthly Late (Optional)</label>
                                        <input type="number" class="form-control" name="late" id="exampleInputEmail1"
                                            placeholder="Enter Employee Total Monthly Late" value="{{ old('late') }}">
                                        @error('late')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Employee Total Monthly Leave (Optional)</label>
                                        <input type="text" class="form-control" name="leave" id="exampleInputEmail1"
                                            placeholder="Employee Total Monthly Leave" value="{{ old('leave') }}">
                                        @error('leave')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Employee Total Monthly Target</label>
                                        <input type="number" class="form-control" name="target" id="exampleInputEmail1"
                                            placeholder="Employee Total Monthly Target" value="{{ old('target') }}">
                                        @error('target')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Employee Type</label>
                                        <select class="form-select" name="employe_type"   aria-label="Default select example">
                                            <option value="">-- Select Employee Type --</option>
                                            <option value="Senior">Senior</option>
                                            <option value="Junior">Junior</option>
                                        </select>
                                        @error('employe_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a href="{{ route('employee.index') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save Employee Recode</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </section> --}}
@endsection
