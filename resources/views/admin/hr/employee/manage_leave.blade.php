@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-10">
                        <h1 class="m-0 d-inline">All Employee Leave Recode</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>AGENT NAME</th>
                                    <th>START DATE</th>
                                    <th>END DATE</th>
                                    <th>REASON</th>
                                    <th>STATUS</th>
                                    <th>REJECT REASON</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveRequests as $index => $leave)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $leave->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                                        <td>{{ $leave->reason }}</td>
                                        <td>
                                            @if ($leave->status === 'Pending')
                                                <span class="bg-warning px-2 py-1 rounded text-white">Pending</span>
                                            @elseif ($leave->status === 'Approved')
                                                <span class="bg-success px-2 py-1 rounded text-white">Approved</span>
                                            @else
                                                <span class="bg-danger px-2 py-1 rounded text-white">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $leave->reason_to_reject ?? '-' }}</td>
                                        <td>
                                            @if ($leave->status === 'Pending')
                                                {{-- Approve Button --}}
                                                <a href="{{ route('approveLeave', $leave->id) }}"
                                                    class="btn btn-success btn-sm">Approve</a>

                                                {{-- Reject Button (Opens Modal) --}}
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $leave->id }}">
                                                    Reject
                                                </button>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- Reject Modal --}}
                                    <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Leave Request</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('rejectLeave', $leave->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <label class="form-label">Reason for Reject:</label>
                                                        <input type="text" name="reason_to_reject" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection