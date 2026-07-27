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
                            <h3 class="text-center">Distribute Sale To Other Agents</h3>
                        </div>
                        <form action="{{ route('updateSaleAgent', $agentID->id) }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Agent Name</label>
                                        <select class="form-select" name="agent" aria-label="Default select example">
                                            <option selected disabled>-- Select Agent Name --</option>
                                            @foreach ($agentName as $agent)
                                                @if($agent->user)
                                                    <option value="{{ $agent->user->id }}">{{ $agent->user->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('agent')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Sale Distribute Count Input Field -->
                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Sale Distribute Count</label>
                                        <input type="number" class="form-control" name="number" id="exampleInputEmail1"
                                            placeholder="Enter count" value="{{ old('number') }}" required min="1">
                                        @error('number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewAgentSaleTable') }}" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection