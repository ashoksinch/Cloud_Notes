<?php 

class ApplicationController extends BaseController{

	public function __construct()
	{
		
	}
	public function getIndex()
	{
		return View::make('index');
	}

	public function postResult()
	{
		$private_key = "ef8ea506d45539f3";
		$signature = microtime();
		$api_sig = md5($private_key.$signature);
		$api_key = "730cc907c6ba220474d1a82372e2033e";
		$search = Input::get('search');
		$url = "http://persons.api.centroidmedia.com/search?lang=nl&country=nl&api_key=$api_key&api_sig=$api_sig&signature=$signature&fullname=$search&sources=facebook";
		$json = file_get_contents("http://persons.api.centroidmedia.com/search?lang=nl&country=nl&api_key=$api_key&api_sig=$api_sig&signature=$signature&fullname=$search&sources=facebook", true);
		dd($json);
	}

	public function getResult()
	{
		return View::make('search');
	}
}
