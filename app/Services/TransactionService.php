<?php

namespace App\Services;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{
    public int $budget;

    public function storeUser(TransactionRequest $request): void
    {
        $input=$request->only('status','job', 'income','description');
        $transaction=new Transaction();
        $transaction->status=$input['status'];
        $transaction->job=$input['job'];
        $transaction->description=$input['description'] ?? NULL;
        $transaction->income=$input['income'];
        $transaction->user_id=Auth()->user()->id;
        $transaction->save();
    }

    public function calculateBudget(Transaction|Collection $transactions): int{
        $budget=0;
        foreach($transactions as $transaction){
            if($transaction['status'] === 'outflow'){
                $budget=$budget-=$transaction['income'];
            } else{
                $budget=$budget+=$transaction['income'];
            }
        }
        return $budget;
    }

    //function to count number of transcations today, in a month, in a year, make tests, make request;
}
