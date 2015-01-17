@extends('layouts.default')
@section('content')
    {{ Form::model(new Ban, array('route' => 'bans.store', 'class'=>'form-horizontal', 'id'=>'banForm')) }}
    <div class="form-group">
        {{ Form::label('type', 'Ban Type', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::select('type', array('nick' => 'nick',
                                            'user' => 'user',
                                            'ip' => 'ip',
                                            'realname' => 'realname',
                                            'version' => 'version',
                                            'server' => 'server'), 'ip');
            }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('mask', 'Ban Mask', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('mask', '', array( 'class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('reason', 'Ban Reason', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::text('reason', 'Reason for ban', array( 'class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('action', 'Ban Action', array('class' => 'col-sm-2 control-label')) }}
        <div class="col-sm-10">
            {{ Form::select('action', array('kill' => 'kill',
                            'tempshun' => 'tempshun',
                            'shun' => 'shun',
                            'kline' => 'kline',
                            'zline' => 'zline',
                            'gline' => 'gline',
                            'gzline' => 'gzline'), 'gline');
            }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Save', array('class' => 'btn btn-default')) }}
        </div>
    </div>
    {{ Form::close() }}
@stop