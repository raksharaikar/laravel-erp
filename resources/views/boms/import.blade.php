
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
                  <h2> Choose file to import</h2> <br>
                  <form action="{{ route('boms.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file"><br><br>
                    <button class="btn btn-success"type="submit">Import Data</button>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>


@endsection