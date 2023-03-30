<?php

namespace App\Services;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use \Illuminate\Support\Collection;

class TransactionService
{


    public function storeUser(TransactionRequest $request): void
    {
        $transaction = new Transaction();
        $transaction->status = $request->status;
        $transaction->task = $request->task;
        $transaction->description = $request->description;
        $transaction->value = $request->value;
        $transaction->user_id = Auth()->user()->id;
        $transaction->save();
    }

    public function calculateBudget(Transaction|Collection $transactions): int
    {
        $budget=0;
        foreach ($transactions as $transaction) {
            if ($transaction['status'] === 'outflow') {
                $budget=$budget-=$transaction['value'];
            } else {
                $budget=$budget+=$transaction['value'];
            }
        }
        return $budget;
    }
}
