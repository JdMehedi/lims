{{--@if(count($home_slider_image) > 0)--}}

    <?php  $slider_interval = 4000; $i = 0; ?>
    @foreach($home_slider_image as $key => $sliderInterval)
        @if($key == 0)
            <?php  $slider_interval = $sliderInterval->slider_interval; ?>
        @endif
    @endforeach


<div id="demo" class="carousel slide" data-ride="carousel">

{{--    <!-- Indicators -->--}}
{{--    <ul class="carousel-indicators">--}}
{{--        <li data-target="#demo" data-slide-to="0" class="active"></li>--}}
{{--        <li data-target="#demo" data-slide-to="1"></li>--}}
{{--        <li data-target="#demo" data-slide-to="2"></li>--}}
{{--    </ul>--}}

    <!-- The slideshow -->



    <div class="carousel-inner" style="background: aliceblue">
        @foreach($home_slider_image as $key=>$item)
            <div class="{{$key == 0 ? "active": ""}} carousel-item">
            <img class="banner_img" src="{{URL::to('/'.$item->slider_image) }}"
                 alt="{{ $item->slider_title }}">
            <div class="carousel-caption" style="margin-bottom: 155px ;">
               <h3 style="color: white">{{ $item->slider_title }}</h3>
{{--                <a target="_blank" href="{{ $item->slider_url }}"><h3--}}
{{--                        style="color: white">{{ $item->slider_title }}</h3></a>--}}
            </div>
        </div>
        @endforeach



    </div>

    <!-- Left and right controls -->
    <a class="carousel-control-prev" href="#demo" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </a>
    <a class="carousel-control-next" href="#demo" data-slide="next">
        <span class="carousel-control-next-icon"></span>
    </a>
</div>

