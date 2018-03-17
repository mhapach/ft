<?php

namespace App\Models;
/**
 * App\Models\BasketItemTourist
 *
 * @property int $id
 * @property int $basket_item_id
 * @property int $accmd_id
 * @property int $sex_id
 * @property int $realsex_id
 * @property string $last_name
 * @property string $first_name
 * @property string $second_name
 * @property string $paspser
 * @property string $paspnum
 * @property string $paspactual
 * @property \Carbon\Carbon $birthdate
 * @property string $citizen
 * @property int $tu_key - Id Туриста в мегатековской таблице turist
 * @property int $subcode1
 * @property string created_at
 * @property string updated_at
 */

class BasketItemTourist extends BaseModel
{
    protected $table = 'tb_baskettourists';
    /**
     * Получить корзину
     */
    public function basketItem()
    {
        return $this->belongsTo(BasketItem::class, 'basket_item_id', 'id');
    }

    public function basketItemService()
    {
        return $this->belongsToMany(BasketItemService::class, 'tb_item_service_tourists', 'itemtourist_id', 'itemservice_id');
    }

}

