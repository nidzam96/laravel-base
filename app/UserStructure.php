<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStructure extends Model
{

	protected $fillable = [
    	'user_id', 
    	'parent_id',
    ];

    public function user() 
    {
    	return $this->belongsTo('App\User', 'user_id');
    }

    
}
