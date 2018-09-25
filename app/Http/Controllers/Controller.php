<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Validation\Rule;
use Validator;
use App\Http\CustomMessagesRequest;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validatorResponse($params, $rules, $message = false)
    {
        // Validate data.
        $validator = Validator::make($params, $rules, CustomMessagesRequest::messages());
        if ($validator->fails()) {
            if ($message) {
                $resp['msg'] = $message;
            }
            $resp["errors"]= $validator->errors();
            $resp["params"]= $params;
            return response()->json($resp, 422);
        }
    }
}
