@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                 
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   

                    <a href="{{ route('boms.store') }}" class="btn btn-primary">View BOM list</a> <br> <br>
                    <a href="{{ route('boms.import') }}" class="btn btn-primary">Import BOM</a> <br> <br>
                    <a href="{{ route('compare-boms') }}" class="btn btn-primary">Compare BOMs</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
