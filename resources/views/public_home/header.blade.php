<style>
    .reg_btn {
        background-color: #fff;
        color: #006a4e;
        border-color: #006a4e;
    }
</style>
<nav class="navbar navbar-expand-lg navbar-success bg-success">
    {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" --}}
    {{-- aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> --}}
    {{-- <span class="navbar-toggler-icon"></span> --}}
    {{-- </button> --}}

    <div class="collapsse navbar-collapse" id="navbarSupportedContents">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item d-none d-sm-block">
                <i class="fa fa-mail-bulk"> </i> support@ba-systems.com &nbsp;&nbsp;&nbsp;
            </li>

            <li class="nav-item d-none d-sm-block">
                <i class="fa fa-phone-square"> </i> 09639655565
            </li>


        </ul>
        <form class="form-inline my-2 my-lg-0">
            One Stop Service (OSS)

            @if (config('app.APP_ENV', 'local') == 'local')
                <div class="outerDivFull">
                    <div class="switchToggle">
                        <input type="checkbox" id="switch" onchange="languageSwitch(this.checked)" value="bn"
                            {{ App::getLocale() == 'bn' ? 'checked' : '' }}>
                        <label for="switch"></label>
                    </div>
                </div>
            @endif
        </form>
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">

        <a class="navbar-brand" href="{{ config('app.PROJECT_ROOT') }}">
            <img style="width: 70%" src="{{ asset(Cache::get('logo-info')->logo) }}"
                alt="{{ config('app.project_name') }} - Logo" class="img-responsive">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                @if (request()->route() == null ||
                    (request()->route() != null && !in_array(request()->route()->uri, ['/', 'login'])))
                    <li class="active menu-text nav-item"><a class="nav-link"
                            href="{{ url('/') }}">{!! env('IS_MOBILE') ? 'Home' : trans('messages.header.home_page') !!}</a>
                    </li>
                @endif
                <li class=" menu-text nav-item">
                    <a class="nav-link" href="{{ url('article/why-bscic') }}">{!! trans('messages.header.why_bscic') !!}
                    </a>
                </li>
                <li class=" menu-text nav-item">
                    <a class="nav-link" href="{{ url('/articles/support') }}">{!! trans('messages.header.help') !!}
                    </a>
                </li>




                {{-- @guest --}}
                {{-- <li class="nav-item"> --}}
                {{-- <a class="nav-link" href="{{ route('login') }}">Login</a> --}}
                {{-- </li> --}}
                {{-- <li class="nav-item"> --}}
                {{-- <a class="nav-link" href="{{ route('register') }}">Register</a> --}}
                {{-- </li> --}}
                {{-- @else --}}
                {{-- <li class="nav-item"> --}}
                {{-- <a class="nav-link" href="{{ route('logout') }}">Logout</a> --}}
                {{-- </li> --}}
                {{-- @endguest --}}
            </ul>

        </div>

        <div class="collapsed navbar-collapseds" id="navbarSupportedContentsd">
            <ul class="navbar-nav">
                <div class="col-md-12 ">
                    <div class="row  ml-auto text-center">


                        <li class="hidden-xs  menu-text nav-item ml-3">
                            <a href="{{ url('client/signup/identity-verify') }}">
                                <button class="btn btn-default btn-block reg_btn"><i class="fa fa-user-plus"></i>
                                    {!! trans('messages.header.register_normal') !!}
                                </button>
                            </a>
                        </li>
                        <li class="hidden-xs  menu-text nav-item ml-3">
                            <a rel="noopener" target="_blank" href="https://osspid.org/user/create">
                                <button class="btn btn-default btn-block reg_btn"><i class="fa fa-user-plus"></i>
                                    {!! trans('messages.header.register') !!}
                                </button>
                            </a>
                        </li>

                        @if (request()->routeIS('/') || request()->routeIS('login'))
                            <li class="hidden-xs menu-text nav-item ml-3">
                                <a href="#LoginPortal" class="btn btn-danger btn-block btn-xs"
                                    style="background-color: #EC1D23"><b><i class="fa fa-chevron-circle-right"></i>
                                        {!! trans('messages.header.login') !!}</b></a>
                            </li>
                        @endif

                    </div>
                </div>
            </ul>
        </div>
    </div>
</nav>
