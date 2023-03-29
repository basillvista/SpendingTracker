<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{
    public int $budget;

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
