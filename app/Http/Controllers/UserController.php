<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Validator;

class UserController extends Controller
{
    public function get(User $user, $id)
    {
        $resp = $user->find($id);
        if (!empty($resp)) {
            return response()->json($resp, 200);
        } else {
            return response()->json(['Error'=>"User $id not found"], 404);
        }
    }

    public function search(User $user, Request $request)
    {
        $rules = array(
            'email' => "string",
        );

        if ($response = $this->validatorResponse($request->all(), $rules)) {
            return $response;
        }

        $per_page = $request->has('per_page') ? $request->per_page : 25;

        $resp = $user->when($request->has('email'), function ($q) use ($request) {
            $q->where('email', "LIKE", "%".$request->email."%");
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
