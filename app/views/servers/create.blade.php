@extends('layouts.default')
@section('content')
    {{ Form::model(new Server, array('route' => 'servers.store', 'class'=>'form-horizontal', 'id'=>'serverForm')) }}
    <div class="form-group">
        {{ Form::label('name', 'Server Name', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('name', '', array( 'class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('ip', '', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('ip', '', array( 'class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('password', '', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('password', '', array( 'class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Save', array('class' => 'btn btn-default')) }}
        </div>
    </div>
    {{ Form::close() }}
@stop