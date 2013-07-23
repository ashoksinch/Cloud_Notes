<?php
/**
* 
*/
class UserController extends BaseController
{
	

	//user create and store in datebase
	public function post_create(){

		$data = Input::get();
		$rules = array("username" => 'required',
						"password" => 'required',
						"email" => 'required|email'
						);
		$validator = Validator::make($data, $rules);
		if($validator->fails())
		{
			return Response::json(array("errors" => $validator), 401);
		}

		$data["password"] = Hash::make($data["password"]);
		$user = new User();
		$user->fill($data);
		$user->save();

		if($user->id)
			return Response::json(compact("user"), 201);
		else
			return Response::json( array("message" => "Unable to create user"), 400 );

	}


	//user login
	public function post_login(){
		$data["username"] = Input::get("username");
		$data["password"] = Input::get("password");

		if( Auth::attempt( $data )){
			return Response::json(array(), 204 );

		}
		else
		{
			return Response::json(array("message" => "Username and Password Combination Wrong"), 400);
		}

	}


	//user logout
	public function get_logout(){

		Auth::logout();
		return Response::json(array(), 204);
	}
}