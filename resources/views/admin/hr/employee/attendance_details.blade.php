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
                                        <th>DATE</th>
                                        <th>CHECK IN</th>
                                        <th>CHECK OUT</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $index => $attendance)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $attendance->employee_name }}</td>
                                            <td>
                                                @if (is_numeric($attendance->date))
                                                    {{ \Carbon\Carbon::createFromTimestamp($attendance->date)->format('d M Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (is_numeric($attendance->check_in))
                                                    {{ gmdate('H:i', $attendance->check_in) }}
                                                @else
                                                    {{ $attendance->check_in }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (is_numeric($attendance->check_out))
                                                    {{ gmdate('H:i', $attendance->check_out) }}
                                                @else
                                                    {{ $attendance->check_out }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($attendance->status === 'Present')
                                                    <span class="badge badge-success">Present</span>
                                                 @elseif ($attendance->status === 'Absent')
                                                    <span class="badge badge-danger">Absent</span>
                                                @else
                                                    <span class="badge badge-warning">{{ $attendance->status }}</span>
                                                @endif
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
