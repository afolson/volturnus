@extends('layouts.default')

@section('content')
    <p>Select the ban you wish to edit. You may also add a new ban {{ link_to_route('bans.create', 'here') }}.</p>
    @if ( !$bans->count() )
    <p>There are currently no bans. Click {{ link_to_route('bans.create', 'here') }} to add some!</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Mask</th>
                    <th>Reason</th>
                    <th>Action</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
            @foreach( $bans as $ban )
                <tr>
                    <td>{{ $ban->type }}</td>
                    <td>{{ $ban->mask }}</td>
                    <td>{{ $ban->reason }}</td>
                    <td>{{ $ban->action }}</td>
                    <td>
                        {{ Form::open(array('class' => 'inline', 'method' => 'DELETE', 'route' => array('bans.destroy', $ban->id))) }}
                        {{ link_to_route('bans.edit', 'Edit', array($ban->id), array('class' => 'btn btn-info')) }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
                </tbody>
            </table>
        </div>
    @endif
@stop