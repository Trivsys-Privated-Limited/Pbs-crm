@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 d-inline">All Agent Lead Report</h1>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DashBord</a></li>
                            <li class="breadcrumb-item active">All Agent Lead Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class='container-fluid'>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <form action="{{ route('distributeMultipleLeadForm') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success mb-3">Distribute Selected Leads</button>
                        <table id="example1" class="table table-bordered table-striped">
                            @if (session('success'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <thead>
                                <tr>
                                    <th>Select</th>
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
                                    <td>Date</td>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $index => $customer)
                                    <tr>
                                        <td><input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="lead-checkbox"></td>
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
                                                class="bg-warning  py-1 px-2 rounded block mt-5">{{ $customer->status }}</span>
                                        </td>

                                        <td>{{ $customer->user_name }}</td>
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
                                        <td>
                                            <a href="{{ route('cutomerUPdateDetailFormVIew', $customer->id) }}"
                                                class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="{{ route('deleteLeadCustomerDetails', $customer->id) }}"
                                                class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                            <a href="{{ route('distributeSingleLeadForm', $customer->id) }}"
                                                class="btn btn-warning" title="Distribute Lead"><i class="fa-solid fa-share"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </form>
                    </div>
                </div>
                <div>
                </div>
            </div>
        @endsection




