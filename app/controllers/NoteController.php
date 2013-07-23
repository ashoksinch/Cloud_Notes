<?php
/**
* 
*/
class NoteController extends BaseController
{
	

	public function post_create(){

		$data = Input::get();
		$rules = array("title" => 'required',
						"content" => 'required',
						"tag" => 'required'
						);

		$validator = Validator::make($data, $rules);
		
		if($validator->fails())
		{
			Response::json(array("errors" => $validator->messages()), 400);
		}

		//note saving in database
		$note = new Note();
		$note->user_id = Auth::user()->id;
		$note->fill($data);
		$note->save();

		//tag saving in database

		$tags = Input::get("tags");
		$tags = explode(",", $tags);
		
		$tagsToInsert = array();
		foreach ($tags as $t) {
			if( trim( strlen($t) ) > 0 ){
				array_push($tagsToInsert, array(
					"note_id" => $note->id,
					"content" => trim($t),
					"created_at" => date("Y-m-d H:i:s"),
					"updated_at" => date("Y-m-d H:i:s")
				));
			}
		}

		if( count($tagsToInsert) > 0 ){
			Tag::insert($tagsToInsert);	
		}
		




		if($note->id)
			 return Response::json(compact("note"), 201 );
		else
			return Response::json(array("message" => "Note has been saved"), 400);

	}

	public function get_show($id){

		$notes = Note::with("tags", "user")->find($id)->toArray();

		return Response::json(compact("notes"), 200 );

	}


	public function put_update($id){

		$data = Input::get();
		$note = Note::find($id);
		$note->fill($data);
		$note->save();

		$tags = Tag::where("note_id", $id)->get();
		foreach($tags as $tag)
		{
			$tag->content = Input::get("tags");
			$tag->updated_at = date("Y-m-d H:i:s");
			$tag->save();
			return Response::json(compact("tag"), 200 );			
		}

		return Response::json(compact("note"), 200);

	}

	public function get_destroy($id){

		$notes = Note::with("tags")->find($id)->toArray();
		foreach($notes->tags as $tag)
		{
			$tag->delete();
		}
		$notes->delete();

		return Response::json(array(), 200 );

	}


	public function post_search(){

		$search = Input::get("search");

		$notes = Note::with("user", "tags")
					->where("title", $search)
					->get()
					->toArray();
		return Response::json(array(compact("notes")), 200);

	}
}