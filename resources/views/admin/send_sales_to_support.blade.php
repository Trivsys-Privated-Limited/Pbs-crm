@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Send Sales to Support</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Assign {{ $agent->name }}'s Sales to Support Team</h3>
                        </div>
                        
                        <div class="card-body text-center">
                            <h4>Total Active Sales for {{ $agent->name }}: <span class="badge badge-success" style="font-size: 1.2rem;">{{ $totalSales }}</span></h4>
                            <p class="text-muted mt-2">Note: Original sales data will remain safe. Duplicate numbers currently in Support or Expired Support will be automatically skipped.</p>
                        </div>

                        <form action="{{ route('support.sendSalesToSupport', $agent->id) }}" method="POST">
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
    <input type="number" class="form-control" name="limit" min="1" max="{{ $totalSales }}" placeholder="Enter count (e.g., 10)" required>
    <small class="text-muted">Total available sales: {{ $totalSales }}</small>
</div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary w-100" @if($totalSales == 0) disabled @endif>Copy Data to Support Table</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection