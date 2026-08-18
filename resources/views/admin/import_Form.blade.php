@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-12 mt-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="text-center">Import Excel Data</h3>
                        </div>
                        <form action="{{ route('support.import.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="importForm">
                            @csrf
                            @if (session('success'))
                                <div class="alert alert-success text-center" role="alert">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger text-center" role="alert">{{ session('error') }}</div>
                            @endif
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <label for="excelFile">Import Excel File</label>
                                        <input type="file" class="form-control" name="file" id="excelFile" required>
                                    </div>

                                    <div class="col-12 mt-2" id="totalDataCount" style="display:none;">
                                        <span class="badge bg-info text-dark px-3 py-2" style="font-size: 14px;">
                                            Total Rows in Sheet: <b id="rowCountSpan">0</b>
                                        </span>
                                    </div>

                                    <div class="col-12 mt-3" id="customLimitDiv" style="display:none;">
                                        <label for="importLimit">How many records do you want to assign?</label>
                                        <input type="number" name="import_limit" id="importLimit" class="form-control" placeholder="Enter custom number (e.g., 60)" min="1">
                                        <small class="text-danger">If left blank, all valid data in the sheet will be assigned.</small>
                                    </div>

                                    <div class="col-12 mt-3">
                                         <label for="expiry_date">Expiry Date</label>
                                         <input type="date" class="form-control" name="expiry_date" required>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <label>Select Support Agent</label>
                                        <select name="assigned_to" class="form-control" required>
                                            <option value="">-- Choose Agent --</option>
                                            @foreach($supportUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submitBtn">Save and Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#excelFile').on('change', function() {
            var formData = new FormData();
            var file = $(this)[0].files[0];
            
            // Agar file remove kar di jaye toh fields hide kar dein
            if (!file) {
                $('#totalDataCount').fadeOut();
                $('#customLimitDiv').fadeOut();
                $('#importLimit').val('');
                return;
            }

            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            // Button ko temporary disable karein taky user loading ke doran click na kare
            var btn = $('#submitBtn');
            var originalText = btn.text();
            btn.prop('disabled', true).text('Checking file...');

            // AJAX Request
            $.ajax({
                url: "{{ route('support.countExcelRows') }}", // Ensure yeh route web.php mein bana hua ho
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        // Data update karein aur fields show karein
                        $('#rowCountSpan').text(response.count);
                        $('#totalDataCount').fadeIn();
                        $('#customLimitDiv').fadeIn();
                        
                        // Max limit set karein (user sheet se zyada data enter na kar sake)
                        $('#importLimit').attr('max', response.count); 
                    } else {
                        alert('Error: Data could not be read from the file.');
                    }
                },
                error: function(xhr) {
                    console.log("Error:", xhr);
                    alert('Server error while reading the file.');
                },
                complete: function() {
                    // Button wapis enable karein
                    btn.prop('disabled', false).text(originalText);
                }
            });
        });
    });
    </script>
@endsection