<?php
/**
* 
*/
class Note extends Eloquent
{
	
	protected $table = "notes";
	protected $fillable = array("user_id", "title", "content");

	public function user(){
		return $this->belongsTo("user");
	}

	public function tags(){
		return $this->hasMany("tag");
	}
}