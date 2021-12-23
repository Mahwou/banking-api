<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\TransferHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Resource to create new Account
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        if ($request->deposit) {
            $balance = (int) $request->deposit;
        } else {
            $balance = 10000;
        }
        $account = Account::create([
            'name'    => $request->name,
            'user_id' => auth()->user()->id,
            'balance' => $balance
        ]);
        $message = "Account Created Successfully";
        $data = [
            'account' => $account,
            'message' => $message
        ];
        return response()->json([
            'data' => $data,
        ], 200);
    }

    /**
     * Resource to transfer amount to another account
     */
    public function update(Request $request)
    {
        $request->validate([
            'id'         => 'required|int',
            'amount'     => 'required|int',
            'accountNum' => 'required|int'
        ]);
        $id = $request->id;
        $amount = $request->amount;
        $accountNum = $request->accountNum;
        $currentUserAmount = Account::find($id)->balance;
        if ($amount > $currentUserAmount) {
            $history = [];
            $message = "Please refill your Account Insufficient balance";
        } else {
            $newBalance = $currentUserAmount - $amount ;
            $account = Account::where(['id' => $id, 'user_id' => auth()->user()->id])->update([
                'balance' => $newBalance
            ]);

            $receiverCurrentAccount = Account::find($accountNum)->balance;
            $receiverNewBalance = $receiverCurrentAccount + $amount ;
            $account = Account::where(['id' => $accountNum])->update([
                'balance' => $receiverNewBalance
            ]);
            $detail = "You transfered " . $amount . "XFC to account " . $accountNum . " on " . Carbon::now();
            $history = TransferHistory::create([
                'details'    => $detail,
                'account_id' => $id
            ]);
            $message = "Successfully Tranfered";
        }
        return response()->json(['data' => $history, 'message' => $message], 200);
    }

    /**
     * List Balance
     */
    public function balance(Request $request, $id)
    {
        $account = Account::where(['id' => $id, 'user_id' => auth()->user()->id]);
        if ($account->exists()) {
            $history = $account->first()->balance;
            $message = "Current Balance";
            $statusCode = 200;
        } else {
            $statusCode = 422;
            $message = "Unauthorized Access";
        }
        
        return response()->json(['data' => $history, 'message' => $message], $statusCode);
    }

    /**
     * List History
     */
    public function history(Request $request, $id)
    {
        $account = TransferHistory::where(['account_id' => $id]);
        if ($account->exists()) {
            $history = $account->get('details');
            $message = "Transfer History";
            $statusCode = 200;
        } else {
            $statusCode = 404;
            $message = "Not Found";
        }
        
        return response()->json(['data' => $history, 'message' => $message], $statusCode);
    }
}
