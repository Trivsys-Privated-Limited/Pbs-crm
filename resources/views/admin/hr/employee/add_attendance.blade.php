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
                            <h3 class="text-center">Add Employee Attendance</h3>
                        </div>
                        <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Employee Name</label>
                                        <select class="form-control" name="employee_name"
                                            aria-label="Default select example">
                                            <option value="">-- Select Employee Name --</option>
                                            @foreach ($employees as $user)
                                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Attendance Date</label>
                                        <input class="form-control" type="date" name="date" id="formFile">
                                        @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Check In</label>
                                        <input class="form-control" type="time" name="checkin" id="formFile">
                                        @error('checkin')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Check Out</label>
                                        <input class="form-control" type="time" name="checkout" id="formFile">
                                        @error('checkout')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Status</label>
                                        <select class="form-select" name="status" aria-label="Default select example">
                                            <option selected>-- Select Status --</option>
                                            <option value="Present">Present</option>
                                            <option value="Absent">Absent</option>
                                            <option value="Half Day">Half Day</option>
                                        </select>
                                        @error('profile_img')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('attendance.index') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Add Employee Attendance</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
