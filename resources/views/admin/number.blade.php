@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline">All Numbers</h1>
                    </div>
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('viewAddNumbersForm') }}" class="btn btn-primary">Add
                                New</a></h1>
                    </div>
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('viewCustomerNumberForm') }}"
                                class="btn btn-primary">Distribute Number</a></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DashBord</a></li>
                            <li class="breadcrumb-item active">All Numbers</li>
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
                            @if (session('error'))
                                <div class="alert alert-danger text-center" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numbers</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($numbers as $index => $number)
                                    <tr>
                                        <td> {{ $index + 1 }} </td>
                                        <td> {{ $number->number }} </td>
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
