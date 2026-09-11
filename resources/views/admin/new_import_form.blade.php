@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="text-center">Import New Outside Sheet</h3>
                        </div>

                        <form action="{{ route('support.newImport.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="newImportForm">
                            @csrf

                            @if (session('success'))
                                <div class="alert alert-success text-center m-3" role="alert">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger text-center m-3" role="alert">{{ session('error') }}</div>
                            @endif
                            
                            <div class="card-body">
                                <div class="row">
                                    <!-- File Upload -->
                                    <div class="col-12 mt-2">
                                        <label for="excelFileNew">Upload Excel Sheet (.xlsx, .csv)</label>
                                        <input type="file" class="form-control" name="file" id="excelFileNew" accept=".xlsx, .xls, .csv" required>
                                    </div>

                                    <!-- Total Rows Count -->
                                    <div class="col-12 mt-3" id="totalDataCountNew" style="display:none;">
                                        <span class="badge bg-info text-dark px-3 py-2" style="font-size: 14px;">
                                            Total Rows in Sheet: <b id="rowCountSpanNew">0</b>
                                        </span>
                                    </div>

                                    <!-- Dynamic Missing Columns Inputs (Yehan auto fields aayengi agar missing ho) -->
                                    <div class="col-12 mt-3" id="missingColumnsDiv" style="display:none;">
                                        <div class="alert alert-warning">
                                            <h5 class="text-danger font-weight-bold">Missing Columns Found!</h5>
                                            <p class="mb-2">Missing Some Important Column in Your Excel Sheet. Kindly Fill This Field:</p>
                                            <div id="dynamicInputsContainer"></div>
                                        </div>
                                    </div>
                                    <!-- Invalid Template Error Div -->
                                    <div class="col-12 mt-3" id="templateErrorDiv" style="display:none;">
                                        <div class="alert alert-danger">
                                            <h5 class="font-weight-bold"><i class="icon fas fa-ban"></i> Invalid Excel Template!</h5>
                                            <p class="mb-0">Please upload the correct excel file. The file must contain exactly these columns: <b>CUSTOMER REGISTRATION DATE, CUSTOMER NAME, CUSTOMER PHONE</b>.</p>
                                        </div>
                                    </div>
                                    <!-- Import Limit -->
                                    <div class="col-12 mt-3" id="customLimitDivNew" style="display:none;">
                                        <label for="importLimitNew">Import Limit (Optional)</label>
                                        <input type="number" name="import_limit" id="importLimitNew" class="form-control" placeholder="Enter custom limit (e.g., 50)" min="1">
                                        <small class="text-muted">If left blank, all valid data in the sheet will be imported.</small>
                                    </div>

                                    <!-- Expiry Date -->
                                    <div class="col-12 mt-3">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="date" class="form-control" name="expiry_date" required>
                                    </div>

                                    <!-- Assign To -->
                                    <div class="col-12 mt-3">
                                        <label>Select Support Agent</label>
                                        <select name="assigned_to" class="form-control" required>
                                            <option value="" disabled selected>-- Choose Support Member --</option>
                                            @foreach($supportUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submitBtnNew">Import Sheet Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery & AJAX Script for Checking Headers and Rows -->
   <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#excelFileNew').on('change', function() {
            var fileInput = this;
            if (fileInput.files.length === 0) {
                $('#totalDataCountNew').fadeOut();
                $('#customLimitDivNew').fadeOut();
                $('#missingColumnsDiv').fadeOut();
                return;
            }

            var formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            var btn = $('#submitBtnNew');
            var originalText = btn.text();
            btn.prop('disabled', true).text('Checking file...');

            $.ajax({
                url: "{{ route('support.checkNewHeaders') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        // Show Row Count
                        $('#rowCountSpanNew').text(response.count);
                        $('#totalDataCountNew').fadeIn();
                        $('#customLimitDivNew').fadeIn();
                        $('#importLimitNew').attr('max', response.count);

                        // Check Headers for missing columns
                        let headers = response.headers.map(h => typeof h === 'string' ? h.toLowerCase() : '');
                        let headerStr = headers.join(',');
                        
                        let missingHtml = '';
                        let hasMissing = false;

                        // Check if 'agent' column exists
                        if(!headerStr.includes('agent')) {
                            hasMissing = true;
                            missingHtml += '<div class="form-group mt-2">' +
                                '<label>Agent Name is missing in sheet. Enter Default Agent Name:</label>' +
                                '<input type="text" name="default_agent_name" class="form-control" required placeholder="E.g: Muhammad Ali">' +
                                '</div>';
                        }

                        // Check if 'status' column exists
                        if(!headerStr.includes('status')) {
                            hasMissing = true;
                            missingHtml += '<div class="form-group mt-2">' +
                                '<label>Status column is missing. Enter Default Status:</label>' +
                                '<input type="text" name="default_status" value="sale" class="form-control" required>' +
                                '</div>';
                        }

                        if(hasMissing) {
                            $('#dynamicInputsContainer').html(missingHtml);
                            $('#missingColumnsDiv').fadeIn();
                        } else {
                            $('#dynamicInputsContainer').html('');
                            $('#missingColumnsDiv').fadeOut();
                        }

                    } else {
                        alert('Error: Data could not be read from the file.');
                    }
                },
                error: function(xhr) {
                    console.log("Error:", xhr);
                    alert('Server error while reading the file.');
                },
                complete: function() {
                    btn.prop('disabled', false).text(originalText);
                }
            });
        });
    });
    </script>
