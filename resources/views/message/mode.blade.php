@if(env('APP_ENV') != 'PRODUCTION' or env('APP_ENV') != 'Live')
{{--    <span class="text-danger huge">You are connected to {!! env('APP_ENV') !!} Database!</span>--}}
@endif