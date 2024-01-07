<option value="{{$menuData->menu_id}}" @if(isset($menuIdData->menu_parent) && $menuIdData->menu_parent==$menuData->menu_id){{'selected'}}@else{{''}}@endif>
	{{ $newDashes }}{{$menuData->menu_name}}
</option>
 @php $newDashes = $dashes. ' -- ' @endphp
@foreach($menuData->children as $childdata)
@include('csadmin.elements.recursive_dropdown_menu',['menuData'=>$childdata,'menuIdData'=>$menuIdData,'dashes'=>$newDashes])
@endforeach