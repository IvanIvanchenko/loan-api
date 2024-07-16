<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\RequestBody(
 *     request="UpdateLoanRequest",
 *     required=true,
 *     description="Запрос на обновление займа",
 *     @OA\JsonContent(ref="#/components/schemas/UpdateLoanRequest")
 * )
 */
class UpdateLoanRequest extends FormRequest
{
    /**
     * @OA\Schema(
     *     type="string",
     *     schema="UpdateLoanRequest",
     *     example={"name":"Alex","second_name":"Sidorov","amount_debt":777}
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
