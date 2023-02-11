

<tr class="main-row level-{{ $level }}" data-row-type={{ $indent + 20 }} style="padding-left: {{ $indent }}px;">
    <td data-optionid="2" style="padding-left: {{ $indent }}px;">{{ $level }}</td>
    
    <td data-optionid="2" style="padding-left: {{ $indent }}px;">{{ $bom->partID }}</td>
    <td data-optionid="2" style="padding-left: {{ $indent }}px;">{{ $bom->quantity }}</td>
    <td data-optionid="2" style="padding-left: {{ $indent }}px;">{{ $bom->semi_part_bom_version  }}</td>
    <td data-optionid="2"><a href="#" class="toggle-children">Expand</a></td>
 
</tr>

@if($bom->childparts->count() > 0)
    @foreach($bom->childparts as $child_part)
     <div>
        @include('partials.view', ['bom' => $child_part, 'data' => $bom['children'], 'indent' => $indent + 50 ,'level' => $level + 1])
     </div>
        @endforeach
@endif



 