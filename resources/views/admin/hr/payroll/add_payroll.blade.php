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
                            <h3 class="text-center">Add Employee Payroll</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('payroll.store') }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Select Employee</label>
                                        <select class="form-control" name="employee_id"
                                            aria-label="Default select example">
                                            <option value="">-- Select Employee --</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="exampleInputEmail1">Salary Month</label>
                                        <input class="form-control" type="month" name="month" id="formFile">
                                        @error('month')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2 mb-2">
                                        <label for="exampleInputEmail1">Commission</label>
                                        <input class="form-control" type="text" name="commission" id="formFile"
                                            placeholder="Enter Commission">
                                        @error('commission')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label>Attendance Deduction</label>
                                        <select class="form-control" name="attendance_deduction">
                                            <option value="yes">Apply Deduction</option>
                                            <option value="no">No Deduction</option>
                                        </select>
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="manualSalary">Manual Salary (Optional)</label>
                                        <input class="form-control" type="number" name="manual_salary" id="manualSalary"
                                            placeholder="Leave empty to use default salary">
                                    </div>

                                    <div class="col-6 mt-2">
                                        <label for="manualDeduction">Manual Deduction (Optional)</label>
                                        <input class="form-control" type="number" name="manual_deduction" id="manualDeduction"
                                            placeholder="Enter manual deduction amount">
                                    </div>


                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <a href="{{ route('payroll.index') }}" class="btn btn-primary">Back</a>
                                        <button class="btn btn-primary">Save Employee Payroll</button>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </section> --}}
@endsection
