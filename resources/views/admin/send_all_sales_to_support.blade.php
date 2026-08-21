@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Send All Global Sales to Support</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Assign All Agents' Fresh Sales to Support Team</h3>
                        </div>
                        
                        <div class="card-body text-center">
                            <h4>Total Available Global Sales: <span class="badge badge-success" style="font-size: 1.2rem;">{{ $totalSales }}</span></h4>
                            <p class="text-muted mt-2">Note: Original sales data will remain safe. Duplicate numbers currently in Support or Expired Support will be automatically skipped.</p>
                        </div>

                        <form action="{{ route('support.sendAllSalesToSupport') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="assigned_to">Assign To (Support Member)</label>
                                    <select class="form-control" name="assigned_to" required>
                                        <option value="" disabled selected>Select Support Member</option>
                                        @foreach($supportUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="expiry_date">Support Expiry Date</label>
                                    <input type="date" class="form-control" name="expiry_date" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="limit">Number of Sales to Send (Limit)</label>
                                    <input type="number" class="form-control" name="limit" min="1" max="{{ $totalSales }}" placeholder="Enter count (e.g., 50)" required>
                                    <small class="text-muted">You can copy up to: {{ $totalSales }} sales.</small>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning w-100 font-weight-bold" @if($totalSales == 0) disabled @endif>
                                    <i class="fa-solid fa-copy"></i> Copy Data to Support Table
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection