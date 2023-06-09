<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait LoadHandler
{
    /**
     * handle load with search && pagination
     * @param $query
     * @param  Request  $request
     * @param  int  $limit
     * @return mixed
     */
    public function scopeLoadWithParams(
        $query,
        Request &$request,
        int $limit = 20,

    ): mixed {
        $search = $request->get('search');
        $page = $request->integer('page', 1);

        $offset = $page > 1 ? $page * $limit : 0;

        return $query->when(!is_null($search) && count(self::$searchFields) > 0,
                function ($q) use ($search) {
                    for ($i = 0; $i < count(self::$searchFields); $i++) {
                        if ($i > 0)
                            $q = $q->orWhere(self::$searchFields[$i], 'like', "%{$search}%");
                         else
                            $q = $q->where(self::$searchFields[$i], 'like', "%{$search}%");
                    }
                    return $q;
                }
            )
            ->offset($offset)
            ->limit($limit);
    }

    /**
     * @param $query
     * @param  string  $sortBy
     * @param  string  $sort
     * @return mixed
     */
    public function scopeSort($query, string $sort = 'DESC', string $sortBy = 'id'): mixed
    {
        return $query->orderBy($sortBy, $sort);
    }

    /**
     * handle load with search && pagination
     * @param $query
     * @param  Request  $request
     * @return mixed
     */
    public function scopeApplyFilters(
        $query,
        Request &$request,

    ): mixed {
        $createdToday = $request->boolean('createdToday',false);
        $soldOut = $request->boolean('soldOut',false);
        $priceMoreThan = $request->integer('priceMoreThan',0);
        $priceLessThan = $request->integer('priceLessThan',0);

        return $query->when($createdToday,function ($q){
                    $q->where('created_at', '>=', Carbon::today());
                })
                ->when($soldOut,function ($q){
                    $q->where('quantity','=',0);
                })
                ->when($priceMoreThan > 0,function ($q) use($priceMoreThan){
                    $q->where('price', '>=', $priceMoreThan);
                })
                ->when($priceLessThan > 0,function ($q) use($priceLessThan){
                    $q->where('price', '<=', $priceLessThan);
                });

    }

}
