<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersVCS;

use Validator;

class UsersVCSController extends Controller
{
    public function get(UsersVCS $vcs, $id)
    {
        $resp = $vcs->find($id);
        if (!empty($resp)) {
            return response()->json($resp, 200);
        } else {
            return response()->json(['Error'=>"User $id not found"], 404);
        }
    }

    public function getByUser(UsersVCS $vcs)
    {
        $user = \Auth::user();
        $resp = $vcs->where('user_id', $user->id)->first();
        if (!empty($resp)) {
            return response()->json($resp, 200);
        } else {
            return response()->json(['Error'=>"User $id not found"], 404);
        }
    }

    public function search(UsersVCS $vcs, Request $request)
    {
        $rules = array(
            'user_id' => "integer",
            'id' => 'integer',
        );

        if ($response = $this->validatorResponse($request->all(), $rules)) {
            return $response;
        }

        $per_page = $request->has('per_page') ? $request->per_page : 25;

        $resp = $vcs->when($request->has('id'), function ($q) use ($request) {
            $q->where('id', $request->id);
        })->when($request->has('user_id'), function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        })->paginate($per_page);

        if (!empty($resp)) {
            return response()->json($resp, 200);
        } else {
            return response()->json(['Error'=>"No virtual currency found for this query"], 404);
        }
    }

    public function store(Request $request, $id = false)
    {
        //save item
    }

    public function delete($id)
    {
        //Delete item
    }
}
