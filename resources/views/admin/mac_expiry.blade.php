@extends('layout.app')

@section('content')
    @include('admin.nav')
    @include('admin.saidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0">All Mac Expiry Report</h1>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">

                    @if (session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Agent Name</th>
                            <th>Customer Name</th>
                            <th>Customer Number</th>
                            <th>Mac Address</th>
                            <th>Expiry Date</th>
                            <th>Expired Days</th>
                            <th>Expired Months</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="bg-danger p-1 rounded">{{ $customer->status }}</span></td>

                                <td>{{ $customer->user->name ?? 'No Agent' }}</td>
                                <td>{{$customer->customer_name}}</td>
                                <td>{{$customer->customer_number}}</td>
                                <td>{{ $customer->mac_address ?? 'No Mac Address' }}</td>

                                <td>
                                    @if ($customer->expiry_date)
                                        {{ \Carbon\Carbon::parse($customer->expiry_date)->format('d M, Y') }}
                                    @else
                                        No Expiry
                                    @endif
                                </td>

                                <td>{{ number_format($customer->expired_days) }}</td>
                                <td>{{ number_format($customer->expired_months) }}</td>

                                <td>
                                    <a href="{{ route('cutomerUPdateSaleDetailFormVIew', $customer->id) }}"
                                        class="btn btn-primary">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
