<?php

namespace App\Models;
use App\Helpers\CommonHelper;
use Carbon\Carbon;

/**
 * App\Models\History
 *
 * @property int $HI_ID
 * @property string $HI_DGCOD
 * @property string $HI_MOD
 * @property \Carbon\Carbon $HI_DATE
 * @property string $HI_TEXT
 * @property string $HI_TextLat
 * @property string $HI_REMARK
 * @property string $HI_WHO
 * @property string $HI_HOST
 * @property int $HI_DGKEY
 * @property int $HI_MessEnabled
 * @property int $HI_Invisible
 *
 * @property Dogovor $dogovor
 */

class History extends BaseModel
{
    protected $primaryKey = 'HI_ID';
    protected $table = 'History';
    protected $dates = ['HI_DATE'];
    /**
     * Получить догвор
     */
    public function dogovor()
    {
        return $this->belongsTo(Dogovor::class, 'HI_DGCOD', 'DG_CODE');
    }

    public function save(array $options = array())
    {
        $aParams = self::toArray();
        $aParams['HI_TEXT'] = $aParams['HI_TEXT'];
        $aParams['HI_MOD'] = isset($aParams['HI_MOD']) ? $aParams['HI_MOD'] : 'WWW';
        $aParams['HI_MessEnabled'] = isset($aParams['HI_MessEnabled']) ? $aParams['HI_MessEnabled'] : 1;
        return \DB::statement(
            self::query_prepare("
                EXEC dbo.wrapper_InsHistory @dogovor = :HI_DGCOD,
	                                        @mode = :HI_MOD ,
                                            @text = :HI_TEXT,
                                            @remark  = :HI_REMARK,
                                            @message_enabled = :HI_MessEnabled",
                $aParams
            )
        );
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
