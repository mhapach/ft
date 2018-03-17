<?php

namespace App\Models;
/**
 * App\Models\Basket
 *
 * @property INT $id
 * @property string(256) session_id
 * @property string created_at
 * @property string updated_at
 */

class Basket extends BaseModel
{
    protected $table = 'tb_basket';

    /**
     * Получить items корзины
     */
    public function basketItems()
    {
        return $this->hasMany(BasketItem::class, 'basket_id', 'id');
    }
}

