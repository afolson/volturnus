<?php

class ServersController extends \BaseController {

	/**
	 * Display a listing of servers
	 *
	 * @return Response
	 */
	public function index()
	{
		$servers = Server::all();
		return View::make('servers.index', compact('servers'), array('pageTitle' => 'Servers'));
	}

	/**
	 * Show the form for creating a new server
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('servers.create');
	}

	/**
	 * Store a newly created server in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Server::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Server::create($data);

		return Redirect::route('servers.index');
	}

	/**
	 * Display the specified server.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$server = Server::findOrFail($id);

		return View::make('servers.show', compact('server'));
	}

	/**
	 * Show the form for editing the specified server.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$server = Server::find($id);

		return View::make('servers.edit', compact('server'));
	}

	/**
	 * Update the specified server in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$server = Server::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Server::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$server->update($data);

		return Redirect::route('servers.index');
	}

	/**
	 * Remove the specified server from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Server::destroy($id);

		return Redirect::route('servers.index');
	}

}
