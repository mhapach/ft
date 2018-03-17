<?php

namespace App\Models;

use App\Models\Service;
/**
 * App\Models\Trasnfer
 *
 * @property string $TF_KEY
 * @property string $TF_NAME
 * @property string $TF_NAMELAT
 * @property \App\Models\City $city
 */
class Transfer extends BaseModel
{
    protected $table = 'transfer';

    /**
     * Получить Город
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'TF_CTKEY', 'CT_KEY');
    }

}
