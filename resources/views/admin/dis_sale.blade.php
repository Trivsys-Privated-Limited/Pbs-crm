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
                            <h3 class="text-center">Distribute Sale To Other Agents</h3>
                        </div>
                        <form action="{{ route('updateSaleAgent',$agentID->id) }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Agent Name</label>
                                        <select class="form-select" name="agent" aria-label="Default select example">
                                            <option selected>-- Aelect Agent Name --</option>
                                 /// Start New Code add /////

                                           @foreach ($agentName as $agent)
                                                    @if($agent->user)  <!-- New Line Add here -->
                                                <option value="{{ $agent->user->id }}"> {{ $agent->user->name }}
                                                </option>
                                                @endif             <!-- New Line Add here -->
                                         @endforeach

                               //// End New Code Add  ////
                                        </select>
                                        @error('agent')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewAgentSaleTable') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
