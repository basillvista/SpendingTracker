<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService=$transactionService;
    }

    public function index(): View
    {
            $transactions=Transaction::where('user_id', Auth()->user()->id)->get();
            $budget=$this->transactionService->calculateBudget($transactions);
        return view('transaction.index', compact('budget', 'transactions'));
    }

    public function create(): View
    {
        return view('transaction.create');
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
            $this->transactionService->storeUser($request);
            return redirect()->route('transaction.index');
    }

    public function show(Transaction $transaction): View
    {
        return view('transaction.show', compact('transaction'));
    }


    public function edit(Transaction $transaction): View
    {
        return view('transaction.edit', compact('transaction'));
    }

    public function update(TransactionRequest $request, Transaction $transaction): RedirectResponse
    {
            $input=$request->only('status', 'task', 'value', 'description');
            $transaction->update(['status'=>$request['status'], 'task'=>$request['task'], 'description'=>$request['description'], 'value'=>$request['value']]);
            return redirect()->route('transaction.index');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();
        return redirect()->route('transaction.index');
    }
}
