@extends('layouts.admin')

@section('content')

    @include('partials.messages')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <div class="float-left">
                    <h5><i class="fa fa-list"></i> <strong>Application Search</strong></h5>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="card-body">
                {!! Form::open(['url' => 'settings/app-rollback-open', 'method' => 'POST', 'id' => 'appRollback'])!!}
                <div class="row">
                    <div class="offset-md-3 col-md-5">
                        <label for="">Search Application: </label>
                        {!! Form::text('tracking_no', '', ['class' => 'form-control search_text', 'placeholder'=>'Tracking Number']) !!}
                    </div>
                    <div class="col-md-1">
                        <label for="">&nbsp;</label>
                        <button style="margin-top: 28px" type="submit" id="search_process" class="btn btn-primary">Search</button>
                    </div>
                </div>
                {!! Form::close()!!}
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div>
</div>
@endsection
