@extends('layouts.default')
@section('content')
    {{ Form::model($ban, array('method' => 'PATCH', 'route' => ['bans.update', $ban->id], 'class'=>'form-horizontal', 'id'=>'banForm')) }}
        <div class="form-group">
            {{ Form::label('type', 'Ban Type', array('class' => 'col-sm-2 control-label')) }}
            <div class="col-sm-10">
                {{ Form::select('type', array('nick' => 'nick',
                                                'user' => 'user',
                                                'ip' => 'ip',
                                                'realname' => 'realname',
                                                'version' => 'version',
                                                'server' => 'server'), $ban->type);
                }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('mask', 'Ban Mask', array('class' => 'col-sm-2 control-label')) }}
            <div class="col-sm-10">
                {{ Form::text('mask', $ban->mask, array( 'class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('reason', 'Ban Reason', array('class' => 'col-sm-2 control-label')) }}
            <div class="col-sm-10">
                {{ Form::text('reason', $ban->reason, array( 'class' => 'form-control')) }}
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
                                'gzline' => 'gzline'), $ban->action);
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