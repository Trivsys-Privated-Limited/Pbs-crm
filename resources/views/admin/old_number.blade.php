@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <h1 class="m-0 d-inline">
                            All {{ isset($region) ? strtoupper($region) : '' }} Old Numbers
                        </h1>
                    </div>
                    <div class="col-sm-4 text-center">
                        <h1 class="m-0 d-inline">
                            @if(isset($region))
                                <a href="{{ route('distributeOldNumbersFormByRegion', $region) }}" class="btn btn-primary">
                                    Distribute {{ strtoupper($region) }} Number
                                </a>
                            @else
                                <a href="{{ route('disOldCustomerNumberToAgent') }}" class="btn btn-primary">
                                    Distribute Number
                                </a>
                            @endif
                        </h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">
                                All {{ isset($region) ? strtoupper($region) : '' }} Numbers
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
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
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numbers</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($old_number as $index => $number)
                                    <tr>
                                        <td>{{ $old_number->firstItem() + $index }}</td>
                                        <td>{{ $number->number }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">No Old Numbers Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $old_number->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
