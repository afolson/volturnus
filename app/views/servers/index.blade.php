@extends('layouts.default')

@section('content')
    <p>Select the server you wish to edit. You may also add a new server {{ link_to_route('servers.create', 'here') }}.</p>
    @if ( !$servers->count() )
        <p>There are currently no servers. Click {{ link_to_route('servers.create', 'here') }} to add some!</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>IP</th>
                    <th>Password</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $servers as $server )
                    <tr>
                        <td>{{ $server->name }}</td>
                        <td>{{ $server->ip }}</td>
                        <td>{{ $server->password }}</td>
                        <td>
                            {{ Form::open(array('class' => 'inline', 'method' => 'DELETE', 'route' => array('servers.destroy', $server->id))) }}
                            {{ link_to_route('servers.edit', 'Edit', array($server->id), array('class' => 'btn btn-info')) }}
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