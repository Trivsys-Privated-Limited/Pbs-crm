@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">All Employee Attendance</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-2">
                         <a href="{{route('attendance.create')}}" class="btn btn-sm btn-primary">Add Attendance</a>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>EMPLOYEE NAME</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->employee_name }}</td>
                                        <td>
                                            <a href="{{ route('attendance.show', $attendance->employee_name) }}" class="btn btn-primary btn-sm">View Attendance</a>
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
@endsection
