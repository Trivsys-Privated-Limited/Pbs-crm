@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-3">
                        <h1 class="m-0 d-inline">All Agent Sale Report</h1>
                    </div>
                    <!-- Just Hide this link From Sales Coordinator -->
                    @if(Auth::user()->role !== 'sales coordinator')
                    <div class="col-sm-3">
                        <a href="{{ route('viewAddNewAgentSaleForm') }}" class="btn btn-primary">Add New</a>
                    </div>
                    @endif
                    <!-- End -->

                  <!--  <div class="col-sm-6">
                        <form action="{{ route('filterSaleByDate') }}" method="get" id="filterbyDateForm">
                            <div class="row">
                                <div class="col-6">
                                    <label for="exampleInputEmail1">From</label>
                                    <input type="date" class="form-control" name="from"
                                        aria-label="Text input with 2 dropdown buttons" id="from">
                                </div>
                                <div class="col-6">
                                    <label for="exampleInputEmail1">To</label>
                                    <input type="date" class="form-control" name="to"
                                        aria-label="Text input with 2 dropdown buttons" id="to">
                                </div>
                            </div>
                        </form>
                    </div> -->

<!-- add new testing for support button -->
 <div class="col-sm-6">
    <form action="{{ route('filterSaleByDate') }}" method="get" id="filterbyDateForm">
        <div class="row">
            <div class="col-4">
                <label for="from">From</label>
                <input type="date" class="form-control" name="from" id="from">
            </div>
            <div class="col-4">
                <label for="to">To</label>
                <input type="date" class="form-control" name="to" id="to">
            </div>
            <!-- Yahan Naya Button Add Kiya Hai -->
            <div class="col-4 mt-4">
                <a href="{{ route('support.sendAllSalesForm') }}" class="btn btn-warning w-100 font-weight-bold" title="Copy All Agent Sales to Support">
                    <i class="fa-solid fa-share-from-square"></i> Send All to Support
                </a>
            </div>
        </div>
    </form>
</div>
<!-- end here --> 
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
                                    <th>AGENT NAME</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $index => $customer)
                                    @if ($customer->user_id)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                {{ $customer->user_name }}
                                                              <span style="display:none;">{{ $customer->search_data }}</span>
                                           </td>

                                            <td>
                                                <a href="{{ route('viewSaleTable', $customer->user_id) }}"
                                                    class="btn btn-primary">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <!-- Copy to Support Button -->
                                                <a href="{{ route('support.sendSalesForm', $customer->user_id) }}" class="btn btn-info">
                                                    <i class="fa-solid fa-share-from-square"></i> Send to Support
                                                </a>
                                                <!-- Just Hide this link From Sales Coordinator -->
                                                @if(Auth::user()->role !== 'sales coordinator')
                                                <a href="{{ route('viewAgentDistributeSale', $customer->user_id) }}"
                                                    class="btn btn-primary">
                                                    Distribute Sale
                                                </a>
                                                @endif
                                                <!-- End -->
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
            <script>
                let filterbyDate = document.querySelector('#filterbyDate');
                let from = document.querySelector('#from');
                let to = document.querySelector('#to');
                if (from && to) {
                    function trySubmit() {
                        if (from.value && to.value) {
                            filterbyDateForm.submit();
                        }
                    }

                    from.addEventListener('change', trySubmit);
                    to.addEventListener('change', trySubmit);
                }
            </script>
        @endsection
