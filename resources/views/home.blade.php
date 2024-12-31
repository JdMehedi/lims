@extends('public_home.front')

@push('style')
    .welcome-message {
        font-weight: bold;
        margin: 0;
    }
    .welcome-message-wrapper {
        max-width: 1140px;
        height: 40px;
        margin: auto;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    @media (max-width: 500px) {
        p { text-align: center;}
        .welcome-message-wrapper {
            margin-top: 5px;
            margin-bottom: 5px;
        }
    }
@endpush

@section('body')
    <div class="welcome-message-wrapper" style="width: 60%;margin: 0 auto">
    <marquee><p class="welcome-message">{!! !empty($latestNotice->details) ? $latestNotice->details : 'Welcome to License Issuance & Management System (LIMS)' !!}</p></marquee>
    </div>
    <main class="main-content">
        <section class="home-slider">
            <div id="btrcHomeSlider" class="carousel slide">
                <div class="carousel-inner">
                    <div class="carousel-item">
                        <div class="btrc-home-slider-item" style="background-image: url({{asset('assets/images/new_images/slider/image-02.jpg')}});"></div>
                    </div>
                    <div class="carousel-item active">
                        <div class="btrc-home-slider-item" style="background-image: url({{asset('assets/images/new_images/slider/image-01.jpg')}});"></div>
                    </div>
                    <div class="carousel-item">
                        <div class="btrc-home-slider-item" style="background-image: url({{asset('assets/images/new_images/slider/image-03.jpg')}});"></div>
                    </div>
                </div>
                <div class="home-slider-nav">
                    <a class="carousel-control-prev" href="#btrcHomeSlider" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <ol class="carousel-indicators">
                        <li data-target="#btrcHomeSlider" data-slide-to="0">
                            <div class="btrc-hs-nav-item" style="background-image: url({{asset('assets/images/new_images/slider/image-02.jpg')}});"></div>
                        </li>
                        <li data-target="#btrcHomeSlider" data-slide-to="1" class="active">
                            <div class="btrc-hs-nav-item" style="background-image: url({{asset('assets/images/new_images/slider/image-01.jpg')}});"></div>
                        </li>
                        <li data-target="#btrcHomeSlider" data-slide-to="2">
                            <div class="btrc-hs-nav-item" style="background-image: url({{asset('assets/images/new_images/slider/image-03.jpg')}});"></div>
                        </li>
                    </ol>
                    <a class="carousel-control-next" href="#btrcHomeSlider" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="home-information">
            <div class="container">
                <div class="sec-title">
                    <h2>Information</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-3 col-lg-4 mb-4">
                        <a class="feature-white-box" href="{{URL('/')}}">
                            <div class="feature-card">
                                <div class="feature-card-icon">
                                    <img src="{{asset('assets/images/new_images/icon-info-01.svg')}}" alt="Img">
                                </div>
                                <h3>License</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-4 mb-4">
                        <a class="feature-white-box" href="{{URL('/notices')}}">
                            <div class="feature-card">
                                <div class="feature-card-icon">
                                    <img src="{{asset('assets/images/new_images/icon-info-02.svg')}}" alt="Img">
                                </div>
                                <h3>Notice</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-4 mb-4">
                        <a class="feature-white-box" href="{{URL('/guidelines')}}">
                            <div class="feature-card">
                                <div class="feature-card-icon">
                                    <img src="{{asset('assets/images/new_images/icon-info-03.svg')}}" alt="Img">
                                </div>
                                <h3>Guideline</h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        {{--            {{dd($data)}}--}}
{{--        <section class="home-documentary">--}}
{{--            <div class="container">--}}
{{--                <div class="sec-title">--}}
{{--                    <h2>Documentary</h2>--}}
{{--                </div>--}}
{{--                <div class="row">--}}

{{--                    <div class="col-xl-3 col-lg-6 mb-4">--}}
{{--                        <div class="feature-white-box">--}}
{{--                            <div class="home-doc-item">--}}
{{--                                <div class="doc-item-icon"><img src="{{asset('assets/images/new_images/doc-icon-01.svg')}}" alt="icon"></div>--}}
{{--                                <h3 class="doc-num">{{$data['total_application']}}</h3>--}}
{{--                                <span class="doc-text">Total Application</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-xl-3 col-lg-6 mb-4">--}}
{{--                        <div class="feature-white-box">--}}
{{--                            <div class="home-doc-item">--}}
{{--                                <div class="doc-item-icon"><img src="{{asset('assets/images/new_images/doc-icon-02.svg')}}" alt="icon"></div>--}}
{{--                                <h3 class="doc-num">{{$data['total_draft']}}</h3>--}}
{{--                                <span class="doc-text">Draft Application</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-xl-3 col-lg-6 mb-4">--}}
{{--                        <div class="feature-white-box">--}}
{{--                            <div class="home-doc-item">--}}
{{--                                <div class="doc-item-icon"><img src="{{asset('assets/images/new_images/doc-icon-03.svg')}}" alt="icon"></div>--}}
{{--                                <h3 class="doc-num">{{$data['total_approve_application']}}</h3>--}}
{{--                                <span class="doc-text">Approved Application</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-xl-3 col-lg-6 mb-4">--}}
{{--                        <div class="feature-white-box">--}}
{{--                            <div class="home-doc-item">--}}
{{--                                <div class="doc-item-icon"><img src="{{asset('assets/images/new_images/doc-icon-04.svg')}}" alt="icon"></div>--}}
{{--                                <h3 class="doc-num">{{$data['total_reject_application']}}</h3>--}}
{{--                                <span class="doc-text">Reject Application</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}

        <section class="home-contact">
            <div class="container" id="contactUs">
                <div class="sec-title">
                    <h2>Contact Us</h2>
                </div>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="contact-form">
                            <div class="contact-title">
                                <h2>Send A Message</h2>
{{--                                <p>When, while lovely valley teems with vapour around meand meridian the upper impenetrable.</p>--}}
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="email" class="form-control" placeholder="Your Email">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" placeholder="Phone number">
                                    </div>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" name="contact-message" id="contact-message" cols="20" rows="6" placeholder="Your Message Here"></textarea>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="homeContactCheck">
                                            <label class="form-check-label" for="homeContactCheck">Your email address will not be published. Required fields are marked *</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="btn-right">
                                            <button class="btn-btrc">Send Message</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="get-in-touch">
                            <div class="contact-title">
                                <h3>Get In Touch</h3>
{{--                                <p>therefore always free from repetition, injected humour, or non-characteristic</p>--}}
                            </div>

                            <div class="contact-info">
                                <div class="contact-info-item">
                                    <span class="info-icon"><img src="{{asset('assets/images/new_images/icon-location.svg')}}" alt="Icon"></span>
{{--                                    <p><span>visit us :</span> Bangladesh Telecommunication Regulatory Commission IEB Building (5th, 6th & 7th Floor), Ramna, Dhaka-1000</p>--}}
                                    <p><span>visit us :</span>{{ config('app.address') }}</p>
                                </div>
                                <div style="margin-top: -40px; margin-bottom: 20px;">
                                    <iframe src="{{ config('app.GOOGLE_MAP_LINK') }}" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>


                                <div class="contact-info-item">
                                    <span class="info-icon"><img src="{{asset('assets/images/new_images/icon-envelop.svg')}}" alt="Icon"></span>
                                    <p><span>mail us :</span> <a href="mailto:info@btrc.gov.bd">lims@btrc.gov.bd</a></p>
                                </div>
                                <div class="contact-info-item">
                                    <span class="info-icon"><img src="{{asset('assets/images/new_images/icon-phone.svg')}}" alt="Icon"></span>
                                    <p><span>phone us :</span> <a href="tel:100">100 (Call Center)</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-12 justify-content-center">--}}
{{--                        <iframe src="{{ config('app.GOOGLE_MAP_LINK') }}" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </section>
    </main>
@endsection

@section('footer-script')
@endsection
