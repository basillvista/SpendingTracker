<?php

namespace App\Services;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionService
{


    public function storeUser(TransactionRequest $request): void
    {
        $transaction = new Transaction;
        $transaction->status = $request->status;
        $transaction->task = $request->task;
        $transaction->description = $request->description;
        $transaction->value = $request->value;
        $transaction->user_id = Auth()->user()->id;
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


}
