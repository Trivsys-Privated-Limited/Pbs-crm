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

            <!-- 1. BULK REASSIGN FORM (Hidden Shell) -->
            <!-- Yeh form multiple selection ko handle karega -->
            <form id="bulkReassignForm" action="{{ route('support.reassign.multiple') }}" method="POST">
                @csrf
            </form>

            <!-- Bulk Assign Controls Top Par -->
          <!--  <div class="row mb-3 bg-light p-3 rounded border">
                <div class="col-md-3">
                    <label>Reassign Selected To:</label>
                    <!-- form="bulkReassignForm" isko upar wale form ke sath link kar dega -->
                 <!--   <select name="assigned_to" form="bulkReassignForm" class="form-control" required>
                        <option value="">-- Choose Agent --</option>
                        @foreach($supportUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>New Expiry Date:</label>
                    <input type="date" name="new_expiry_date" form="bulkReassignForm" class="form-control" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" form="bulkReassignForm" class="btn btn-primary w-100">
                        <i class="fas fa-users"></i> Reassign Selected
                    </button>
                </div>
            </div> -->
                        <!-- Bulk Reassign Options (Option 1: Limit Count & Option 2: Checkbox Selection) -->
            <div class="row mb-3">
                <!-- Option 1: Re-assign by Limit/Count (e.g. 100, 200 numbers) -->
                <div class="col-md-6 mb-2">
                    <div class="bg-light p-3 rounded border shadow-sm">
                        <h6 class="font-weight-bold text-success mb-3"><i class="fas fa-list-ol mr-1"></i> Re-assign by Limit / Quantity</h6>
                        <form action="{{ route('support.reassign.limit') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-4 mb-2">
                                    <label class="small font-weight-bold">Quantity / Limit:</label>
                                    <input type="number" name="limit_count" class="form-control form-control-sm" min="1" placeholder="e.g. 100" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="small font-weight-bold">Select Support Agent:</label>
                                    <select name="assigned_to" class="form-control form-control-sm" required>
                                        <option value="">-- Agent --</option>
                                        @foreach($supportUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="small font-weight-bold">New Expiry Date:</label>
                                    <input type="date" name="new_expiry_date" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success w-100 mt-1">
                                <i class="fas fa-check-circle"></i> Re-assign By Limit
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Option 2: Re-assign Selected Checkboxes -->
                <div class="col-md-6 mb-2">
                    <div class="bg-light p-3 rounded border shadow-sm">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-tasks mr-1"></i> Re-assign Selected (Checkboxes)</h6>
                        <div class="form-row">
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">Select Support Agent:</label>
                                <select name="assigned_to" form="bulkReassignForm" class="form-control form-control-sm" required>
                                    <option value="">-- Agent --</option>
                                    @foreach($supportUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold">New Expiry Date:</label>
                                <input type="date" name="new_expiry_date" form="bulkReassignForm" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <button type="submit" form="bulkReassignForm" class="btn btn-sm btn-primary w-100 mt-1">
                            <i class="fas fa-users"></i> Re-assign Selected Numbers
                        </button>
                    </div>
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
                                <!-- Checkbox bhi bulkReassignForm ke sath linked hai -->
                                <input type="checkbox" name="selected_ids[]" value="{{ $data->id }}" form="bulkReassignForm" style="width: 20px; height: 20px;">
                            </td>
                            <td>{{ $expiredSupports->firstItem() + $key }}</td>
                            <td>{{ $data->name ?? 'N/A' }}</td>
                            <td>{{ $data->number }}</td>
                            <td class="text-danger fw-bold">{{ $data->old_expiry_date }}</td>
                            <td>
                                <!-- 2. SINGLE REASSIGN FORM (Hidden Shell for this specific row) -->
                                <!-- Har row ka apna ek alag form ID hoga -->
                                <form id="singleReassignForm_{{ $data->id }}" action="{{ route('support.reassign', $data->id) }}" method="POST">
                                    @csrf
                                </form>

                                <!-- Single Assign Controls -->
                                <div class="d-flex align-items-center">
                                    <select name="assigned_to" form="singleReassignForm_{{ $data->id }}" class="form-select form-select-sm me-2" style="max-width:130px;" required>
                                        <option value="">Agent</option>
                                        @foreach($supportUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                    <input type="date" name="new_expiry_date" form="singleReassignForm_{{ $data->id }}" class="form-control form-control-sm me-2" style="max-width:130px;" required>
                                    
                                    <button type="submit" form="singleReassignForm_{{ $data->id }}" class="btn btn-sm btn-info text-nowrap">
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