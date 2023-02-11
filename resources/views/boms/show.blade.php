@extends('layout.layout')

@section('content')

<table class="table table-striped">
 
    <thead>
        <tr>
            <th>bom ID</th>
            <th>Part ID</th>
            <th>bom version</th>
        </tr>
    </thead>
    <tbody>
      
        {{ $id}}
        </tr>
    </tbody>
</table>

@endsection