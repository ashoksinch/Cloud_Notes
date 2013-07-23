<?php
/**
* 
*/
class Tag extends Eloquent
{
	
	protected $table = "tags";
	protected $fillable = array("note_id", "content");

	public function note(){

		return $this->belongsTo("note");
	}
}