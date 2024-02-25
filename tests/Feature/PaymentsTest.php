<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Payments;
use App\Models\User;
use Illuminate\Support\Str;


class PaymentsTest extends TestCase
{
    use WithFaker;

    /**
     * @var string
     */
    public static $token;

    /**
     * @var array
     */
    public static $payment;


    /** @test */
    public function test_can_login()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/rest/login', ['email' => 'test@example.com', 'password' => 'password']);
        self::$token = $response->json()['token'];
        $response->assertStatus(200);
    }

    /** @test 
     *  @depends test_can_login
     */
    public function test_can_list_all_payments()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . self::$token])->get('/api/rest/payments');
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
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . self::$token])->postJson('/api/rest/payments', $paymentData);
        self::$payment = $response->json();
        $response->assertStatus(201);
    }

    /** @test */
    public function test_can_show_a_specific_payment()
    {
        $response =  $this->withHeaders(['Authorization' => 'Bearer ' . self::$token])->get("/api/rest/payments/" . self::$payment['data']['id']);
        $response->assertStatus(200);
    }

    /** @test */
    public function test_can_update_a_specific_payment()
    {
        $updatedData = [
            'status' => 'PAID'
        ];
        $response =  $this->withHeaders(['Authorization' => 'Bearer ' . self::$token])
        ->patchJson("/api/rest/payment/" . self::$payment['data']['id'], $updatedData);
        $response->assertStatus(200);
    }

    /** @test */
    public function test_can_delete_a_specific_payment()
    {
        $response =  $this->withHeaders(['Authorization' => 'Bearer ' . self::$token])
        ->delete("/api/rest/payments/".self::$payment['data']['id']);
        $response->assertStatus(204);
    }
}
