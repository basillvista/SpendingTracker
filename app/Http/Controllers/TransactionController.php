<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Transcation;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{

    public function __construct(TransactionService $transactionService){
        $this->transactionService=$transactionService;
    }

    public function index()
    {
            $transactions=Transaction::where('user_id', Auth()->user()->id)->get();
            $budget=$this->transactionService->calculateBudget($transactions);

        return view('results', compact('budget', 'transactions'));
    }


    public function create()
    {
        return view('transaction.create');
    }


    public function store(Request $request): View
    {
        $transactions=Transaction::all();
        $budget=$this->transactionService->calculateBudget($transactions);
        return view('results', compact('transactions', 'budget'));
    }

    public function show(Transaction $transaction): View
    {
        return view('transaction.singleTransaction', compact($transaction));
    }


    public function edit(Transaction $transaction): View
    {
        return view('transaction.edit');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();
    }
}
