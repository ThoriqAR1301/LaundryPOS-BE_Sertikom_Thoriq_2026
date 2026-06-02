<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Service",
 *     required={"id","service_name","price"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="service_name", type="string"),
 *     @OA\Property(property="price", type="number"),
 *     @OA\Property(property="unit", type="string")
 * )
 */

class Service extends Model
{
    protected $fillable = [
        'service_name',
        'price',
        'unit',
    ];
}