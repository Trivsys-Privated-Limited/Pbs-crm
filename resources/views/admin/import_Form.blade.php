@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-12 mt-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="text-center">Import Excel Data</h3>
                        </div>
                        <form action="{{ route('support.import.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @if (session('success'))
                                <div class="alert alert-secondary text-center" role="alert">{{ session('success') }}</div>
                            @endif
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Import Excel File</label>
                                        <input type="file" class="form-control" name="file" required>
                                    </div>
                                    <div class="col-12 mt-2">
                                         <label for="expiry_date">Expiry Date</label>
                                         <input type="date" class="form-control" name="expiry_date" required>
                                    </div>
                                    <!-- Naya Select Box: Kis ko assign karni hy file -->
                                    <div class="col-12 mt-2">
                                        <label>Select Support Agent</label>
                                        <select name="assigned_to" class="form-control" required>
                                            <option value="">-- Choose Agent --</option>
                                            @foreach($supportUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary">Save and Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection