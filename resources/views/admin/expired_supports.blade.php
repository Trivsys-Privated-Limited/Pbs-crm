@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
<div class="content-wrapper">
<div class="container-fluid mt-4">
    <div class="row ">
    <div class="col-12 mt-4">
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title">Expired Support Numbers (Needs Re-assignment)</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Multiple Selection Form Start -->
            <form action="{{ route('support.reassign.multiple') }}" method="POST">
                @csrf
                <!-- Bulk Assign Controls Top Par -->
                <div class="row mb-3 bg-light p-3 rounded border">
                    <div class="col-md-3">
                        <label>Reassign Selected To:</label>
                        <select name="assigned_to" class="form-control" required>
                            <option value="">-- Choose Agent --</option>
                            @foreach($supportUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>New Expiry Date:</label>
                        <input type="date" name="new_expiry_date" class="form-control" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-users"></i> Reassign Selected</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Number</th>
                                <th>Old Expiry Date</th>
                                <th>Action (Single Assign)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expiredSupports as $key => $data)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $data->id }}" style="width: 20px; height: 20px;">
                                </td>
                                <td>{{ $expiredSupports->firstItem() + $key }}</td>
                                <td>{{ $data->name ?? 'N/A' }}</td>
                                <td>{{ $data->number }}</td>
                                <td class="text-danger fw-bold">{{ $data->old_expiry_date }}</td>
                                <td>
                                    <!-- Agar sirf 1 record ko update karna ho to uska apna form idhar se post hoga (using HTML formaction) -->
                                    <div class="d-flex align-items-center">
                                        <select name="single_assign_to_{{ $data->id }}" class="form-select form-select-sm me-2" style="max-width:130px;" onchange="this.form.action='{{ route('support.reassign', $data->id) }}'; this.name='assigned_to';">
                                            <option value="">Agent</option>
                                            @foreach($supportUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="date" name="single_date_{{ $data->id }}" class="form-control form-control-sm me-2" style="max-width:130px;" onchange="this.name='new_expiry_date';">
                                        <button type="submit" class="btn btn-sm btn-info text-nowrap" formaction="{{ route('support.reassign', $data->id) }}">
                                            Re-assign One
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No expired support data found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
            <!-- Multiple Selection Form End -->

            <!-- <div class="mt-3">
                {{ $expiredSupports->links() }}
            </div> -->
          <!-- Fixed Pagination Wrapper -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $expiredSupports->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection