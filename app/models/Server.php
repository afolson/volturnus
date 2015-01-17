<?php

class Server extends \Eloquent {
	protected $table ='servers';

	// Add your validation rules here
	public static $rules = [
		'name' => 'required',
		'ip' => 'required|ip',
		'password' => 'required',
	];

	// Don't forget to fill this array
	protected $fillable = ['name','ip','password'];
}