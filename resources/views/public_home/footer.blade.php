<footer>
    <div class="footer_top row">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer_menu">
                        <img alt="..." class="footer_img" src="{{ asset('assets/images/u252.png') }}"><span class="footer_title">{!!trans('messages.footer.f_basic')!!}</span>
                        <ul>
                            @foreach($related as $relatedData)
                                <li><a href="{{url($relatedData->details_url)}}">  {{ App::isLocale('bn') ? $relatedData->heading : $relatedData->heading_en }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="footer_menu">
                        <img alt="..." class="footer_img" src="{{ asset('assets/images/u252.png') }}"><span
                            class="footer_title">{!! trans('messages.footer.f_resources') !!}</span>
                        <ul>
                            @foreach($resource as $resourceData)
                                <li><a href="{{url($resourceData->details_url)}}">  {{ App::isLocale('bn') ? $resourceData->heading : $resourceData->heading_en }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="footer_menu">
                        <img alt="..." class="footer_img" src="{{ asset('assets/images/u252.png') }}"><span
                            class="footer_title">{!! trans('messages.footer.f_other') !!}</span>
                        <ul>
                            @foreach($others as $othersData)
                                <li><a href="{{url($othersData->details_url)}}"> {{ App::isLocale('bn') ? $othersData->heading : $othersData->heading_en }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-xs-12">
                    <a target="_blank" href="https://www.ba-systems.com">
                        <img class="footer_bottom_logo" src="{{ asset('assets/images/business_automation.png') }}"
                             alt="https://www.ba-systems.com">
                    </a>
                </div>
                <div class="col-md-10 col-xs-12 text-center">
                    {!! trans('messages.footer.copyright_text') !!}
                </div>
                <div class="col-md-1 col-xs-12">
                    <a target="_blank" href="https://ocpl.com.bd">
                        <img class="footer_bottom_logo" src="{{ asset('assets/images/feature-logo-ossp.png') }}"
                             alt="https://ocpl.com.bd">
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
