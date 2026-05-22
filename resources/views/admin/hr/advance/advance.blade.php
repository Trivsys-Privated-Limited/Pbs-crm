@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">Manage Advances</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('advance.create') }}" class="btn btn-primary">Add
                                Advance</a></h1>
                    </div>
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
                                    <th>ADVANCE AMOUNT</th>
                                    <th>MONTHLY PAY AMOUNT</th>
                                    <th>REMAINING AMOUNT</th>
                                    <th>START MONTH</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($advances as $index => $advance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $advance->created_at->format('d-M-Y') }}</td>
                                        <td>{{ $advance->user->name }}</td>
                                        <td>PKR {{ $advance->advance_amount }}</td>
                                        <td>PKR {{ $advance->monthly_amount }}</td>
                                        <td>PKR {{ $advance->remaining_amount }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::createFromFormat('Y-m', $advance->start_month)->format('F Y') }}
                                        </td>
                                        <td>
                                            @if ($advance->status == 'active')
                                                <span class="btn btn-sm btn-primary">{{ $advance->status }}</span>
                                            @elseif($advance->status == 'completed')
                                                <span class="btn btn-sm btn-success">{{ $advance->status }}</span>
                                            @endif
                                        </td>

                                        <td><a href="{{route('advance.show',$advance->id)}}" class="btn btn-sm btn-primary">View Detail</a>
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
