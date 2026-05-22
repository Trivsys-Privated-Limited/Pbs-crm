@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">Manage Advances Deductions</h1>
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
                                    <th>CREATED</th>
                                    <th>EMPLOYEE NAME</th>
                                    <th>DEDUCTED AMOUNT</th>
                                    <th>SALARY MONTH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($advances as $index => $advance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $advance->created_at->format('d-M-Y') }}</td>
                                        <td>{{ $advance->user->name }}</td>
                                        <td>{{ $advance->deducted_amount }}</td>
                                      <td>{{ \Carbon\Carbon::parse($advance->month)->format('M-Y') }}</td>
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
