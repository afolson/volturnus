@extends('layouts.default')
@section('content')
    {{ Form::model($server, array('method' => 'PATCH', 'route' => ['servers.update', $server->id], 'class'=>'form-horizontal', 'id'=>'serverForm')) }}
    <div class="form-group">
        {{ Form::label('name', 'Server Name', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('name', $server->name, array( 'class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('ip', 'IP', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('ip', $server->ip, array( 'class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('password', 'Password', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('password', $server->password, array( 'class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Save', array('class' => 'btn btn-default')) }}
        </div>
    </div>
    {{ Form::close() }}
@stop
