@extends('layouts.app')

@section('content')

 


@if(isset($boms) && count($boms)>0)
<div class="container my-5">
  <h1 class="text-center">Welcome to BOM list</h1>
</div>
@endif

  <div class="container">
    <div class="row">
      <div class="col-12 d-flex justify-content-center">
       
        @if(isset($boms) && count($boms)>0)

       
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col">Bom ID</th>
              <th scope="col">Part ID</th>
              <th scope="col">Semi part BOM version</th>
              <th scope="col">Loss rate</th>
              <th scope="col">Bom version</th>
              <th scope="col">quantity</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($boms as $bom)
            <tr>
                <td>{{ $bom->bomID }}</td>
                <td><a href="{{ route('vvv', ['partID' => $bom->partID, 'semiPartVersion' => $bom->bom_version]) }}">{{ $bom->partID }}</a></td>
                <td>{{ $bom->semi_part_bom_version }}</td>
                <td>{{ $bom->loss_rate }}</td>
                <td>{{ $bom->bom_version }}</td>
                <td>{{ $bom->quantity }}</td>
                <td>
                 
                     

                        <form action="{{ route('boms.destroy', ['bom' => $bom->id]) }}" method="POST">
                          @csrf
                          @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                       </form>
                   
                       <a href="{{ route('vvv', ['partID' => $bom->partID, 'semiPartVersion' => $bom->bom_version ]) }}"> 
                        <button type="button" class="btn btn-success">view</button>
                      </a>
                    </td>
            </tr>
        @endforeach
          </tbody>
        </table>
        @else

      <div clas="d-flex justify-content-center" style="text-align: center;">
        <br> <br>
        <H2>No BOM's available</H2>
        <br> <br>
        <a href="{{ route('boms.import') }}" class="btn btn-primary">Import BOM</a> 
    </div> 
      @endif
        
      </div>
    </div>
  </div>
 

@endsection