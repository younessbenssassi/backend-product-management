<?php

namespace App\Http\Controllers\API\Modules;


use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Product;
use Exception;

class DashboardController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $content['todayProductsCount'] = Product::where('user_id',Auth::user()->id)
                ->where('created_at', '>=', Carbon::today())
                ->count();

            $content['allProductsCount'] = Product::where('user_id',Auth::user()->id)
                ->count();

            return $this->sendResponse(['content'=> $content], 'Products count loaded successfully.');

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

    }

}
