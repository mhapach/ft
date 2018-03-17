<?php

namespace App\Models;
use Carbon\Carbon;

/**
 * App\Models\BasketItem
 *
 * @property int $id
 * @property int $basket_id - required
 * @property \Carbon\Carbon $date_begin - required
 * @property string $remark
 * @property string $contact_name
 * @property string $phone
 * @property string $email
 * @property int $discount_reason_id
 * @property string $discount_size
 * @property string $is_public
 * @property int $prkey
 * @property float $counted_price
 * @property int $uskey
 * @property string $creation_date
 * @property int $site_id
 * @property string $currency
 * @property string created_at
 * @property string updated_at
 * @property BasketItemService[] basketItemServices
 * @property Basket basket
 * @property BasketItemTourist[] basketItemTourist
 */

class BasketItem extends BaseModel
{
    protected $table = 'tb_basket_items';
    protected $dates = ['date_begin', 'created_at', 'updated_at'];
    /**
     * Получить  корзину
     */
    public function basket()
    {
        return $this->belongsTo(Basket::class, 'basket_id', 'id');
    }

    /**
     * Получить услуги корзине
     */
    public function basketItemServices()
    {
        return $this->hasMany(BasketItemService::class, 'basket_item_id', 'id');
    }

    /**
     * Получить туристов корзине
     */
    public function basketItemTourists()
    {
        return $this->hasMany(BasketItemTourist::class, 'basket_item_id', 'id');
    }

    /**
     * @param Service $oService
     * @param int $is_disabled - необязательная услуга
     * @return BasketItemService
     */
    public function insertService( Service $oService, $is_disabled = 0 ){

        $aItemService = [
            'basket_item_id' => $this->id,
            'tsday'          => 1,
            'attribute'      => 415,
            'name'           => $oService->getName(),
            'brutto'         => $oService->getPriceBrutto(),
            'is_disabled'    => $is_disabled
        ];
        $aItemService = array_merge($aItemService, $oService->toArray());
        return BasketItemService::create($aItemService);
    }

    /**
     * @return array - [ DOGOVOR => zxzzz, EMAIL => zzzz ]
     */
    public function createDogovor(){
        $sql = self::query_prepare("
                EXEC xml_gate_basket_insert_dogovor @session_id  = :sSession, @item_id = :nItemId
            ",
            ['sSession'=>$this->basket->session_id, 'nItemId' => $this->id]
        );
        return \DB::selectOne($sql);
    }
}

