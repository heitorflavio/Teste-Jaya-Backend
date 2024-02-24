<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\PaymentsController;
use App\Models\Payments;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class PaymentsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    public function testIndex()
    {
        for ($i = 0; $i < 5; $i++) {

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
        }

        $controller = new PaymentsController();
        $response = $controller->index()->toJson();
        $response = json_decode($response, true);
        
        $this->assertCount(5, $response);
    }

    public function testShow()
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

        $controller = new PaymentsController();
        $response = $controller->show($payment->id)->toJson();

        $response = json_decode($response, true);
        $this->assertEquals($payment->id, $response['id']);
       
    }


    public function testStore()
    {
        $data = [
            'transaction_amount' => $this->faker()->randomFloat(2, 1, 1000),
            'installments' =>  $this->faker()->numberBetween(1, 12),
            'token' => Str::random(10),
            'payment_method_id' => 'master',
            'notification_url' => env('WEBHOOK_URL'),
            'payer' => [
                'email' =>  $this->faker()->email,
                'identification' => [
                    'type' => 'CPF',
                    'number' =>  (string) $this->faker()->randomNumber(9),
                ]
            ]
        ];

        $response = $this->post('/api/rest/payments', $data);
        $response->assertStatus(201);
    }

    public function testUpdateStatus(){
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

        $response = $this->patch('/api/rest/payment/'.$payment->id, ['status' => 'PAID']);
        $response->assertStatus(200);
        $payment = Payments::find($payment->id);
        $this->assertEquals('PAID', $payment->status);
    }

    public function testDelete(){
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

        $response = $this->delete('/api/rest/payments/'.$payment->id);
        $response->assertStatus(204);
        $payment = Payments::find($payment->id);
        $this->assertNull($payment);
    }

    
}
