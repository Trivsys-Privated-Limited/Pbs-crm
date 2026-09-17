@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Admin Assigned Track Report</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <!-- Summary Widgets Row -->
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalAssigned }}</h3>
                            <p>Total Data Assigned by Admin</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Breakdown Tables -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-users mr-1"></i> Data Assigned to Support Team</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Support Team Member</th>
                                        <th class="text-right">Total Assigned</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bySupportTeam as $team)
                                        <tr>
                                            <td>
                                                <span class="badge badge-primary" style="font-size: 14px;">
                                                    {{ $team->support_person_name ?? 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td class="text-right"><strong>{{ $team->total }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center">No Data Found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Work Progress (Status)</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Lead Status</th>
                                        <th class="text-right">Total Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($byStatus as $status)
                                        <tr>
                                            <td>
                                                @if($status->status == 'Satisfied')
                                                    <span class="badge badge-success">Satisfied</span>
                                                @elseif($status->status == 'Non Satisfied')
                                                    <span class="badge badge-danger">Non Satisfied</span>
                                                @elseif(empty($status->status))
                                                    <span class="badge badge-warning">Pending / Un-touched</span>
                                                @else
                                                    <span class="badge badge-info">{{ $status->status }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right"><strong>{{ $status->total }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center">No Data Found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
<!-- Status Update Model pop code -->
<!-- Edit Support Status Modal -->
<div class="modal fade" id="editSupportModal" tabindex="-1" role="dialog" aria-labelledby="editSupportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupportModalLabel">Update Support Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSupportForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_status">Status <span class="text-danger">*</span></label>
                        <select name="status" id="modal_status" class="form-control" required>
                            <option value="">Select Status</option>
                            <option value="Satisfied">Satisfied</option>
                            <option value="Non Satisfied">Non Satisfied</option>
                            <option value="Not Answering">Not Answering</option>
                            <option value="Call me Back">Call me Back</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modal_remarks">Remarks <span class="text-danger">*</span></label>
                        <textarea name="remarks" id="modal_remarks" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Activity Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> Admin Live Activity Logs</h3>
                    <div class="card-tools">
                     <!--   <form method="GET" action="{{ route('adminAssignedReports') }}" class="form-inline">
                            <input type="date" name="date" class="form-control form-control-sm mr-2" value="{{ request('date') }}"> -->
    <!-- Naya Status Filter Dropdown -->
 <!--   <select name="status_filter" class="form-control form-control-sm mr-2">
        <option value="">All Status</option>
        <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="Satisfied" {{ request('status_filter') == 'Satisfied' ? 'selected' : '' }}>Satisfied</option>
        <option value="Non Satisfied" {{ request('status_filter') == 'Non Satisfied' ? 'selected' : '' }}>Non Satisfied</option>
        <option value="Not Answering" {{ request('status_filter') == 'Not Answering' ? 'selected' : '' }}>Not Answering</option>
        <option value="Call me Back" {{ request('status_filter') == 'Call me Back' ? 'selected' : '' }}>Call me Back</option>
    </select>
    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    @if(request('date') || request('status_filter'))
        <a href="{{ route('adminAssignedReports') }}" class="btn btn-sm btn-secondary ml-1">Reset</a>
    @endif -->
    <!-- Old Button Code before 17/09/2026 -->
                          <!--  <button type="submit" class="btn btn-sm btn-primary">Filter Date</button>
                            @if(request('date'))
                                <a href="{{ route('adminAssignedReports') }}" class="btn btn-sm btn-secondary ml-1">Reset</a>
                            @endif -->
                            <!-- End Old Button Code -->
                     <!--   </form> -->
                    <!-- search agent-name 17/09/2026 start -->
                     <form method="GET" action="{{ route('adminAssignedReports') }}" class="form-inline">
                        <input type="date" name="date" class="form-control form-control-sm mr-2" value="{{ request('date') }}">
                        
                        <!-- Naya Status Filter Dropdown -->
                        <select name="status_filter" class="form-control form-control-sm mr-2">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Satisfied" {{ request('status_filter') == 'Satisfied' ? 'selected' : '' }}>Satisfied</option>
                            <option value="Non Satisfied" {{ request('status_filter') == 'Non Satisfied' ? 'selected' : '' }}>Non Satisfied</option>
                            <option value="Not Answering" {{ request('status_filter') == 'Not Answering' ? 'selected' : '' }}>Not Answering</option>
                            <option value="Call me Back" {{ request('status_filter') == 'Call me Back' ? 'selected' : '' }}>Call me Back</option>
                        </select>

                        <!-- Naya Agent Search Box Yahan Add Hua -->
                        <input type="text" name="agent_name" class="form-control form-control-sm mr-2" placeholder="Search Agent Name" value="{{ request('agent_name') }}">
                        
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    
                        <!-- Reset button logic me request('agent_name') bhi add kar diya -->
                        @if(request('date') || request('status_filter') || request('agent_name'))
                            <a href="{{ route('adminAssignedReports') }}" class="btn btn-sm btn-secondary ml-1">Reset</a>
                        @endif
                    </form>
                    <!--  agent-name search code 17/09/2026 End -->
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Assigned By</th>
                                <th>Assigned To (Support)</th>
                                <th>Original Agent</th>
                                <th>Customer Info</th>
                                <th>Assigned Date</th>
                                <th>Expiry Date</th>
                                <th>Remarks</th> <!-- Naya Column Yahan Add Hua -->
                                <th>Work Status</th>
                                <th>Action</th> <!-- Naya Column -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($coordinatorData as $key => $row)
                                <tr>
                                    <td>{{ $coordinatorData->firstItem() + $key }}</td>
                                    <td><small class="text-muted">{{ $row->assigned_by_name ?? 'Admin' }}</small></td>
                                    <td>
                                        <span class="badge badge-primary px-2 py-1">
                                            <i class="fas fa-user-check mr-1"></i> {{ $row->support_person_name ?? 'Not Assigned' }}
                                        </span>
                                    </td>
                                    <td><span class="badge badge-secondary">{{ $row->agent_name }}</span></td>
                                    <td>
                                        <strong>{{ $row->name }}</strong><br>
                                        <small>{{ $row->number }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($row->assigned_date)->format('d-M-Y') }}</td>
                                    <td>
                                        <span class="text-danger font-weight-bold">
                                            {{ \Carbon\Carbon::parse($row->expiry_date)->format('d-M-Y') }}
                                        </span>
                                    </td>

                                    <!-- Naya Remarks Column Yahan Add Hua -->
                                    <td>
                                        {{ $row->remarks ?? '-' }}
                                    </td>
                                    

                                    <td>
                                        @if($row->status == 'Satisfied')
                                            <span class="badge badge-success">Satisfied</span>
                                        @elseif($row->status == 'Non Satisfied')
                                            <span class="badge badge-danger">Non Satisfied</span>
                                        @else
                                            <span class="badge badge-warning">{{ $row->status ?? 'Pending' }}</span>
                                        @endif
                                    </td>
                                    <!-- Naya Action Column Logic -->
          <!--  <td>
                @if(empty($row->status))
                    <a href="{{ route('editSupportNumber', $row->id) }}" class="btn btn-sm btn-info text-white">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @else
                    <span class="text-muted"><i class="fas fa-check-circle"></i> Updated</span>
                @endif
            </td> -->
            <!-- Table Action Column -->
<!-- <td>
    @if(empty($row->status))
        // Button Click karne par Popup Khulega 
       <button type="button" 
        class="btn btn-sm btn-info text-white edit-support-btn" 
        data-toggle="modal" 
        data-target="#editSupportModal"
        data-bs-toggle="modal" 
        data-bs-target="#editSupportModal"
        data-id="{{ $row->id }}" 
        data-remarks="{{ $row->remarks }}" 
        data-status="{{ $row->status }}">
    <i class="fas fa-edit"></i> Edit
</button>

    @else
        <span class="text-muted"><i class="fas fa-check-circle"></i> Updated</span>
    @endif
</td> -->
<td>
    <!-- Button Click karne par Popup Khulega (Condition Hata Di Gayi Hai) -->
    <button type="button" 
        class="btn btn-sm btn-info text-white edit-support-btn" 
        data-toggle="modal" 
        data-target="#editSupportModal"
        data-bs-toggle="modal" 
        data-bs-target="#editSupportModal"
        data-id="{{ $row->id }}" 
        data-remarks="{{ $row->remarks }}" 
        data-status="{{ $row->status }}">
        <i class="fas fa-edit"></i> Edit
    </button>
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No Activity Record Found by Admin</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $coordinatorData->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
@push('scripts') <!-- Ya simple <script> tag agar push layout use nahi kar rahe -->
<script>
$(document).ready(function () {
    // Edit Button Click Handling
    $('.edit-support-btn').on('click', function () {
        let id = $(this).data('id');
        let remarks = $(this).data('remarks');
        let status = $(this).data('status');

        // Dynamically Action URL set karein
        let actionUrl = "{{ url('/store-support-number') }}/" + id; // Ya jo bhi aapka route path hai
        $('#editSupportForm').attr('action', actionUrl);

        // Fields populate karein
        $('#modal_remarks').val(remarks);
        $('#modal_status').val(status);

        // Modal Open karein
        $('#editSupportModal').modal('show');
    });

    // Form Submit handling via AJAX
    $('#editSupportForm').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        $('#saveBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    $('#editSupportModal').modal('hide');
                    // Save hone ke baad wahi page reload hoga bina URL change kiye
                    window.location.reload(); 
                }
            },
            error: function (xhr) {
                alert('Something went wrong. Please check fields again.');
                $('#saveBtn').prop('disabled', false).text('Save Changes');
            }
        });
    });
});
</script>
@endpush