<?php

namespace App\Models;

//use Faker\Provider\Base;
//use App\Models\Service;
//use Faker\Provider\DateTime;

/**
 * App\Models\BasketItemService
 *
 * @property int $id
 * @property int $basket_item_id
 * @property int $svkey -required
 * @property int $code - required
 * @property int $subcode1 - required
 * @property int $subcode2
 * @property int $prkey - required
 * @property int $tsday - required
 * @property \Carbon\Carbon $date_begin - required
 * @property \Carbon\Carbon $date_end - required
 * @property int $pkkey - required
 * @property int $tskey - Id Туриста в мегатековской таблице turservice
 * @property int $netto	STANDARD BEACH BUNGALOW
 * @property int $brutto
 * @property int $discount
 * @property string $name
 * @property int $nmen
 * @property int $attribute - Атрибуты в МТ
 * @property string created_at
 * @property string updated_at
 * @property int is_disabled - включена услуга в обсчет или нет . Если нет то в корзине не считаем
 *
 */

class BasketItemService extends Service
{
    protected $table = 'tb_touristservices';

    protected $fillable = [
        'basket_item_id', 'svkey', 'code', 'subcode1', 'subcode2', 'tsday', 'date_begin', 'date_end', 'prkey', 'pkkey', 'attribute', 'name', 'brutto', 'nmen', 'cnkey', 'ctkey', 'is_disabled'
    ];
    protected $dates = ['date_begin','date_end', 'created_at', 'updated_at'];
    /**
     * Получить item корзины
     */
    public function basketItem()
    {
        return $this->belongsTo(BasketItem::class, 'basket_item_id', 'id');
    }

    public function basketItemTourist()
    {
        return $this->belongsToMany(BasketItemTourist::class, 'tb_item_service_tourists', 'itemservice_id', 'itemtourist_id');
    }
}

