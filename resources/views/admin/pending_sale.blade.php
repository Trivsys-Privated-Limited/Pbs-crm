@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 d-inline">All Agent Pending Sale Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <form action="" method="get" id="filterbyMonthForm">
                            <label for="exampleInputEmail1">Filter By Month</label>
                            <input type="month" class="form-control" name="date"
                                aria-label="Text input with 2 dropdown buttons" id="filterbyMonth">
                        </form>
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
                                    <th>CUSTOMER REGISTRATION DATE</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>CUSTOMER EMAIL</th>
                                    <th>CUSTOMER PHONE</th>
                                    <th>PRICE</th>
                                    <th>REMARKS</th>
                                    <th>STATUS</th>
                                    <th>AGENT NAME</th>
                                    <th>MAC ADDRESS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $index => $customer)
                                    <tr>
                                        <td> {{ $index + 1 }} </td>
                                        <td>
                                            @if ($customer->regitr_date)
                                                {{ \Carbon\Carbon::parse($customer->regitr_date)->format('d M, Y') }}
                                            @else
                                                No Registration Date
                                            @endif
                                        </td>
                                        <td> {{ $customer->customer_name }} </td>
                                        <td>{{ $customer->customer_email }}</td>
                                        <td>{{ $customer->customer_number }}</td>
                                        <td>${{ $customer->price }}</td>
                                        <td>{{ $customer->remarks }}</td>
                                        <td><span
                                                class="bg-warning py-1 px-2 rounded block mt-5">{{ $customer->status }}</span>
                                        </td>

                                        <td> {{ $customer['user']->name }}</td>
                                        <td>
                                            @if ($customer->make_address)
                                                {{ $customer->make_address }}
                                            @else
                                                No Mac Address
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('acceptPendingSale', $customer->id) }}"
                                                class="btn btn-primary">Accept</a>
                                            <a href="{{ route('deleteSaleCustomerDetails', $customer->id) }}"
                                                class="btn btn-danger">Reject</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

   <!-- <script>
        let fileByMonth = document.querySelector('#filterbyMonth');
        let FilterMonthForm = document.querySelector('#filterbyMonthForm');
        fileByMonth.addEventListener('change', () => {
            FilterMonthForm.submit();
        });
    </script> -->

        <script>
        let fileByMonth = document.querySelector('#filterbyMonth');
        let FilterMonthForm = document.querySelector('#filterbyMonthForm');
        if (fileByMonth && FilterMonthForm) {
            fileByMonth.addEventListener('change', () => {
                FilterMonthForm.submit();
            });
        }
    </script>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // DataTables instance lena
        var table = $('#example1').DataTable();

        // 1. Search ke barabar Agent Filter ka Dropdown design karna
        var agentFilterHtml = `
            <label style="margin-right: 15px; margin-bottom: 0; font-weight: normal; display: inline-flex; align-items: center;">
                <span style="margin-right: 6px; font-weight: 600;">Select Agent: </span>
                <select id="agentFilterSelect" class="form-control form-control-sm" style="display: inline-block; width: auto; min-width: 140px;">
                    <option value="">All Agents</option>
                </select>
            </label>
        `;

        // 2. Search input box ke side (left side) me add karna
        $('#example1_filter').prepend(agentFilterHtml);

        // 3. Table ke "AGENT NAME" column (Index 8) se unique agent names nikalna
        var agentNames = [];
        table.column(8).data().unique().sort().each(function(d) {
            var cleanName = $('<div>').html(d).text().trim();
            if (cleanName && cleanName !== '' && !agentNames.includes(cleanName)) {
                agentNames.push(cleanName);
            }
        });

        // Dropdown me options add karna
        agentNames.sort().forEach(function(name) {
            $('#agentFilterSelect').append('<option value="' + name + '">' + name + '</option>');
        });

        // 4. Page refresh ke baad check karna ke pehle se koi Agent select tha ya nahi (localStorage)
        var savedAgent = localStorage.getItem('pending_sale_selected_agent');

        if (savedAgent) {
            // Agar option mojood na ho (e.g. uski sari sale accept ho chuki hon), tab bhi option banayein
            if ($('#agentFilterSelect option[value="' + savedAgent + '"]').length === 0) {
                $('#agentFilterSelect').append('<option value="' + savedAgent + '">' + savedAgent + '</option>');
            }
            $('#agentFilterSelect').val(savedAgent);
            
            // Table ko us agent par filter karna (exact match)
            var regex = '^\\s*' + escapeRegExp(savedAgent) + '\\s*$';
            table.column(8).search(regex, true, false).draw();
        }

        // 5. Jab dropdown se Agent select kiya jaye
        $('#agentFilterSelect').on('change', function() {
            var selectedAgent = $(this).val();

            if (selectedAgent) {
                // LocalStorage me save karein taake Accept / Reject / Refresh par filter remove na ho
                localStorage.setItem('pending_sale_selected_agent', selectedAgent);
                var regex = '^\\s*' + escapeRegExp(selectedAgent) + '\\s*$';
                table.column(8).search(regex, true, false).draw();
            } else {
                // "All Agents" select karne par filter khatam ho jaye
                localStorage.removeItem('pending_sale_selected_agent');
                table.column(8).search('').draw();
            }
        });

        // Special characters ke liye helper function
        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }
    });
</script>
@endpush

