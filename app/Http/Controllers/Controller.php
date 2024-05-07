<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Документация API Loan",
 *     description="Проект loan-api создан в рамках тестового задания."
 * )
 * @OA\Server(
 *     description="Сервер",
 *     url="http://164.90.176.53:25500"
 * )
 * @OA\Server(
 *      description="Сервер",
 *      url="http://localhost/"
 *  )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
