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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Activity Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> Admin Live Activity Logs</h3>
                    <div class="card-tools">
                        <form method="GET" action="{{ route('adminAssignedReports') }}" class="form-inline">
                            <input type="date" name="date" class="form-control form-control-sm mr-2" value="{{ request('date') }}">
                            <button type="submit" class="btn btn-sm btn-primary">Filter Date</button>
                            @if(request('date'))
                                <a href="{{ route('adminAssignedReports') }}" class="btn btn-sm btn-secondary ml-1">Reset</a>
                            @endif
                        </form>
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
                                <th>Work Status</th>
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
                                    <td>
                                        @if($row->status == 'Satisfied')
                                            <span class="badge badge-success">Satisfied</span>
                                        @elseif($row->status == 'Non Satisfied')
                                            <span class="badge badge-danger">Non Satisfied</span>
                                        @else
                                            <span class="badge badge-warning">{{ $row->status ?? 'Pending' }}</span>
                                        @endif
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