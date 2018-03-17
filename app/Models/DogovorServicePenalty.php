<?php

namespace App\Models;

/**
 * App\Models\DogovorServicePenalty
 *
 * @property int $DLP_ID
 * @property int $DLP_DLKey
 * @property int $DLP_LGID
 * @property float $DLP_Value
 * @property float $DLP_ValueProc
 * @property int $DLP_ValueSum
 * @property int $DLP_Release

 * @property DogovorService $service
 */

class DogovorServicePenalty extends BaseModel
{
    protected $primaryKey = 'DLP_ID';
    protected $table = 'DogovorListPenalties';

    /**
     * Получить Страну
     */
    public function service()
    {
        return $this->belongsTo(DogovorService::class, 'DLP_DLKey', 'DL_KEY');
    }

}
