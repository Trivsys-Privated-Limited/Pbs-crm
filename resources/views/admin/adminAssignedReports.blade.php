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
<!-- Add new for re-assign not-answering numbers 09-30-2026  -->
 <!-- Edit Support Status & Reassign Modal -->
<div class="modal fade" id="editSupportModal" tabindex="-1" role="dialog" aria-labelledby="editSupportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupportModalLabel">Update Status / Re-assign Support Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSupportForm" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Support Agent Dropdown (Re-assign) -->
                    <div class="form-group">
                        <label for="modal_assigned_to">Assign / Re-assign To Support Agent <span class="text-danger">*</span></label>
                        <select name="assigned_to" id="modal_assigned_to" class="form-control" required>
                            <option value="">Select Support Agent</option>
                            @if(isset($supportUsers))
                                @foreach($supportUsers as $sUser)
                                    <option value="{{ $sUser->id }}">{{ $sUser->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Expiry Date Field -->
                    <div class="form-group">
                        <label for="modal_expiry_date">Expiry Date <span class="text-danger">*</span></label>
                        <input type="date" name="expiry_date" id="modal_expiry_date" class="form-control" required>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="form-group">
                        <label for="modal_status">Status (Select empty/blank to Reset to Pending)</label>
                        <select name="status" id="modal_status" class="form-control">
                            <option value="">Reset Status (Pending/Fresh)</option>
                            <option value="Satisfied">Satisfied</option>
                            <option value="Non Satisfied">Non Satisfied</option>
                            <option value="Not Answering">Not Answering</option>
                            <option value="Call me Back">Call me Back</option>
                        </select>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group">
                        <label for="modal_remarks">Remarks</label>
                        <textarea name="remarks" id="modal_remarks" class="form-control" rows="3"></textarea>
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
<!-- End add new for re-assign not-answering numbers 09-30-2026  -->

<!-- add code for bulk re-assign not-answering 09-30-2026 -->
 <!-- Bulk Re-assign Not Answering Modal -->
<div class="modal fade" id="bulkNotAnsweringModal" tabindex="-1" role="dialog" aria-labelledby="bulkNotAnsweringModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="bulkNotAnsweringModalLabel">Bulk Re-assign (Not Answering)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('support.reassign.not_answering_limit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                    <label>Enter Quantity (How Many numbers you want to Re-assign?) <span class="text-danger">*</span></label>
                    <!-- NAYA CODE: max aur id add kiya -->
                    <input type="number" id="bulk_limit_count" name="limit_count" class="form-control" required min="1" max="{{ $notAnsweringCount }}" placeholder="Available: {{ $notAnsweringCount }}">
                    <!-- Error Message (Jo by default hide rahega) -->
                    <small id="limit_error_msg" class="text-danger font-weight-bold mt-1" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i> You Have "Not-Answering" Only {{ $notAnsweringCount }} numbers, You can't Enter More then this Quantity!
                    </small>
                </div> 

                    <div class="form-group">
                        <label>Assign to Support Agent <span class="text-danger">*</span></label>
                        <select name="assigned_to" class="form-control" required>
                            <option value="">Select Support Agent</option>
                            @if(isset($supportUsers))
                                @foreach($supportUsers as $sUser)
                                    <option value="{{ $sUser->id }}">{{ $sUser->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label>New Expiry Date <span class="text-danger">*</span></label>
                        <input type="date" name="new_expiry_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="bulk_submit_btn" class="btn btn-warning font-weight-bold">Re-assign Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- End code for bulk re-assign not-answering 09-30-2026 -->

                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Activity Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> Admin Live Activity Logs</h3>
                    <div class="card-tools">
                        <!-- Admin Live Activity Logs heading ke neeche aur form se pehle ye lagayein -->
                         <!-- Add For Re-assign not-answering numbers 09-30-2026 -->
                        <button type="button" class="btn btn-sm btn-warning mr-2 mb-2" data-toggle="modal" data-target="#bulkNotAnsweringModal" data-bs-toggle="modal" data-bs-target="#bulkNotAnsweringModal">
                            <i class="fas fa-exchange-alt"></i> Bulk Re-assign (Not Answering)
                        </button>
                         <!-- End Re-assign not-answering numbers 09-30-2026 -->
                    <!-- search Agent, Customer Name, Number 18/09/2026 start -->
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

                        <!-- Naya Universal Search Box (Agent, Customer Name, Number) -->
                              <input type="text" name="search_data" class="form-control form-control-sm mr-2" placeholder="Search Agent or Customer Info" value="{{ request('search_data') }}">
                              
                              <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                              
                              <!-- Reset button logic me request('search_data') add kar diya -->
                              @if(request('date') || request('status_filter') || request('search_data'))
                                  <a href="{{ route('adminAssignedReports') }}" class="btn btn-sm btn-secondary ml-1">Reset</a>
                              @endif
                    </form>
                    <!--  Agent, Customer Name, Number search code 18/09/2026 End -->
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

                                     <td>
    <!-- Button Click karne par Popup Khulega (Condition Hata Di Gayi Hai) -->
    <!-- add new for re-assign not-answering numbers 09-30-2026  -->
    <button type="button" 
    class="btn btn-sm btn-info text-white edit-support-btn" 
    data-toggle="modal" 
    data-target="#editSupportModal"
    data-bs-toggle="modal" 
    data-bs-target="#editSupportModal"
    data-id="{{ $row->id }}" 
    data-remarks="{{ $row->remarks }}" 
    data-status="{{ $row->status }}"
    data-assigned_to="{{ $row->assigned_to }}"
    data-expiry_date="{{ \Carbon\Carbon::parse($row->expiry_date)->format('Y-m-d') }}">
    <i class="fas fa-edit"></i> Edit / Re-assign
</button>
<!-- End add new for re-assign not-answering numbers 09-30-2026  -->

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

<!-- Add new for re-assign not-answering numbers 09-30-2026  -->
@push('scripts')
<script>
$(document).ready(function () {
    $('.edit-support-btn').on('click', function () {
        let id = $(this).data('id');
        let remarks = $(this).data('remarks');
        let status = $(this).data('status');
        let assigned_to = $(this).data('assigned_to');
        let expiry_date = $(this).data('expiry_date');

        let actionUrl = "{{ url('/store-support-number') }}/" + id;
        $('#editSupportForm').attr('action', actionUrl);

        $('#modal_remarks').val(remarks);
        $('#modal_status').val(status ?? '');
        $('#modal_assigned_to').val(assigned_to);
        $('#modal_expiry_date').val(expiry_date);

        $('#editSupportModal').modal('show');
    });

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
<!-- End re-assign not-answering numbers 09-30-2026  -->
 <!-- Add Validation bulk not-answering form 09-30-2026 -->
 <script>
$(document).ready(function () {
    // Bulk Re-assign limit validation
    $('#bulk_limit_count').on('input', function() {
        let maxCount = {{ $notAnsweringCount }};
        let enteredCount = parseInt($(this).val());

        if (enteredCount > maxCount) {
            // Agar limit se zyada likha toh error dikhao aur button disable kardo
            $('#limit_error_msg').slideDown();
            $('#bulk_submit_btn').prop('disabled', true);
            $(this).addClass('is-invalid'); // Input field ko red border dega
        } else {
            // Agar theek hai toh sab normal kardo
            $('#limit_error_msg').slideUp();
            $('#bulk_submit_btn').prop('disabled', false);
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
<!-- End Validation bulk not-answering form 09-30-2026 -->