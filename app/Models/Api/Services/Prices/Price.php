<?php
namespace App\Models\api\services\prices;
use App\Models\BaseModel;
/**
 * App\Models\Api\Services\Prices\Price
 *
 * @property integer $nCode
 * @property integer $nCode1
 * @property integer $nCode2
 * @property integer $nPrKey
 * @property integer $nPkKey
 * @property integer $nPrice
 * @property integer $nTotalDays
 */
class Price extends BaseModel
{
    protected $fillable  = ['nCode', 'nCode1', 'nCode2', 'nPrKey', 'nPkKey', 'nPrice', 'nTotalDays'];
}
