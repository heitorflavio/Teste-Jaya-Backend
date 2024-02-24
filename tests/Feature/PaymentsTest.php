<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Payments;
use Illuminate\Support\Str;

class PaymentsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    
    /** @test */
    public function test_can_list_all_payments()
    {
        $response = $this->get('/api/rest/payments');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_can_create_a_new_payment()
    {
        $paymentData = [
            'transaction_amount' => 100.00,
            'installments' => 1,
            'token' => 'token_value',
            'payment_method_id' => 'payment_method_id_value',
            'payer' => [
                'email' => 'test@example.com',
                'identification' => [
                    'type' => 'CPF',
                    'number' => '12345678901'
                ]
            ]
        ];
        $response = $this->postJson('/api/rest/payments', $paymentData);
        $response->assertStatus(201);
    }

    /** @test */
    public function test_can_show_a_specific_payment()
    {

        $payment = Payments::create(
            [
                'transaction_amount' => $this->faker()->randomFloat(2, 1, 1000),
                'status' => 'PENDING',
                'installments' =>  $this->faker()->numberBetween(1, 12),
                'token' => Str::random(10),
                'payment_method_id' => 'master',
                'notification_url' => env('WEBHOOK_URL'),
            ]
        );
        $payment->payer()->create(
            [
                'payment_id' => $payment->id,
                'email' =>  $this->faker()->email,
                'identification_type' => 'CPF',
                'identification_number' =>  $this->faker()->randomNumber(9),
            ]
        );
        $response = $this->get("/api/rest/payments/{$payment->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function test_can_update_a_specific_payment()
    {
        $payment = Payments::create(
            [
                'transaction_amount' => $this->faker()->randomFloat(2, 1, 1000),
                'status' => 'PENDING',
                'installments' =>  $this->faker()->numberBetween(1, 12),
                'token' => Str::random(10),
                'payment_method_id' => 'master',
                'notification_url' => env('WEBHOOK_URL'),
            ]
        );
        $payment->payer()->create(
            [
                'payment_id' => $payment->id,
                'email' =>  $this->faker()->email,
                'identification_type' => 'CPF',
                'identification_number' =>  $this->faker()->randomNumber(9),
            ]
        );
        $updatedData = [
            'status' => 'PAID'
        ];
        $response = $this->patchJson("/api/rest/payment/{$payment->id}", $updatedData);
        $response->assertStatus(200);
    }

    /** @test */
    public function test_can_delete_a_specific_payment()
    {
        $payment = Payments::create(
            [
                'transaction_amount' => $this->faker()->randomFloat(2, 1, 1000),
                'status' => 'PENDING',
                'installments' =>  $this->faker()->numberBetween(1, 12),
                'token' => Str::random(10),
                'payment_method_id' => 'master',
                'notification_url' => env('WEBHOOK_URL'),
            ]
        );
        $payment->payer()->create(
            [
                'payment_id' => $payment->id,
                'email' =>  $this->faker()->email,
                'identification_type' => 'CPF',
                'identification_number' =>  $this->faker()->randomNumber(9),
            ]
        );
        $response = $this->delete("/api/rest/payments/{$payment->id}");
        $response->assertStatus(204);
    }
}
