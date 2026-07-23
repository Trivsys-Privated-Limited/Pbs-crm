@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
<!--  ////////// New Code Add For Search /////// -->

            <div class="row mb-2 align-items-end">
    <!-- 1. Left Column: Dashboard Heading -->
    <div class="col-sm-4">
        <h1 class="m-0">Dashboard</h1>
    </div>
    
    <!-- 2. Middle Column: Completely Separate Search Bar (Naya) -->
    <div class="col-sm-4">
        <form action="" method="get" id="dashboardSearchForm">
            <label for="dashboardSearch">Search Reports & Numbers</label>
            <div class="input-group">
                <input type="text" class="form-control" name="search" id="dashboardSearch" 
                       placeholder="Search name, number, email..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('dashboard') }}" class="btn btn-danger"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    
    <!-- 3. Right Column: Existing Filter By Month (100% Unchanged, Pehle wala) -->
    <div class="col-sm-4">
        <form action="" method="get" id="filterbyMonthForm">
            <label for="exampleInputEmail1">Filter By Month</label>
            <input type="month" class="form-control" name="date"
                aria-label="Text input with 2 dropdown buttons" id="filterbyMonth" value="{{ request('date') }}">
        </form>
    </div>
</div>

            <!--  ////////// End Here New Code Add For Search /////// -->

            </div>
        </div>
       
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @if (Auth::user()->role === 'admin')
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3> ${{ $price }} </h3>

                                    <p>Total Sales</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3> {{ $userCount }} </h3>

                                    <p>User Registrations</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{ route('viewUserTable') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3> {{ $lead }} </h3>

                                    <p>Total Agents Lead Counts</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{ route('viewAgentLeadlTable') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3> {{ $sale }} </h3>

                                    <p>Total Agents Sales Counts</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{ route('viewAgentSaleTable') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3> {{ $trial }} </h3>

                                <p>Total Agents Trial Counts</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('viewAgentTrialTable') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3> {{ $help }} </h3>
                                <p>Total Help Request</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('viewHelpRequestTableDashboard') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
        </section>

        <!--  ////////// New Code Add For Search Results /////// -->
        @if(request()->has('search') && request('search') != '')
        <div class="container-fluid mt-4 mb-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-search mr-1"></i> Search Results for "{{ request('search') }}"
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Tabs Headers -->
                    <ul class="nav nav-tabs" id="searchTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="leads-tab" data-toggle="tab" href="#leads-result" role="tab">Leads ({{ $searchResultsLeads->count() }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales-result" role="tab">Sales ({{ $searchResultsSales->count() }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pending-sales-tab" data-toggle="tab" href="#pending-sales-result" role="tab">Pending Sales ({{ $searchResultsPendingSales->count() }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="trials-tab" data-toggle="tab" href="#trials-result" role="tab">Trials ({{ $searchResultsTrials->count() }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="numbers-tab" data-toggle="tab" href="#numbers-result" role="tab">Distributed Numbers ({{ $searchResultsNumbers->count() }})</a>
                        </li>
                    </ul>

                    <!-- Tabs Content Panels -->
                    <div class="tab-content mt-3">
                        <!-- LEADS TAB -->
                        <div class="tab-pane fade show active" id="leads-result" role="tabpanel">
                            @if($searchResultsLeads->isEmpty())
                                <div class="alert alert-light text-muted">No Lead records matched your search.</div>
                            @else
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Number</th>
                                            <th>Email</th>
                                            <th>Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($searchResultsLeads as $leadData)
                                            <tr>
                                                <td>{{ $leadData->customer_name }}</td>
                                                <td>{{ $leadData->customer_number }}</td>
                                                <td>{{ $leadData->customer_email }}</td>
                                                <td>{{ $leadData->user->name ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- SALES TAB -->
                        <div class="tab-pane fade" id="sales-result" role="tabpanel">
                            @if($searchResultsSales->isEmpty())
                                <div class="alert alert-light text-muted">No Sales records matched your search.</div>
                            @else
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Number</th>
                                            <th>Email</th>
                                            <th>Price</th>
                                            <th>Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($searchResultsSales as $saleData)
                                            <tr>
                                                <td>{{ $saleData->customer_name }}</td>
                                                <td>{{ $saleData->customer_number }}</td>
                                                <td>{{ $saleData->customer_email }}</td>
                                                <td>${{ $saleData->price }}</td>
                                                <td>{{ $saleData->user->name ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- PENDING SALES TAB -->
                        <div class="tab-pane fade" id="pending-sales-result" role="tabpanel">
                            @if($searchResultsPendingSales->isEmpty())
                                <div class="alert alert-light text-muted">No Pending Sales records matched your search.</div>
                            @else
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Number</th>
                                            <th>Email</th>
                                            <th>Price</th>
                                            <th>Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($searchResultsPendingSales as $pendingSaleData)
                                            <tr>
                                                <td>{{ $pendingSaleData->customer_name }}</td>
                                                <td>{{ $pendingSaleData->customer_number }}</td>
                                                <td>{{ $pendingSaleData->customer_email }}</td>
                                                <td>${{ $pendingSaleData->price }}</td>
                                                <td>{{ $pendingSaleData->user->name ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- TRIALS TAB -->
                        <div class="tab-pane fade" id="trials-result" role="tabpanel">
                            @if($searchResultsTrials->isEmpty())
                                <div class="alert alert-light text-muted">No Trial records matched your search.</div>
                            @else
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Number</th>
                                            <th>Email</th>
                                            <th>Agent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($searchResultsTrials as $trialData)
                                            <tr>
                                                <td>{{ $trialData->customer_name }}</td>
                                                <td>{{ $trialData->customer_number }}</td>
                                                <td>{{ $trialData->customer_email }}</td>
                                                <td>{{ $trialData->user->name ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- DISTRIBUTED NUMBERS TAB -->
                        <div class="tab-pane fade" id="numbers-result" role="tabpanel">
                            @if($searchResultsNumbers->isEmpty())
                                <div class="alert alert-light text-muted">No Distributed Numbers matched your search.</div>
                            @else
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Number</th>
                                            <th>Agent</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($searchResultsNumbers as $numberData)
                                            <tr>
                                                <td>{{ $numberData->customer_name }}</td>
                                                <td>{{ $numberData->customer_number }}</td>
                                                <td>{{ $numberData->user->name ?? 'N/A' }}</td>
                                                <td><span class="badge badge-warning">{{ $numberData->status }}</span></td>
                                                <td>{{ $numberData->remarks ?? 'No Remarks' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!--  ////////// End Here New Code Add For Search Results /////// -->

        @if ($curentSale->isEmpty())
        @else
            @if (Auth::user()->role === 'admin')
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-3 ">
                                <h1 class="m-0 d-inline">Today Agent Sale Report</h1>
                            </div>

                        </div>
                    </div>
                </div>
                <div class='container-fluid'>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>CUSTOMER REGISTRATION DATE</th>
                                            <th>CUSTOMER NAME</th>
                                            <th>CUSTOMER NUMBER</th>
                                            <th>CUSTOMER EMAIL</th>
                                            <th>REMARKS</th>
                                            <th>STATUS</th>
                                            <th>MAC ADDRESS</th>
                                            <th>AGENT NAME</th>
                                            <th>ACTION</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($curentSale as $index => $customer)
                                            @if ($customer->status === 'sale')
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if ($customer->regitr_date)
                                                            {{ \Carbon\Carbon::parse($customer->regitr_date)->format('d M, Y') }}
                                                        @else
                                                            No Registration Date
                                                        @endif
                                                    </td>
                                                    <td> {{ $customer->customer_name }} </td>
                                                    <td>{{ $customer->customer_number }}</td>
                                                    <td>{{ $customer->customer_email }}</td>
                                                    <td>{{ $customer->remarks }}</td>
                                                    <td>
                                                        @if ($customer->status === 'sale')
                                                            <span class="bg-success py-1 px-2 rounded">Sale</span>
                                                        @elseif($customer->status === 'trial')
                                                            <span class="bg-danger py-1 px-2 rounded">Trial</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($customer->make_address)
                                                            {{ $customer->make_address }}
                                                        @else
                                                            No Mac Address
                                                        @endif

                                                        {{ $customer->active_status }}
                                                        </span>
                                                    </td>
                                                    <td> {{ $customer['user']->name }} </td>
                                                    <td> <a href="{{ route('cutomerUPdateSaleDetailFormVIew', $customer->id) }}"
                                                            class="btn btn-primary"><i
                                                                class="fa-solid fa-pen-to-square"></i></a></td>

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
                </div>
            @endif
        @endif

        @if (Auth::user()->role === 'admin')
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-3 ">
                            <h1 class="m-0 d-inline">Agent Leave Requests</h1>
                        </div>

                    </div>
                </div>
            </div>
            <div class='container-fluid'>
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
                                                    <a href="{{ route('approveLeave', $leave->id) }}"
                                                        class="btn btn-success btn-sm">Approve</a>

                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $leave->id }}">
                                                        Reject
                                                    </button>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>

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
                                                            <input type="text" name="reason_to_reject"
                                                                class="form-control" required>
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
                    <div>
                    </div>
                </div>
            </div>
        @endif

        <script>
            let fileByMonth = document.querySelector('#filterbyMonth');
            let FilterMonthForm = document.querySelector('#filterbyMonthForm');
            fileByMonth.addEventListener('change', () => {
                FilterMonthForm.submit();
            });
        </script>

    @endsection
