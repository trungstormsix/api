@foreach($items as $item)
    @php ($attr = $item->attributes)
        <li class="{!! $item->isActive ? 'active' : '' !!} {{$item->class}}">
            @if($item->hasChildren())
                <a href="{!! $item->url() !!}">
                    <i class="fa fa-{{$attr? $attr["pre_icon"] : 'user'}}"></i>
                    <span class="nav-label">{!! $item->title !!}</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    @include('layouts.menu.custom-menu-items', array('items' => $item->children(), 'level' => $level+1))
                </ul> 
            @else
                @if($item->link)
                <a href="{!! $item->url() !!}">
                    @if($item->parent)
                    {!! $item->title !!}
                    @else
                    <i class="fa fa-{{$attr? $attr["pre_icon"] : 'user'}}"></i>
                    <span class="nav-label">{!! $item->title !!}</span>
                    @endif
                </a>
                @else
                    {!! $item->title !!}
                @endif
            @endif

        </li>
    @if($item->divider)
        <hr {!! Lavary\Menu\Builder::attributes($item->divider) !!} /> 
    @endif
@endforeach