<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>@yield('title')</title>
    <link href='https://fonts.googleapis.com/css?family=Vollkorn' rel='stylesheet' type='text/css'>
</head>


<body>
<table width="80%"
       style="background-color:#D2E0E8;margin:0 auto; height:50px; border-radius: 4px;font-family: Vollkorn">
    <thead>
    <tr>
        <td style="padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);">
            <img style="margin-left: auto; margin-right: auto; display: block;"

                 src="{{ URL::to('/assets/images/new_images/btrc.png') }}" width="80px" alt="{{config('app.project_name')}}"/>
            <h4 style="text-align:center">
                {{config('app.project_name')}}
            </h4>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="margin-top: 20px; padding: 15px;">
            @yield('content')
            <br/><br/>
        </td>
    </tr>
    <tr style="margin-top: 15px;">
        <td style="padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);">
            <h5 style="text-align:center">All right reserved by {{config('app.project_name')}} {{ date('Y') }}.</h5>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
