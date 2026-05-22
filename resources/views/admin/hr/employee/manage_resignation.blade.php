@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">All Employee Resignation Recode</h1>
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
                                @foreach ($resignations as $key => $resignation)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $resignation->employee->name }}</td>
                                        <td>
                                            <a href="{{route('resignation.show',$resignation->id)}}" class="btn btn-sm btn-primary">View Resignation Letter</a>
                                            @if($resignation->status == 'Pending')
                                                <a href="{{route('resignation.accept',$resignation->id)}}" class="btn btn-sm btn-success">Accept</a>
                                                <a href="{{route('resignation.reject',$resignation->id)}}" class="btn btn-sm btn-danger">Reject</a>
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
