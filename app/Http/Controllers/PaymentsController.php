<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentsRequest;
use App\Http\Requests\UpdatePaymentsRequest;
use App\Http\Resources\PaymentsResource;
use App\Models\Payments;
use Illuminate\Http\Response;

class PaymentsController extends Controller
{

    /**
     * @OA\Tag(
     *     name="Payments",
     *     description="API Endpoints for Payments"
     * )
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *      path="/api/rest/payments",
     *      operationId="index",
     *      tags={"Payments"},
     *      summary="List all payments",
     *      security={{"bearerAuth": {}}},
     *      description="Returns a list of all payments paginated",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         
     *      ),
     * )
     */
    public function index()
    {
        $payments = Payments::query()->with('payer')->paginate(100);
        return PaymentsResource::collection($payments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @OA\Post(
     *      path="/api/rest/payments",
     *      operationId="store",
     *      tags={"Payments"},
     *      summary="Create a new payment",
     *      description="Creates a new payment record",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"transaction_amount", "installments", "token", "payment_method_id", "payer"},
     *              @OA\Property(property="transaction_amount", type="number", format="float", example=245.90),
     *              @OA\Property(property="installments", type="integer", example=1),
     *              @OA\Property(property="token", type="string", example="ae4e50b2a8f3h6d932c3afg5d6e745g9"),
     *              @OA\Property(property="payment_method_id", type="string", example="master"),
     *              @OA\Property(
     *                  property="payer",
     *                  required={"email", "identification"},
     *                  @OA\Property(property="email", type="string", format="email", example="example_random@gmail.com"),
     *                  @OA\Property(
     *                      property="identification",
     *                      required={"type", "number"},
     *                      @OA\Property(property="type", type="string", example="CPF"),
     *                      @OA\Property(property="number", type="string", example="12345678909")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      )
     * )
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
     *
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *      path="/api/rest/payments/{id}",
     *      operationId="show",
     *      tags={"Payments"},
     *      summary="Display the specified payment",
     *      description="Displays the details of a specific payment",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the payment",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *         
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *         
     *      ),
     * )
     */
    public function show($id)
    {
        $payments = Payments::findOrFail($id);
        $payments->load('payer');
        return new PaymentsResource($payments);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentsRequest  $request
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     * 
     * @OA\Patch(
     *      path="/api/rest/payment/{id}",
     *      operationId="update",
     *      tags={"Payments"},
     *      summary="Update the specified payment",
     *      description="Updates the details of a specific payment, example: PAID, CANCELED, PENDING",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the payment",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     * 
     *         @OA\JsonContent(
     *             required={"status"},
     *            @OA\Property(property="status", type="string", example="PAID")
     *        )
     *         
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *         
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          
     *      ),
     * )
     */
    public function update(UpdatePaymentsRequest $request, $id)
    {
        $data = $request->validated();

        $payments = Payments::findOrFail($id);
        $payments->update($data);
        $payments->load('payer');
        return new PaymentsResource($payments);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payments  $payments
     * @return \Illuminate\Http\Response
     * 
     * @OA\Delete(
     *      path="/api/rest/payments/{id}",
     *      operationId="destroy",
     *      tags={"Payments"},
     *      summary="Delete the specified payment",
     *      description="Deletes a specific payment record",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the payment",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="No Content"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *         
     *      ),
     * )
     */
    public function destroy($id)
    {
        $payments = Payments::findOrFail($id);
        $payments->delete();
        return response()->json([
            'message' => 'Payment deleted successfully',
        ], 204);
    }
}
