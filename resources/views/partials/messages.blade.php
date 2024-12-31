<div class="row">
    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>'. Session::get("error") .'</div>' : '' !!}
    </div>
</div>