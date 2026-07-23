@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">All Employee Recode</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('employee.create') }}" class="btn btn-primary">Add Employee
                                Recode</a></h1>
                    </div><!-- /.col -->
                    {{-- <div class="col-sm-6">
                        <form action="{{ route('viewUserTable') }}" method="get" id="filterbyMonthForm">
                            <label for="exampleInputEmail1">Filter By Month Sale Count</label>
                            <input type="month" class="form-control" name="date"
                                aria-label="Text input with 2 dropdown buttons" id="filterbyMonth">
                        </form>
                    </div><!-- /.col --> --}}
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            @if (session('success'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>EMPLOYEE NAME</th>
                                    <th>EMPLOYEE SALARY</th>
                                    <th>EMPLOYEE CURRENT MONTH TARGET</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($getAllEmployees as $index => $employee)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $employee->user->name }}</td>
                                        <td>PKR {{ number_format($employee->salary) }}</td>
                                        <td>$ {{ number_format($employee->target) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                                <a href="{{route('employee.show',$employee->id)}}" class="btn btn-primary btn-sm">View Profile</a>
                                                <a href="{{route('employee.destroy',$employee->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee record?')">Delete</a>
                                            </div>
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
        fileByMonth.addEventListener('change', () => {
            FilterMonthForm.submit();
        });
    </script>
@endsection