-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#excelFileNew').on('change', function() {
        var fileInput = this;
        var btn = $('#submitBtnNew');
        
        if (fileInput.files.length === 0) {
            $('#totalDataCountNew').fadeOut();
            $('#customLimitDivNew').fadeOut();
            $('#missingColumnsDiv').fadeOut();
            $('#templateErrorDiv').fadeOut();
            btn.prop('disabled', false);
            return;
        }

        var formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        var originalText = btn.text();
        btn.prop('disabled', true).text('Checking Template...');

        $.ajax({
            url: "{{ route('support.checkNewHeaders') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    if(response.isValidTemplate) {
                        // Agar Template theek hai
                        $('#templateErrorDiv').fadeOut();
                        btn.prop('disabled', false).text(originalText);

                        // Row Count Show Karein
                        $('#rowCountSpanNew').text(response.count);
                        $('#totalDataCountNew').fadeIn();
                        $('#customLimitDivNew').fadeIn();
                        $('#importLimitNew').attr('max', response.count);

                        // Missing columns generate karein (Kyunke is template mein Agent aur Status nahi hain)
                        let missingHtml = '';
                        
                        missingHtml += '<div class="form-group mt-2">' +
                            '<label>Agent Name is missing in sheet. Enter Default Agent Name:</label>' +
                            '<input type="text" name="default_agent_name" class="form-control" required placeholder="E.g: Muhammad Ali">' +
                            '</div>';
                            
                        missingHtml += '<div class="form-group mt-2">' +
                            '<label>Status column is missing. Enter Default Status:</label>' +
                            '<input type="text" name="default_status" value="sale" class="form-control" readonly>' +
                            '</div>';

                        $('#dynamicInputsContainer').html(missingHtml);
                        $('#missingColumnsDiv').fadeIn();
                    } else {
                        // Agar Template ghalat hai
                        $('#templateErrorDiv').fadeIn();
                        $('#totalDataCountNew').fadeOut();
                        $('#customLimitDivNew').fadeOut();
                        $('#missingColumnsDiv').fadeOut();
                        btn.prop('disabled', true).text('Import Sheet Data'); // Button disabled rahega
                    }
                } else {
                    alert('Error: Data could not be read from the file.');
                    btn.prop('disabled', false).text(originalText);
                }
            },
            error: function(xhr) {
                console.log("Error:", xhr);
                alert('Server error while reading the file.');
                btn.prop('disabled', false).text(originalText);
            }
        });
    });
});
</script>
@endsection