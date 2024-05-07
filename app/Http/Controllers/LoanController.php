<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="loans",
 *     description="Методы работы c займами",
 * )
 */
class LoanController extends Controller
{
    /**
     * @OA\Get(
     *       path="/api/loans",
     *       tags={"loans"},
     *       summary="Получение списка займов",
     *       @OA\Parameter(in="query", name="date_from"),
     *       @OA\Parameter(in="query", name="date_to"),
     *       @OA\Parameter(in="query", name="amount_debt_upper"),
     *       @OA\Parameter(in="query", name="amount_debt_lower"),
     *       @OA\Response(response=200, description="Successful operation",@OA\JsonContent(ref="#/components/schemas/GetLoansResponse")),
     *       @OA\Response(response=404, description="Resource Not Found"),
     *   )
     * @OA\Schema(
     *         type="string",
     *         schema="GetLoansResponse",
     *         example={"name":"Ivan","second_name":"Ivanov","amount_debt":200,"updated_at": "2000-01-01T10:00:00.000000Z","created_at": "2000-01-01T10:00:00.000000Z","id": 1}
     *     )
     *
     * @return Collection|Builder[]
     */
    public function index(Request $request)
    {
        $query = Loan::query();
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->amount_debt_upper) {
            $query->where('amount_debt','<=', $request->amount_debt_upper);
        }
        if ($request->amount_debt_lower) {
            $query->where('amount_debt','>=', $request->amount_debt_lower);
        }
        return $query->orderBy('id', 'asc')->get();
    }

    /**
     * @OA\Post(
     *      path="/api/loans",
     *      tags={"loans"},
     *      summary="Создание займа",
     *      @OA\RequestBody(required=true, description="Запрос на создание займа", @OA\JsonContent(ref="#/components/schemas/CreateLoanRequest")),
     *      @OA\Response(response=201, description="Successful operation", @OA\JsonContent(ref="#/components/schemas/CreateLoanResponse")),
     *      @OA\Response(response=422, description="Unprocessable Content"),
     *  )
     * @OA\Schema(
     *      type="string",
     *      schema="CreateLoanResponse",
     *      example={"name":"Ivan","second_name":"Ivanov","amount_debt":200}
     *  )
     * @param CreateLoanRequest $request
     * @return mixed
     */
    public function store(CreateLoanRequest $request): mixed
    {
        return Loan::create($request->validated());
    }

    /**
     * @OA\Get(
     *      path="/api/loans/{id}",
     *      tags={"loans"},
     *      summary="Получение отдельного займа",
     *      @OA\Parameter(in="path", name="id", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Successful operation",@OA\JsonContent(ref="#/components/schemas/GetLoanByIdResponse")),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *  )
     * @OA\Schema(
     *        type="string",
     *        schema="GetLoanByIdResponse",
     *        example={"name":"Ivan","second_name":"Ivanov","amount_debt":200,"updated_at": "2000-01-01T10:00:00.000000Z","created_at": "2000-01-01T10:00:00.000000Z","id": 1}
     *    )
     * @param Loan $loan
     * @return Loan
     */
    public function show(Loan $loan): Loan
    {
        return $loan;
    }

    /**
     * @OA\Put(
     *      path="/api/loans/{id}",
     *      tags={"loans"},
     *      summary="Изменение данных займа",
     *      @OA\RequestBody(required=true, description="Запрос на изменение данных займа", @OA\JsonContent(ref="#/components/schemas/UpdateLoanRequest")),
     *      @OA\Parameter(in="path", name="id", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Successful operation",@OA\JsonContent(ref="#/components/schemas/UpdateLoanResponse")),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *  )
     * @OA\Schema(
     *       type="string",
     *       schema="UpdateLoanResponse",
     *       example={"name":"Alex","second_name":"Sidorov","amount_debt":777,"updated_at": "2000-01-22T22:22:22.220000Z","created_at": "2000-01-01T10:00:00.000000Z","id": 1}
     *   )
     * @param UpdateLoanRequest $request
     * @param Loan $loan
     *
     */
    public function update(UpdateLoanRequest $request, Loan $loan)
    {
        $loan->update($request->validated());
        return $loan->fresh();
    }

    /**
     * @OA\Delete(
     *      path="/api/loans/{id}",
     *      tags={"loans"},
     *      summary="Удаление займа",
     *      @OA\Parameter(in="path", name="id", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Successful operation",@OA\JsonContent(ref="#/components/schemas/DeleteLoanResponse")),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     * @OA\Schema(
     *        type="string",
     *        schema="DeleteLoanResponse",
     *        example={"success":true}
     *    )
     * @param Loan $loan
     * @return array
     */
    public function destroy(Loan $loan): array
    {
        $loan->delete();
        return ['success' => true];
    }
}
