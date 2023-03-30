<?php

namespace Tests\Feature;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;

use Tests\TestCase;

class TransactionTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     *
     *
     *
     */
    public function test_index()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id'=>$user->id]);
        $this->actingAs($user);
        $response = $this->get(route('transaction.index', ['transaction'=>$transaction]));
        $response->assertStatus(200);
        $response->assertViewIs('transaction.index');
    }

    public function test_create()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id'=>$user->id]);
        $this->actingAs($user);
        $response = $this->get(route('transaction.create'));
        $response->assertStatus(200);
        $response->assertViewIs('transaction.create');
    }

    public function test_store()
    {
        $transactionService = new TransactionService();
        $user = User::factory()->create();
        $this->actingAs($user);
        $request = new TransactionRequest([
            'status' => 'income',
            'task' => 'Buy groceries',
            'value' => 50,
            'description' => 'Grocery shopping'
        ]);
        $transactionService->storeUser($request);
        $this->assertDatabaseHas('transactions', [
            'status' => 'income',
            'task' => 'Buy groceries',
            'value' => 50.0,
            'description' => 'Grocery shopping'
        ]);
        $response = $this->actingAs($user)->post(route('transaction.store'));
        $response->assertStatus(302);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id'=>$user->id]);
        $this->actingAs($user);
        $response = $this->get(route('transaction.show', ['transaction'=>$transaction]));
        $response->assertStatus(200);
    }

    public function test_edit()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id'=>$user->id]);
        $this->actingAs($user);
        $response = $this->get(route('transaction.edit', ['transaction'=>$transaction]));
        $response->assertStatus(200);
        $response->assertViewIs('transaction.edit');
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id'=>$user->id]);
        $this->actingAs($user);
        $request = [
            'status' => 'income',
            'task' => 'Buy groceries',
            'value' => 50,
            'description' => 'Grocery shopping'
        ];
        $user->update($request);
        $response = $this->actingAs($user)->put(route('transaction.update', ['transaction'=>$transaction]));
        $response->assertStatus(302);
    }

    public function test_delete()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id'=>$user->id]);
        $this->actingAs($user);
        $response = $this->actingAs($user)->delete(route('transaction.destroy', ['transaction'=>$transaction]));
        $response->assertStatus(302);
        $response->assertRedirect(route('transaction.index'));
    }

    public function test_user_not_authorized()
    {
        $response = $this->get(route('transaction.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_authorized_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('transaction.index'));
        $response->assertStatus(200);
    }

    public function test_calculate_budget()
    {
        $transactionService = new TransactionService();
        $user = User::factory()->create();
        $transactions = Transaction::factory(3)->create();
        $this->actingAs($user);
        $budget = $transactionService->calculateBudget($transactions);
        $calculateBudget = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->status === 'outflow') {
                $calculateBudget -= $transaction->value;
            } else {
                $calculateBudget += $transaction->value;
            }
        }
        $this->assertEquals($calculateBudget, $budget);
    }
}
