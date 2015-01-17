<?php

class Ban extends \Eloquent {
	protected $table ='bans';

	// Add your validation rules here
	public static $rules = [
		'type' => 'in:nick,user,ip,realname,version,server',
		'action' => 'in:kill,tempshun,shun,kline,zline,gline,gzline',
	];

	// Don't forget to fill this array
	protected $fillable = ['type','mask','reason','action'];
}