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
                            <h3 class="text-center">
                                Distribute Customer {{ isset($region) ? strtoupper($region) : '' }} Old Numbers To Agents
                            </h3>
                        </div>
                        <form action="{{ isset($region) ? route('storeDistributedOldNumbersByRegion', $region) : route('storeOldCustomerNumber') }}" 
                              method="POST" 
                              enctype="multipart/form-data"
                              autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <label for="agent">Agent Name</label>
                                        <select class="form-select" name="agent" aria-label="Default select example">
                                            <option selected disabled>-- Select Agent Name --</option>
                                            @foreach ($agentName as $agent)
                                                <option value="{{ $agent->id }}"> {{ $agent->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('agent')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="date">Expiry Date</label>
                                        <input type="date" class="form-control" name="date" id="date" value="{{ old('date') }}">
                                        @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="number">Customer Number Count</label>
                                        <input type="number" class="form-control" name="number" id="number"
                                            placeholder="Enter Distribute Number Count" value="{{ old('number') }}">
                                        <span class="text-success d-block mt-1">
                                            All {{ isset($region) ? strtoupper($region) : '' }} Customer Numbers Count: <b>{{ $allClientNumbersCount }}</b>
                                        </span>
                                        @error('number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ isset($region) ? route('viewOldNumbersByRegion', $region) : route('viewOldCustomerNumber') }}" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection