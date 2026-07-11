@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 d-inline">All Agent Trial Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DashBord</a></li>
                            <li class="breadcrumb-item active">All Agent Trial Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class='container-fluid'>
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
                                    <th>CUSTOMER REGISTRATION DATE</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>CUSTOMER EMAIL</th>
                                    <th>CUSTOMER PHONE</th>
                                    <th>PRICE</th>
                                    <th>REMARKS</th>
                                    <th>STATUS</th>
                                    <th>AGENT NAME</th>
                                    <th>MAC ADDRESS</th>
                                    <th>DATE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $index => $customer)
                                    <tr>
                                        <td> {{ $index + 1 }} </td>
                                        <td>
                                            @if ($customer->regitr_date)
                                                {{ \Carbon\Carbon::parse($customer->regitr_date)->format('d M, Y') }}
                                            @else
                                                No Registration Date
                                            @endif
                                        </td>
                                        <td> {{ $customer->customer_name }} </td>
                                        <td>{{ $customer->customer_email }}</td>
                                        <td>{{ $customer->customer_number }}</td>
                                        <td>${{ $customer->price }}</td>
                                        <td>{{ $customer->remarks }}</td>
                                        <td><span
                                                class="bg-danger py-1 px-2 rounded block mt-5 cursor-pointer">{{ $customer->status }}</span>
                                        </td>

                                        <td> {{ $customer->user_name }}</td>
                                        <td>
                                            @if ($customer->make_address)
                                                {{ $customer->make_address }}
                                            @else
                                                No Mac Address
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($customer->created_at)->format('d M, Y') }}
                                        </td>
                                       
                                        @if (Auth::user()->role === 'admin')
                                            <td>
                                                <a href="{{ route('cutomerUPdateTrialDetailFormVIew', $customer->id) }}"
                                                    class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="{{ route('deleteTrialCustomerDetails', $customer->id) }}"
                                                    class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                                <a href="{{ route('distributeSingleTrialForm', $customer->id) }}"
                                                    class="btn btn-warning" title="Distribute Trial"><i class="fa-solid fa-share"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('updateCustomerStatus', $customer->id) }}"
                                                    class="btn btn-success">Sale</a>
                                                <a href="{{ route('deleteCustomerDetails', $customer->id) }}"
                                                    class="btn btn-danger">Cancel</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                </div>
            </div>
        @endsection

