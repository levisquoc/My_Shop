@if(!$innerLoop)
    @foreach($items as $item)
        <li class="nav-item {{$item->class}} @if(count($item->getsons($item->id)) != 0) dropdown @endif">
            <a class="nav-link"
               href="@if(str_contains($item->link, ['page','post','product'])) {{route('navigation', Creeper::getLink($item->link))}} @else {{$item->link}} @endif">
                {!! $item->label !!}
            </a>
            @if(count($item->getsons($item->id)) != 0)
                @include('site.layouts.nav', ['items' => $item->getsons($item->id), 'innerLoop' => true])
            @endif
        </li>
    @endforeach
@else
    <div class="dropdown-content">
        @foreach($items as $item)
            <a href="@if(str_contains($item->link, ['page','post','product'])) {{route('navigation', Creeper::getLink($item->link))}} @else {{$item->link}} @endif">
                {!! $item->label !!}
            </a>
        @endforeach
    </div>
@endif