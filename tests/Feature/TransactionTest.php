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
        $transactionService = new TransactionService();
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id'=>$user->id]);
        $this->actingAs($user);
//        $budget = $transactionService->calculateBudget($transaction);
//        $this->assertGreaterThanOrEqual(0, $budget);
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
            'status' => 'completed',
            'task' => 'Buy groceries',
            'value' => 50,
            'description' => 'Grocery shopping'
        ]);
        $transactionService->storeUser($request);
        $this->assertDatabaseHas('transactions', [
            'status' => 'completed',
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
            'status' => 'completed',
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
}
