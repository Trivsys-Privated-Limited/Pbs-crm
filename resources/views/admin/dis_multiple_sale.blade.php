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
                            <h3 class="text-center">Distribute Multiple Sales To Another Agent</h3>
                        </div>
                        <form action="{{ route('saveMultipleSaleDistribution') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            
                            @foreach($selected_customers as $customer)
                                <input type="hidden" name="customer_ids[]" value="{{ $customer->id }}">
                            @endforeach

                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label>Selected Sales Preview:</label>
                                        <div class="p-2 border rounded bg-light" style="max-height: 150px; overflow-y: auto;">
                                            <ul class="list-unstyled mb-0">
                                                @foreach($selected_customers as $customer)
                                                    <li class="mb-1">
                                                        <i class="fa-solid fa-cart-shopping text-muted mr-1"></i> 
                                                        <strong>{{ $customer->customer_name }}</strong> ({{ $customer->customer_email }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <label for="agent">Select Agent</label>
                                        <select class="form-select" name="agent" aria-label="Default select example">
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
                            </div>
                            <div class="card-footer">
                                <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection