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
 *     description="Проект loan-api"
 * )
 * @OA\Server(
 *     description="Сервер",
 *     url="http://167.71.46.215:25500"
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
