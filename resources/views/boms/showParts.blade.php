@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <h1 class="text-center">Welcome to Parts list</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr data-optionid="2" class="parent-part">
                        <th scope="col">Level</th>
                        <th scope="col">Part ID</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Semi part BOM version</th>
                        
                    </tr>
                </thead>
                    <tbody>
                        
                            @foreach ($boms[0]->childparts as $bom)
                            @include('partials.view', ['$bom' => $bom, 'indent' => 20, 'level' => 1])
               

                            @endforeach
                       
                    </tbody>
   </table>
            </div>
        </div>
    </div>


    
@endsection
