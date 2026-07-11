@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 d-inline">All Agent Sale Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <form action="" method="get" id="filterbyMonthForm">
                            <label for="exampleInputEmail1">Filter By Month</label>
                            <input type="month" class="form-control" name="date"
                                aria-label="Text input with 2 dropdown buttons" id="filterbyMonth">
                        </form>
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
                                    <th>MAC ADDRESS EXPIRY DATE</th>
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
                                                class="bg-success py-1 px-2 rounded block mt-5">{{ $customer->status }}</span>
                                        </td>

                                        <td> {{ $customer['user']->name }}</td>
                                        <td>
                                            @if ($customer->make_address)
                                                {{ $customer->make_address }}
                                            @else
                                                No Mac Address
                                            @endif
                                        </td>
                                        <td>
                                            @if ($customer->expiry_date)
                                                {{ \Carbon\Carbon::parse($customer->expiry_date)->format('d M, Y') }}
                                            @else
                                                No Expiry Date
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('viewRenewalpage', $customer->id) }}"
                                                class="btn btn-primary">View Renewal</a>
                                            <a href="{{ route('cutomerUPdateSaleDetailFormVIew', $customer->id) }}"
                                                class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="{{ route('deleteSaleCustomerDetails', $customer->id) }}"
                                                class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                            <a href="{{ route('distributeSingleSaleForm', $customer->id) }}"
                                                class="btn btn-warning" title="Distribute Sale"><i class="fa-solid fa-share"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
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
