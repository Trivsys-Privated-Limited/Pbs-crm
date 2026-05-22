@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <h1 class="m-0 d-inline">All Dashboard User</h1>
                    </div>
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline"><a href="{{ route('viewAddForm') }}" class="btn btn-primary">Add New</a></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DashBord</a></li>
                            <li class="breadcrumb-item active">All Dashboard User</li>
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
                                    <th>ADMIN NAME</th>
                                    <th>ADMIN EMAIL</th>
                                    <th>ROLE</th>
                                    <th>LOGIN STATUS</th>
                                    <th>ACTIOLN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    @if ($user->role == 'admin' || $user->role == 'sub_admin')
                                        <tr>
                                            <td> {{ $index + 1 }} </td>
                                            <td> {{ $user->name }} </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->role == 'admin')
                                                    <span class="bg-success py-1 px-2 rounded">Main Admin</span>
                                                @else
                                                    <span class="bg-warning py-1 px-2 rounded">Admin</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->ip_address === '0')
                                                    <span
                                                        class="bg-danger px-1 py-2 d-inline-block text-center rounded mt-1">Deactivate</span>
                                                @else
                                                    <span
                                                        class="bg-success px-1 py-2 d-inline-block text-center rounded mt-1">Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('viewEditFormAdmin', $user->id) }}"
                                                    class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="{{ route('deleteAdmin', $user->id) }}" class="btn btn-danger"><i
                                                        class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                </div>
            </div>
        @endsection
