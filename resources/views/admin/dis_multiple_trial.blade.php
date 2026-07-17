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
                            <h3 class="text-center">Distribute Multiple Trials To Another Agent</h3>
                        </div>
                        <form action="{{ route('saveMultipleTrialDistribution') }}" method="POST" autocomplete="off">
                            @csrf
                            
                            <!-- Hidden inputs for selected customers -->
                            @foreach($selected_customers as $customer)
                                <input type="hidden" name="customer_ids[]" value="{{ $customer->id }}">
                            @endforeach

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <label for="agent">Select Agent</label>
                                        <select class="form-select" name="agent">
                                            <option selected disabled>-- Select Agent Name --</option>
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}"> {{ $agent->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('agent')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <p class="text-muted">You are about to distribute <strong>{{ count($selected_customers) }}</strong> selected trial(s).</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Save Distribution</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection