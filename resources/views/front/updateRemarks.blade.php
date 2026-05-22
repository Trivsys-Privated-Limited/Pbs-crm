@extends('layout.index')
@extends('front.nav')
@section('home')
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="text-center">Update Help Remarks</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('agentRemarksUpdate', $help->id) }}" method="POST"
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
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
