<?php
namespace App\Models\api\services\prices;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Pansion;
use App\Models\RoomCategory;

/**
 * App\Models\Api\Services\Prices\HotelPrice
 *
 * @property integer $nRmKey
 * @property integer $nRcKey
 * @property integer $nAcKey
 * @property integer $nPrice
 * @property integer $nExtraBedCode1
 * @property integer $nExtraBedPrice
 * @property \App\Models\Hotel $hotel
 * @property \App\Models\Room $room
 * @property \App\Models\Pansion $pansion
 * @property \App\Models\RoomCategory $room_category
 */
class HotelPrice extends Price
{
    protected $appends = ['nRmKey', 'nRcKey', 'nAcKey', 'nPrice', 'nExtraBedCode1', 'nExtraBedPrice'];

    /**
     * Получить отель
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'nCode', 'HD_KEY');
    }

    /**
     * Получить комнату
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'nRmKey', 'RM_KEY');
    }


    /**
     * Получить Питание
     */
    public function pansion()
    {
        return $this->belongsTo(Pansion::class, 'nCode2', 'PN_KEY');
    }

    /**
     * Получить категорию комнаты
     */
    public function room_category()
    {
        return $this->belongsTo(RoomCategory::class, 'nRcKey', 'RC_KEY');
    }


//    public function getHotelNameAttribute(){
//        return $this->attributes['HD_NAME'];
//    }
    /*
        public function getNameAttribute(){
            return $this->attributes['HD_NAME'];
        }
    */
}
