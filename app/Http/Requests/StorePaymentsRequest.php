<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePaymentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_amount' => 'required|numeric|min:1',
            'installments' => 'required|integer|min:1',
            'token' => 'required|string|unique:payments,token',
            'payment_method_id' => 'required|string',
            'payer.email' => 'required|email',
            'payer.identification.type' => 'required|string',
            'payer.identification.number' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'transaction_amount.required' => 'The transaction amount is required',
            'transaction_amount.numeric' => 'The transaction amount must be a number',
            'transaction_amount.min' => 'The transaction amount must be greater than 0',
            'installments.required' => 'The installments are required',
            'installments.integer' => 'The installments must be an integer',
            'installments.min' => 'The installments must be greater than 0',
            'token.required' => 'The token is required',
            'token.string' => 'The token must be a string',
            'token.unique' => 'The token must be unique',
            'payment_method_id.required' => 'The payment method id is required',
            'payment_method_id.string' => 'The payment method id must be a string',
            'payer.email.required' => 'The payer email is required',
            'payer.email.email' => 'The payer email must be a valid email',
            'payer.identification.type.required' => 'The payer identification type is required',
            'payer.identification.type.string' => 'The payer identification type must be a string',
            'payer.identification.number.required' => 'The payer identification number is required',
            'payer.identification.number.string' => 'The payer identification number must be a string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
  
}
