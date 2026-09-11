@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-12 mt-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="text-center">Edit Employee Payroll</h3>
                        </div>
                        
                        @if (session('error'))
                            <div class="alert alert-danger text-center m-2" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('payroll.update', $payroll->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mt-2">
                                        <label>Select Employee</label>
                                        <select class="form-control" name="employee_id" required>
                                            <option value="">-- Select Employee --</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ $payroll->employee_id == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                   <div class="col-6 mt-2">
                                        <label>Salary Month</label>
                                        <input class="form-control" type="month" name="month" value="{{ \Carbon\Carbon::parse($payroll->month)->format('Y-m') }}" required>
                                    </div>

                                    <div class="col-6 mt-2 mb-2">
                                        <label>Commission</label>
                                        <input class="form-control" type="number" name="commission" value="{{ $payroll->commission }}" placeholder="Enter Commission">
                                    </div>

                                     {{-- Manual Attendance Deduction --}}
                                     <div class="col-6 mt-2">                                     
                                         <label>Manual Attendance Deduction (PKR)</label>
                                         <input class="form-control" type="number" min="0" name="manual_absent_deduction" value="{{ $payroll->absent_deduction }}" placeholder="Enter attendance deduction amount">
                                     </div> 
                                     
                                     {{-- Manual Advance Deduction --}}
                                     <div class="col-6 mt-2">
                                         <label>Manual Advance Deduction (PKR)</label>
                                         <input class="form-control" type="number" min="0" name="manual_advance_deduction" value="{{ $payroll->advance_deduction }}" placeholder="Enter advance deduction amount">
                                     </div>

                                    <div class="col-6 mt-2">
                                        <label>Manual Salary (Optional)</label>
                                        <input class="form-control" type="number" name="manual_salary" value="{{ $payroll->basic_salary }}" placeholder="Leave empty to use default salary">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <a href="{{ route('payroll.show', $payroll->employee_id) }}" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Update Employee Payroll</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection