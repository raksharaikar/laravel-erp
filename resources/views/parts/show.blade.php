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
      
        
            @foreach ($parts as $part)
            <tr>
                <td>{{ $part->bomID }}</td>
                <td><a href="{{ route('parts.show', $part->parentID) }}">{{ $part->parentID }}</a></td>
                <td>{{ $part->bom_version }}</td>
            </tr>
        @endforeach
        </tr>
    </tbody>
</table>

@endsection