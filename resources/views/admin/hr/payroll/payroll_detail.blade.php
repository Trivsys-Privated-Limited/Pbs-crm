@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h1 class="m-0 d-inline">All Employee Slips</h1>
                </div>
                <div class="col-sm-2">
                    <h1 class="m-0 d-inline">
                        <a href="{{ route('payroll.create') }}" class="btn btn-primary">Add Employee Payroll</a>
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success text-center" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Salary Month</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $serial = 1; @endphp
                            @foreach ($EmployeePayroll as $employeeId => $payrolls)
                                @foreach ($payrolls as $payroll)
                                    <tr>
                                        <td>{{ $serial++ }}</td>
                                        <td>{{ $payroll['user']['name'] }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $payroll['month'])->format('F Y') }}</td>
                                        <td>
                                            <a href="{{ route('payroll.showPayroll', $payroll['id']) }}" class="btn btn-sm btn-primary">View Pay Slip</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
