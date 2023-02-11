
@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                 
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h2> Enter details to compare</h2> <br>
                    <form action="{{ route('compare-boms') }}" method="post">
                        @csrf
                         
                            <label for="parentID">Parent ID:</label>
                            <input type="text" id="parentID" name="parentID">
                     
                    
                            <label for="bom_version_1">BOM Version 1:</label>
                            <input type="text" id="bom_version_1" name="bom_version_1">
                    
                    
                            <label for="bom_version_2">BOM Version 2:</label>
                            <input type="text" id="bom_version_2" name="bom_version_2">
                   
                        <button type="submit">Compare BOMs</button>
                    </form>
            <br><br>
                    <h2>Comparison with structure</h2>
@if(isset($boms1) && isset($boms2))
<div class="row">
    <div class="col-md-6">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Parent ID</th>
                    <th>Child Parts (BOM Version 1)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boms1 as $bom1)
                    <tr>
                        <td>{{ $bom1->partID }}</td>
                        <td>
                            <table class="table">                               
                                <tbody>
                                    @foreach ($bom1->childparts as $childpart1)
                                        <tr>
                                            <td>{{ $childpart1->partID }}</td>
                                            <td>{{ $childpart1->quantity }}</td>
                                            @if(count($childpart1->childparts) > 0){
                                                <td>
                                                    <table class="table">
                                                   
                                                    <tbody>
                                                        @foreach ($childpart1->childparts as $childpart2)
                                                            <tr>
                                                                <td>{{ $childpart2->partID }}</td>
                                                                <td>{{ $childpart2->quantity }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            
                                            </td>
                                            }
                                             @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Parent ID</th>
                    <th>Child Parts (BOM Version 2)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boms2 as $bom2)
                    <tr>
                        <td>{{ $bom2->partID }}</td>
                        <td>
                            <table class="table">
                                
                                <tbody>
                                    @foreach ($bom2->childparts as $childpart2)
                                        <tr>
                                            <td>{{ $childpart2->partID }}</td>
                                            <td>{{ $childpart2->quantity }}</td>
                                            @if(is_array($childpart2->childparts)){
                                                <td>
                                                    <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Child Part ID</th>
                                                            <th>Child Part Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($childpart2->childparts as $childpart2)
                                                            <tr>
                                                                <td>{{ $childpart2->partID }}</td>
                                                                <td>{{ $childpart2->quantity }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            
                                            </td>
                                            }
                                             @endif
                                           
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div>
    <h2>Comparison without structure ( quantity accumulated )</h2>
<table>
    <thead>
        <tr>
            <th>Part ID</th>
            <th>Part Name</th>
            <th>Quantity - BOM 1</th>
            <th>Quantity - BOM 2</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($boms1Data as $partID => $partData)
            <tr>
                <td>{{ $partData['partID'] }}</td>
                <td>{{ $partData['part_name'] }}</td>
                <td>{{ $partData['quantity'] }}</td>
                <td>{{ $boms2Data[$partID]['quantity'] ?? '---' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

                </div>
            </div>
        </div>
    </div>
    
  </div>



 

  
 
  
@endif


@endsection