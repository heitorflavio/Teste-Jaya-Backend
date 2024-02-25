<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\PaymentsController;
use App\Models\Payments;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class PaymentsTest extends TestCase
{
    use WithFaker , RefreshDatabase;


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
        $response = json_decode($response);
        
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

        $payment = Payments::create($data);
        $dt = Payments::find($payment->id);
        $this->assertEquals($payment['id'], $dt->id);
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
        $result = Payments::find($payment->id);
        $result->status = 'PAID';
        $result->save();
        $this->assertEquals('PAID', $result->status);
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

        $controller = new PaymentsController();
        $response = $controller->destroy($payment->id);
        $this->assertEquals(204, $response->status());
    }

    
}
