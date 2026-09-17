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
                                <th>Slip Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php $serial = 1; @endphp
                            @foreach ($EmployeePayroll as $employeeId => $payrolls)
                                @foreach ($payrolls as $payroll)
                                    <tr>
                                        <td>{{ $serial++ }}</td>
                                        <td>{{ $payroll['user']['name'] }}</td>
                                       <!-- <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $payroll['month'])->format('F Y') }}</td> -->
                                       <td>{{ \Carbon\Carbon::parse($payroll['month'])->format('F Y') }}</td> 
                                        <!-- <td>
                                            <a href="{{ route('payroll.showPayroll', $payroll['id']) }}" class="btn btn-sm btn-primary">View Pay Slip</a>
                                        </td> -->
                                       <!-- <td>
                                            <a href="{{ route('payroll.showPayroll', $payroll['id']) }}" class="btn btn-sm btn-primary mb-1">View Pay Slip</a>
                                            <a href="{{ route('payroll.edit', $payroll['id']) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                                            <a href="{{ route('payroll.destroy', $payroll['id']) }}" onclick="return confirm('Are you sure you want to delete this payroll?')" class="btn btn-sm btn-danger mb-1">Delete</a>
                                        </td> -->
                                      <!--  <td>
                                            {{-- Agar employee ne request ki hai, to Accept ka button dikhao --}}
                                            @if($payroll->slip_status == 'requested')
                                                <a href="{{ route('payroll.approveSlip', $payroll['id']) }}" class="btn btn-sm btn-success mb-1">Accept Request</a>
                                            @elseif($payroll->slip_status == 'approved')
                                                <span class="badge badge-success mb-1" style="font-size: 12px; padding: 5px 8px;">Approved</span>
                                            @endif
                                        
                                            {{-- Baqi purane buttons --}}
                                            <a href="{{ route('payroll.showPayroll', $payroll['id']) }}" class="btn btn-sm btn-primary mb-1">View Pay Slip</a>
                                            <a href="{{ route('payroll.edit', $payroll['id']) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                                            <a href="{{ route('payroll.destroy', $payroll['id']) }}" onclick="return confirm('Are you sure you want to delete this payroll?')" class="btn btn-sm btn-danger mb-1">Delete</a>
                                        </td> -->
                                        {{-- Column 1: Actions --}}
                                        <td>
                                            <a href="{{ route('payroll.showPayroll', $payroll['id']) }}" class="btn btn-sm btn-primary mb-1">View Pay Slip</a>
                                            <a href="{{ route('payroll.edit', $payroll['id']) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                                            <a href="{{ route('payroll.destroy', $payroll['id']) }}" onclick="return confirm('Are you sure you want to delete this payroll?')" class="btn btn-sm btn-danger mb-1">Delete</a>
                                        </td>
                                        {{-- Column 2: Slip Status --}}
                                        <td>
                                            @if($payroll->slip_status == 'requested')
                                                <a href="{{ route('payroll.approveSlip', $payroll['id']) }}" class="btn btn-sm btn-success mb-1">Accept Request</a>
                                            @elseif($payroll->slip_status == 'approved')
                                                <span class="badge badge-success mb-1" style="font-size: 13px; padding: 6px 10px;">✔ Approved</span>
                                                <a href="{{ route('payroll.rejectSlip', $payroll['id']) }}" onclick="return confirm('Are you sure you want to reject this slip?')" class="btn btn-sm btn-danger mb-1 ml-1">Reject</a>
                                            @else
                                                <span class="badge badge-secondary mb-1" style="font-size: 12px; padding: 5px 8px;">Not Requested</span>
                                            @endif
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
