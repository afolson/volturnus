<?php

class BansController extends \BaseController {

	/**
	 * Display a listing of bans
	 *
	 * @return Response
	 */
	public function index()
	{
		$bans = Ban::all();
		return View::make('bans.index', compact('bans'), array('pageTitle' => 'Bans'));
	}

	/**
	 * Show the form for creating a new ban
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('bans.create', array('pageTitle' => 'Bans - Create'));
	}

	/**
	 * Store a newly created ban in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Ban::$rules);
		if ($validator->fails())
		{
			return Redirect::route('bans.create')->withErrors($validator)->withInput();
		} else {
			Ban::create($data);
			return Redirect::route('bans.index');
		}
	}

	/**
	 * Display the specified ban.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$ban = Ban::findOrFail($id);
		return View::make('bans.show', compact('ban'));
	}

	/**
	 * Show the form for editing the specified ban.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$ban = Ban::find($id);
		return View::make('bans.edit', compact('ban'), array('pageTitle' => 'Bans - Edit'));
	}

	/**
	 * Update the specified ban in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$ban = Ban::findOrFail($id);
		$validator = Validator::make($data = Input::all(), Ban::$rules);
		if ($validator->fails())
		{
			return Redirect::route('bans.edit')->withErrors($validator)->withInput();
		} else {
			$ban->update($data);
			return Redirect::route('bans.index');
		}
	}

	/**
	 * Remove the specified ban from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Ban::destroy($id);
		return Redirect::route('bans.index')->with('message', 'Ban has been deleted.');
	}

}
