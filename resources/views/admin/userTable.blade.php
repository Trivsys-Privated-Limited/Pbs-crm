@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-2">
                        <h1 class="m-0 d-inline">All Users</h1>
                    </div>
                    <div class="col-sm-4">
                        <h1 class="m-0 d-inline"><a href="{{ route('addUser') }}" class="btn btn-primary">Add New</a></h1>
                    </div>
                    <div class="col-sm-6">
                        <form action="{{ route('viewUserTable') }}" method="get" id="filterbyMonthForm">
                            <label for="exampleInputEmail1">Filter By Month Sale Count</label>
                            <input type="month" class="form-control" name="date"
                                aria-label="Text input with 2 dropdown buttons" id="filterbyMonth">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
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
                                    <th>USER NAME</th>
                                    <th>USER EMAIL</th>
                                    <th>USER PHONE</th>
                                    <th>ROLE</th>
                                    <th>SALES COUNT</th>
                                    <th>SALES TOTAL</th>
                                    <th>MONTH</th>
                                    <th>LOGIN STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    @if ($user->role == 'user' || $user->role == 'support')
                                        <tr>
                                            <td> {{ $index + 1 }} </td>
                                            <td> {{ $user->name }} </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                @php
                                                    $salesCount = $salesData[$user->id] ?? 0;
                                                @endphp
                                                {{ $salesCount }}
                                            </td>
                                            <td>
                                                @php
                                                    $totalSales_1 = $oldCustomer
                                                        ->where('a_name', $user->id)
                                                        ->sum('total');
                                                    $totalSales_2 = $newCustomer
                                                        ->where('agent', $user->id)
                                                        ->sum('total');
                                                @endphp
                                                ${{ $totalSales_1 + $totalSales_2 }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::now()->month($currentMonth)->format('F') }}
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
                                                <a href="{{ route('viewEditFormUser', $user->id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="{{ route('viewUserChangePassword', $user->id) }}"
                                                    class="btn btn-primary">
                                                    Change Password
                                                </a>
                                                <a onclick="return confirm('Are You Sure You Want To Delete This User')"
                                                    href="{{ route('deleteUser', $user->id) }}" class="btn btn-danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
