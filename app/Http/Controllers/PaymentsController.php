<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentsRequest;
use App\Http\Requests\UpdatePaymentsRequest;
use App\Http\Resources\PaymentsResource;
use App\Models\Payments;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payments::query()->with('payer')->paginate(100);
        return PaymentsResource::collection($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentsRequest $request)
    {
        $data = $request->validated();
        $payment = Payments::create([
            'transaction_amount' => $data['transaction_amount'],
            'installments' => $data['installments'],
            'token' => $data['token'],
            'payment_method_id' => $data['payment_method_id'],
            'notification_url' => env('WEBHOOK_URL'),
            'status' => 'PENDING',
        ]);

        $payment->payer()->create([
            'payment_id' => $payment->id,
            'email' => $data['payer']['email'],
            'identification_type' => $data['payer']['identification']['type'],
            'identification_number' => $data['payer']['identification']['number'],
        ]);

        return new PaymentsResource($payment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments $payments)
    {
        $payments->load('payer');
        return new PaymentsResource($payments);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentsRequest $request, Payments $payments)
    {
        $data = $request->validated();

        $payments->update($data);
        $payments->load('payer');
        return new PaymentsResource($payments);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        $payments->delete();
        return response()->json([
            'message' => 'Payment deleted successfully',
        ], 204);
    }
}
