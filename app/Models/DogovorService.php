<?php

namespace App\Models;

/**
 * App\Models\DogovorService
 *
 * @property INT $DL_KEY
 * @property string $DL_NAME
 * @property string $DL_DGCOD
 * @property string $DL_TURDATE
 * @property int $DL_PAKETKEY
 * @property int $DL_TRKEY
 * @property int $DL_SVKEY
 * @property int $DL_DAY
 * @property string $DL_CODE
 * @property int $DL_SUBCODE1
 * @property int $DL_SUBCODE2
 * @property int $DL_NMEN
 * @property int $DL_NDAYS
 * @property int $DL_CNKEY
 * @property int $DL_CTKEY
 * @property int $DL_PARTNERKEY
 * @property int $DL_COST
 * @property int $DL_BRUTTO
 * @property int $DL_PAYED
 * @property int $DL_CONTROL
 * @property int $DL_CREATOR
 * @property int $DL_ATTRIBUTE
 * @property int $DL_ISPAYED
 * @property int $DL_DATEBEG
 * @property int $DL_DATEEND
 * @property int $DL_DGKEY
 * @property int $DL_NameLat
 * @property int $DL_PRTDOGKEY
 * @property int $DL_Long
 * @property int $DL_IsDeleted
 *
 * @property Dogovor $dogovor
 * @property DogovorServicePenalty[] $penalties
 *
 */

class DogovorService extends BaseModel
{
    protected $primaryKey = 'DL_KEY';
    protected $table = 'tbl_dogovorlist';
    protected $dates = ['DL_DATEBEG', 'DL_DATEEND'];

    /**
     * Получить догвор
     */
    public function dogovor()
    {
        return $this->belongsTo(Dogovor::class, 'DL_DGKEY', 'DG_CODE');
    }

    /**
     * Получить информаицю по штрафам
     */
    public function penalties(){
        return $this->hasMany(DogovorServicePenalty::class, 'DLP_DLKey', 'DL_KEY');
    }

    /**
     * @return Service
     */
    public function getServiceInfo(){
        return new Service([
            'svkey' => $this->DL_SVKEY,
            'code' => $this->DL_CODE,
            'date_begin' => $this->DL_DATEBEG->toDateString(), //Можно и $oFromDate но тогда припишет текущее время сука
            'date_end' => $this->DL_DATEEND->toDateString(),
            'subcode1' => $this->DL_SUBCODE1,
            'subcode2' => $this->DL_SUBCODE2,
            'nmen' => $this->DL_NMEN
        ]);
    }
/*
    protected $appends = ['id', 'name'];
    public function getIdAttribute(){
        return $this->attributes['CT_KEY'];
    }

    public function getNameAttribute(){
        return $this->attributes['CT_NAME'];
    }
*/
}
