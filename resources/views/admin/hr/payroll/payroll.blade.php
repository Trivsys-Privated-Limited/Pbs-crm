@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h1 class="m-0 d-inline">All Employee Payrolls</h1>
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
                @if (session('success'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger text-center" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedPayrolls as $employeeId => $payrolls)
                                @php
                                    $firstPayroll = $payrolls->first(); // First record to get user info
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $firstPayroll->user->name }}</td>
                                    <td>
                                        <a href="{{ route('payroll.show', $firstPayroll->user->id) }}" class="btn btn-sm btn-primary">
                                            View Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    let fileByMonth = document.querySelector('#filterbyMonth');
    let FilterMonthForm = document.querySelector('#filterbyMonthForm');
    if(fileByMonth && FilterMonthForm){
        fileByMonth.addEventListener('change', () => {
            FilterMonthForm.submit();
        });
    }
</script>
@endsection
