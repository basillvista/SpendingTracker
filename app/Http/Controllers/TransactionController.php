<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
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

        return view('transaction.index', compact('budget', 'transactions'));
    }

    public function create()
    {
        return view('transaction.create');
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        $input=$request->only('status','job', 'income','description');
        $transaction=new Transaction();
        $transaction->status=$input['status'];
        $transaction->job=$input['job'];
        $transaction->description=$input['description'] ?? NULL;
        $transaction->income=$input['income'];
        $transaction->user_id=Auth()->user()->id;
        $transaction->save();

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
        $input=$request->only('status','job', 'income','description');
        $transaction->update(['status'=>$request['status'], 'job'=>$request['job'], 'description'=>$request['description'], 'income'=>$request['income']]);
        return redirect()->route('transaction.index');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();
        return redirect()->route('transaction.index');
    }
}
