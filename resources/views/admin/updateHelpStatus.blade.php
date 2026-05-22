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
                            <h3 class="text-center">Update Help Status</h3>
                        </div>
                        <form action="{{ route('updateHelpRequeststatus', $help->id) }}" method="POST"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Remarks</label>
                                        <textarea class="form-control" placeholder="Enter Remarks..." name="remarks" id="floatingTextarea">{{ $help->remarks }} </textarea>
                                        @error('remarks')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-2">
                                        <label for="exampleInputEmail1">Select Status</label>
                                        <select class="form-select" name="status" aria-label="Default select example">
                                            <option selected>{{ $help->status }}</option>
                                            <option value="pending">Pending</option>
                                            <option value="working">Working</option>
                                            <option value="resolve">Resolve</option>
                                            <option value="refund">Refund</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('viewHelpRequestTableDashboard') }}" class="btn btn-primary">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
