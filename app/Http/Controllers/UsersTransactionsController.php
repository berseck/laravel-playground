<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersTransactions;
use App\Models\UsersVCS;

use Validator;

class UsersTransactionsController extends Controller
{
    public function get(UsersTransactions $transactions, $id)
    {
        $resp = $transactions->find($id);
        if (!empty($resp)) {
            return response()->json($resp, 200);
        } else {
            return response()->json(['Error'=>"User $id not found"], 404);
        }
    }

    public function search(UsersTransactions $transactions, Request $request)
    {
        $rules = array(
            'from' => "integer",
            'to' => "integer",
            'type' => "integer",
            'flag' => "integer",
            'page' => 'integer',
            'per_page' => 'integer',
        );

        if ($response = $this->validatorResponse($request->all(), $rules)) {
            return $response;
        }

        $per_page = $request->has('per_page') ? $request->per_page : 25;

        $resp = $transactions->when($request->has('from'), function ($q) use ($request) {
            $q->where('from', $request->from);
        })->when($request->has('to'), function ($q) use ($request) {
            $q->where('to', $request->to);
        })->paginate($per_page);

        if (!empty($resp)) {
            return response()->json($resp, 200);
        } else {
            return response()->json(['Error'=>"No virtual currency found for this query"], 404);
        }
    }

    public function transactions(UsersTransactions $transactions, Request $request)
    {
        $rules = array(
            'page' => 'integer',
            'per_page' => 'integer',
        );

        if ($response = $this->validatorResponse($request->all(), $rules)) {
            return $response;
        }

        $per_page = $request->has('per_page') ? $request->per_page : 25;
        $user = \Auth::user();
        $resp = $transactions->whereRaw("(`from`=? AND `type`=?) OR (`to`=? AND `type`=?)", [$user->id, 2, $user->id, 1])
        ->paginate($per_page);

        if (!empty($resp)) {
            return response()->json($resp, 200);
        } else {
            return response()->json(['Error'=>"No virtual currency found for this query"], 404);
        }
    }

    public function unseen(UsersTransactions $transactions)
    {
        $user = \Auth::user();
        $trans = $transactions->whereRaw("((`to`=? AND `type`=?)) AND `unseen`=?", [$user->id, 1, 1])->get();
        return  response()->json(["unseen"=>$trans->count(), "transactions" => $trans]);
    }

    public function setSeen(UsersTransactions $transactions)
    {
        $user = \Auth::user();
        $trans = $transactions->whereRaw("((`to`=? AND `type`=?)) AND `unseen`=?", [$user->id, 1, 1])->get();
        foreach ($trans as $t) {
            $t->unseen = 0;
            $t->save();
        }
        return response()->json(["unseen"=>$trans->count(), "transactions" => $trans]);
    }

    public function store(Request $request)
    {
        $user = \Auth::user();
        $params = [
            'from_id' => $user->id,
        ];
        $request->request->add($params);
        $request->query->add($params);
        $rules = array(
            'from_id' => "integer|required",
            'to_id' => 'integer|required_without:users|different:from_id',
            'amount' => 'numeric|required_with:to_id',
            'users' => 'array|required_without:to_id',
            'users.*.amount' => 'numeric|required_with:users',
            'users.*.id' => 'numeric|required_with:users|different:from_id'
        );

        if ($response = $this->validatorResponse($request->all(), $rules)) {
            return $response;
        }
        \DB::beginTransaction();
        try {
            if ($request->has('users')) {
                $resp = $this->executeMultipleTransactions($request);
            } else {
                $resp = $this->executeSingleTransaction($request);
            }
            \DB::commit();
            return response()->json($resp, !empty($resp['code']) ? $resp['code'] : 200);
        } catch (\Exception $e) {
            $error['code'] = 500;
            $error['message'] = $e->getMessage();
            \DB::rollback();
            return response()->json($error, 500);
        }
    }

    protected function executeSingleTransaction($request)
    {
        $user = array();
        $user['id'] = $request->to_id;
        $user['amount'] = $request->amount;

        $credit = $this->addTransaction($request->from_id, $user, 1, uniqid());
        $debit = $this->addTransaction($request->from_id, $user, 2, uniqid());
        if (!empty($credit['code']) || !empty($debit['code'])) {
            return ["error"=>[$credit, $debit], "code"=>500];
        }
        $from = UsersVCS::where('user_id', $request->from_id)->first();
        $to = UsersVCS::where('user_id', $user['id'])->first();

        $from->amount = $from->amount - $user['amount'];
        $to->amount = $to->amount + $user['amount'];

        if ($from->save() && $to->save()) {
            return ['message'=>"success"];
        }
    }

    protected function executeMultipleTransactions($request)
    {
        foreach ($request->users as $user) {
            $credit = $this->addTransaction($request->from_id, $user, 1, uniqid());
            $debit = $this->addTransaction($request->from_id, $user, 2, uniqid());
            if (!empty($credit['code']) || !empty($debit['code'])) {
                return ["error"=>[$credit, $debit], "code"=>500];
            }

            $from = UsersVCS::where('user_id', $request->from_id)->first();
            $to = UsersVCS::where('user_id', $user['id'])->first();
            $from->amount = $from->amount - $user['amount'];
            $to->amount = $to->amount + $user['amount'];

            $from->save();
            $to->save();
        }
        return ['message'=>"success"];
    }

    protected function addTransaction($id, array $user, $type, $uuid)
    {
        $trans = new UsersTransactions;
        $trans->from = $id;
        $trans->to = $user['id'];
        $trans->amount = $user['amount'];
        $trans->type = $type;
        $trans->flag = 1;
        $trans->group_id = $uuid;
        $trans->save();
        return $trans;
    }

    public function delete($id)
    {
        //Delete item
    }
}
