<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\RequestBody(
 *     request="CreateLoanRequest",
 *     required=true,
 *     description="Запрос на создание займа",
 *     @OA\JsonContent(ref="#/components/schemas/CreateLoanRequest")
 * )
 */
class CreateLoanRequest extends FormRequest
{
    /**
     * @OA\Schema(
     *     type="string",
     *     schema="CreateLoanRequest",
     *     example={"name":"Ivan","second_name":"Ivanov","amount_debt":200}
     * )
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'second_name' => ['string', 'required'],
            'amount_debt' => ['numeric', 'required'],
        ];
    }
}
