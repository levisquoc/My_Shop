<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <ol class="breadcrumb">
            @if(count(Request::segments()) == 1)
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            @else
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
            @endif

            <?php $breadcrumb_url = url(''); ?>
            @for($i = 1; $i <= count(Request::segments()); $i++)
                <?php $breadcrumb_url .= '/' . Request::segment($i); ?>
                @if(Request::segment($i) != ltrim(route('admin.dashboard', [], false), '/') && !is_numeric(Request::segment($i)))

                    @if($i < count(Request::segments()) && $i > 0)
                        <li class="breadcrumb-item active"><a
                                    href="{{ $breadcrumb_url }}">{{ ucwords(str_replace('-', ' ', str_replace('_', ' ', Request::segment($i)))) }}</a>
                        </li>
                    @else
                        <li class="breadcrumb-item">{{ ucwords(str_replace('-', ' ', str_replace('_', ' ', Request::segment($i)))) }}</li>
                    @endif

                @endif
            @endfor
            {{--<li class="breadcrumb-item active">Layout Fix-header &amp; sidebar</li>--}}
        </ol>
    </div>
</div>
