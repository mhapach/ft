<?php

namespace App\Models;
/**
 * App\Models\Room
 *
 * @property string $HR_KEY
 * @property Room room
 * @property RoomCategory roomCategory
 * @property Accmd accmd
 */
class HotelRoom extends BaseModel
{
    protected $table = 'HotelRooms';
    protected $primaryKey = 'HR_KEY';
    /**
     * Получить комнату
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'HR_RMKEY', 'RM_KEY');
    }

    /**
     * Получить категорию комнаты
     */
    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'HR_RCKEY', 'RC_KEY');
    }

    /**
     * Получить размщение
     */
    public function accmd()
    {
        return $this->belongsTo(Accmd::class, 'HR_ACKEY', 'AC_KEY');
    }

}
