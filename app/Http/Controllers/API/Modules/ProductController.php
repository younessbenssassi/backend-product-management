<?php

namespace App\Http\Controllers\API\Modules;


use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $products = Product::where('user_id',Auth::user()->id)
                ->loadWithParams($request)
                ->applyFilters($request)
                ->sort()
                ->get();

            return $this->sendResponse(['products'=> $products], 'Products loaded successfully.');

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

    }

    /**
     * @param Request $request
     * @param $hash
     * @return JsonResponse
     */
    public function show(Request $request,$hash): JsonResponse
    {
        try {
            $product = Product::where('hash',$hash)->first();

            if(is_null($product))
                return $this->sendError('Product not found');

            return $this->sendResponse(['product'=> $product], 'Product loaded successfully.');

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        try {
            $product = null;
            DB::transaction(function () use (&$request, &$product) {
                $product = Product::create(
                    array_merge(
                        $request->all(),
                        [
                            'user_id' => Auth::user()->id,
                            'hash'=> Str::random(40),
                        ]
                    )
                );
            });
            return $this->sendResponse(['product'=> $product], 'Product created successfully.');

        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

    }

    /**
     * @param ProductRequest $request
     * @param $hash
     * @return JsonResponse
     */
    public function update(ProductRequest $request,$hash): JsonResponse
    {
        try {
            $product = Product::where('hash',$hash)->first();

            if(is_null($product))
                return $this->sendError('Product not found');

            DB::transaction(function ()use (&$product , &$request) {
                $product->update($request->except('hash','user_id'));
            });

            return $this->sendResponse(['product'=> $product], 'Product updated successfully.');

        }
        catch (Exception $e){
            return $this->sendError($e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @param $hash
     * @return JsonResponse
     */
    public function destroy(Request $request,$hash): JsonResponse
    {
        try {
            $product = Product::where('hash',$hash)->first();

            if(is_null($product))
                return $this->sendError('Product not found');

            DB::transaction(function ()use (&$product) {
                $product->delete();
            });

            return $this->sendResponse([], 'Product deleted successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
