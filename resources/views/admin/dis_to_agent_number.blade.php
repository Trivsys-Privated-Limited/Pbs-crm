@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid ">
            <div class="row ">
                <div class="col-12   mt-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="text-center">Distribute Customer Numbers To Agents</h3>
                        </div>
                        <form action="{{ route('distributeNumberToAgent',$agentID->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Select Agent Name</label>
                                        <select class="form-select" name="new_agent" aria-label="Default select example">
                                            <option selected>-- Aelect Agent Name --</option>
                                            @foreach ($allAgent as $agent)
                                                <option value="{{ $agent->id }}"> {{ $agent->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('new_agent')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Expiry Date</label>
                                        <input type="date" class="form-control" name="date" id="exampleInputEmail1"
                                            placeholder="Enter User Email" value="{{ old('data') }}">
                                        @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Count To Distribute Number</label>
                                        <input type="number" class="form-control" name="number" id="exampleInputEmail1"
                                            placeholder="Enter Distribute Number Count" value="{{ old('number') }}">
                                        @error('number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewCustomerNumber') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
