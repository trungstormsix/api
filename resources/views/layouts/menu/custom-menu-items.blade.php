@foreach($items as $item)
@php ($attr = $item->attributes)
<!--@php (var_dump($item->attributes))-->
<li class="{!! $item->isActive ? 'active' : '' !!} {{$item->class}}">
    @if($item->hasChildren())
        <a href="{!! $item->url() !!}">
            <i class="fa fa-{{$attr? $attr["pre_icon"] : 'user'}}"></i>
            <span class="nav-label">{!! $item->title !!}</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level collapse">
            @include('layouts.menu.custom-menu-items', array('items' => $item->children(), 'level' => 2))
        </ul> 
    @else
        <a href="{!! $item->url() !!}">
            @if($item->parent)
                {!! $item->title !!}
            @else
                 <i class="fa fa-{{$attr? $attr["pre_icon"] : 'user'}}"></i>
                <span class="nav-label">{!! $item->title !!}</span>
            @endif
         </a>
    @endif
</li>
@endforeach