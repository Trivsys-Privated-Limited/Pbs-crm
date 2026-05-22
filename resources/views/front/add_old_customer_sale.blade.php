@extends('layout.index')
@extends('front.nav')

@section('home')
    <div class="container">
        <div class="row mt-7">
            <div class="col-6 mx-auto">
                @if (session('success'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('storeOldCustomerNewPKGData',$oldCustomerData->id) }}" method="POST" autocomplete="off">
                    @csrf
                    <h2 class="text-xl font-bold text-center mb-2">Add Old Customer New PKG</h2>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Price</label>
                        <input type="number" class="form-control" name="price" id="exampleInputEmail1"
                            placeholder="Enter price" aria-describedby="emailHelp">
                        @error('price')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Customer Registration Date</label>
                        <input type="date" class="form-control" name="date" id="exampleInputEmail1"
                            placeholder="Enter Customer Name" aria-describedby="emailHelp">
                        @error('date')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="floatingTextarea" class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" placeholder="Enetr Remarks" id="floatingTextarea"></textarea>
                        @error('remarks')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="floatingTextarea" class="form-label">Renewal</label>
                        <input type="number" class="form-control" name="renewal" placeholder="Enter Renewal (months)" id="floatingTextarea">
                        @error('renewal')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>

                    <a href="{{ route('customerSalesTable') }}" class="btn btn-primary">Back</a>
                    <button type="submit" class="btn btn-primary">save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
