<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\LoadHandler;

class Product extends Model
{
    use HasFactory, SoftDeletes, LoadHandler;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'hash',
        'description',
        'size',
        'quantity',
        'price',
    ];

    /**
     * The fields that we are going to check on search.
     *
     * @var array|string[]
     */
    public static array $searchFields = [
        'name',
        'description',
    ];


    /**
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
