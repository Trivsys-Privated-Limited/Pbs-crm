@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h1 class="m-0 d-inline">All Customer Numbers Report</h1>
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
                                    <th>AGENT NAME</th>
                                    <th>TOTAL DISTRIBUTE NUMBERS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($allCustomerNumber as $index => $data)
                                    <tr>
                                        <td> {{ $index + 1 }} </td>

                                        <td>{{ $data['user']->name }}</td>
                                        <td>{{ $data->total }}</td>
                                        <td>
                                            <a href="{{ route('viewAgentDistributeNumbersDetail', $data['user']->id) }}"
                                                class="btn btn-primary">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('distributeNumberForm',$data['user']->id)  }}" class="btn btn-primary">Distribute Number</a>
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
        @endsection
