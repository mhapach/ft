<?php

namespace App\Models;
//use Faker\Provider\DateTime;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
/**
 * App\Models\Service
 *
 * @property int $svkey
 * @property int $code
 * @property int $subcode1
 * @property int $subcode2
 * @property \Carbon\Carbon $date_begin
 * @property \Carbon\Carbon $date_end
 * @property int $prkey
 * @property int $pkkey
 * @property int $nmen
 * @property int $price
 * @property int $brutto
 * @property int $name
 * @property int $ctkey
 * @property int $cnkey
 * @property Country $country
 * @property City $city
 *
 */
class Service extends BaseModel
{
//    public $svkey, $code, $date_begin, $date_end, $subcode1, $subcode2, $prkey, $pkkey, $nmen;
//    public $price, $name, $ctkey, $cnkey;
    protected $dates = ['date_begin', 'date_end'];
    protected $fillable = ['svkey', 'code', 'date_begin', 'date_end', 'subcode1', 'subcode2', 'prkey', 'pkkey', 'nmen', 'brutto', 'name', 'ctkey', 'cnkey'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->svkey = (int)$this->svkey;
        $this->code = (int)$this->code;
        $this->subcode1 = (int)$this->subcode1;
        $this->subcode2 = (int)$this->subcode2;
        $this->pkkey = (int)$this->pkkey;
        $this->prkey = (int)$this->prkey;
        $this->nmen = (int)$this->nmen;
        $this->ctkey = (int)$this->ctkey;
        if( !(int)$this->cnkey && $this->svkey && $this->code ) {
            $oServiceInfo = self::getServiceTypeInfo($this->svkey, $this->code);
            $this->ctkey = $oServiceInfo->city_id;
            $this->cnkey = $oServiceInfo->country_id;
        }
    }

    /**
     * Получить объект country
     */
    public function country() {
        return $this->belongsTo(Country::class, 'cnkey', 'CN_KEY');
    }

    /**
     * Получить объект city
     */
    public function city() {
        return $this->belongsTo(City::class, 'ctkey', 'CT_KEY');
    }

    /**
     * Получить описание объекта
     */
    public function mainObject() {
        if( $this->svkey == 3 || $this->svkey == 8 ) {
            return $this->hasOne(Hotel::class, 'HD_KEY', 'code');
        }

        if( $this->svkey == 2 ) {
            return $this->hasOne(Transfer::class, 'TF_KEY', 'code');
        }
    }

    /**
     * Получить описание объекта subcode1
     */
    public function subcode1Object() {
        if( $this->svkey == 3 || $this->svkey == 8 ) {
            return $this->hasOne(HotelRoom::class, 'HR_KEY', 'subcode1');
        }

        if( $this->svkey == 2 ) {
            return $this->hasOne(Transport::class, 'TR_KEY', 'subcode1');
        }
    }

    /**
     * Получить описание объекта subcode
     */
    public function subcode2Object() {
        if( $this->svkey == 3 || $this->svkey == 8 ) {
            return $this->hasOne(Pansion::class, 'PN_KEY', 'subcode2');
        }
    }

    /**
     * Получить имя
     * @return string|null
     */
    public function getName()
    {
        $oDiff = $this->date_begin->diff($this->date_end);
        $aParams =  [
            'cnkey' => $this->cnkey,
            'ctkey' => $this->ctkey,
            'svkey' => $this->svkey,
            'code' => $this->code,
            'code1' => $this->subcode1,
            'code2' => $this->subcode2,
            'prkey' => $this->prkey,
            'date_begin' => $this->date_begin->format(self::DATE_ODBC_FORMAT),
            'date_end' => $this->date_end->format(self::DATE_ODBC_FORMAT),
            'duration' => $oDiff->days
        ];

        $sql =  self::query_prepare("
            EXEC dbo.wrapper_MakeFullSVName 
                @nCountry  = :cnkey,
                @nCity  = :ctkey,
                @nSvKey = :svkey,
                @nCode  = :code,
                @nCode1 = :code1,
                @nCode2 = :code2,
                @nPartner  = :prkey,
                @dServDate = :date_begin,
                @nNDays = :duration,
                @nOutput = 1
        ", $aParams);
        $aRes =  DB::selectOne($sql);

        $sName = !empty($aRes->name_lat) ? $aRes->name_lat : null;
        return $sName;
    }


    /**
     * Получить цену
     *
     * @param string $currency
     * @return float|null
     */
    public function getPriceBrutto($currency = '$') {
        $this->prkey = (int)$this->prkey?:$this->getFirstPartnerWithPrices();
        $aParams =  [
            'svkey' => $this->svkey,
            'code' => $this->code,
            'code1' => $this->subcode1,
            'code2' => $this->subcode2,
            'prkey' => $this->prkey,
            'pkkey' => $this->pkkey,
            'date_begin' => $this->date_begin->toDateString(),
            'date_end' => $this->date_end->toDateString(),
            'nmen' => $this->nmen,
            'currency' => $currency,
            'duration'=> $this->date_begin->diff($this->date_end)->days
        ];

        $sql = self::query_prepare("
                EXEC dbo.[wrapper_GetServiceCost] 
                        @svKey = :svkey, @code = :code, @code1 =:code1, @code2 = :code2, @prKey = :prkey, @packetKey = :pkkey, 
                        @date = :date_begin, @days = :duration, @resRate = :currency, @men =:nmen, @nOutput = 1                        
                ",
            $aParams
        );

        $aRes =  DB::selectOne($sql);
        $nPrice = !empty($aRes->BRUTTO) ? $aRes->BRUTTO : null;
        return $nPrice;
    }

    /**
     * Получить Первого партнерa с ценами
     * @return int
     */
    public function getFirstPartnerWithPrices()
    {
        $aParams =  [
            'svkey' => $this->svkey,
            'code' => $this->code,
            'code1' => $this->subcode1,
            'code2' => $this->subcode2,
            'date_begin' => $this->date_begin->toDateString()
        ];
        $aRes =  DB::selectOne(
            self::query_prepare("
                EXEC dbo.site_master_partners_with_prices 
                    @svkey = :svkey, 
                    @service_id = :code,
                    @subcode1 = :code1,
                    @subcode2 = :code2,
                    @date = :date_begin",
                $aParams
            )
        );

        $nPartnerId = !empty($aRes->id) ? $aRes->id : 0;
        return $nPartnerId;
    }

    /**
     * Получить subcode1 с ценами для ребенка
     * @return int
     *
     */
    protected function getChildSubcode1WithPrices()
    {
        $this->prkey = $this->prkey?:$this->getFirstPartnerWithPrices();
        $aParams =  [
            'code'  => $this->code,
            'code1' => $this->subcode1,
            'code2' => $this->subcode2,
            'date_begin' => $this->date_begin->toDateString(),
            'prkey' => $this->prkey,
            'pkkey' => $this->pkkey
        ];

        $aRes =  DB::selectOne(
            self::query_prepare("
                SELECT TOP 1 hrc.HR_KEY
                from hotelrooms hra
                    INNER JOIN hotelrooms hrc ON hrc.HR_RMKEY = hra.HR_RMKEY
                                                 AND hrc.HR_RCKEY = hra.HR_RCKEY 
                                                 AND hrc.HR_ACKEY > 2                                                 
                    INNER JOIN Accmdmentype   ON AC_KEY = hrc.HR_ACKEY
                                                 AND AC_CODE LIKE 'ch%' 
                    INNER JOIN tbl_costs      ON cs_dateend > :date_begin
                                                and cs_code = :code
                                                and cs_svkey = 3
                                                AND cs_subcode1 = hrc.HR_KEY
                                                AND cs_subcode2 = :code2
                                                AND ISNULL(CS_PRKEY,0) = :prkey
                                                AND ISNULL(cs_pkkey,0) = :pkkey
                WHERE
                    hra.HR_KEY = :code1
                ORDER BY AC_CODE",
                $aParams
            )
        );
        $nChSubcode1 = !empty($aRes->HR_KEY) ? $aRes->HR_KEY : 0;
        return $nChSubcode1;
    }

    /**
     * Получить subcode1 с ценами для ребенка
     * @param int $svkey
     * @param int $code
     * @param \Carbon\Carbon $date_begin
     * @param int $prkey
     * @param int $pkkey
     * @param int $subcode2
     * @return int[]
     */
    public static function getSubcode1WithPrices($svkey, $code, $date_begin, $prkey = 0, $pkkey = 0, $subcode2 = 0)
    {
        $aParams = [
            'svkey' => (int)$svkey,
            'code' => (int)$code,
            'prkey' => (int)$prkey,
            'pkkey' => (int)$pkkey,
            'code2' => (int)$subcode2,
            'date_begin' => $date_begin->toDateString()
        ];

        $aRes = DB::select(
            self::query_prepare("
                SELECT cs_subcode1
                FROM  tbl_costs
                WHERE
                    cs_dateend > :date_begin
                    and cs_code = :code
                    and cs_svkey = :svkey
                    AND (:code2 = 0 OR cs_subcode2 = :code2)
                    AND (:prkey = 0 OR ISNULL(CS_PRKEY,0) = :prkey)
                    AND (:pkkey = 0 OR ISNULL(cs_pkkey,0) = :pkkey)
                ORDER BY cs_cost",
                $aParams
            )
        );
        $aSubcodes = [];
        foreach ($aRes as $oRow) {
            $aSubcodes[] = $oRow->cs_subcode1;
        }
        return $aSubcodes;
    }

    /**
     * Получить информаицю услуге по типу
     * @param int $svkey
     * @param int $code
     * @return array|null
     */
    public static function getServiceTypeInfo($svkey, $code)
    {
        $aRes =  DB::selectOne(
            self::query_prepare(
                "EXEC dbo.site_service_info @code = :code, @svkey = :svkey",
                [
                    'code' => $code,
                    'svkey' => $svkey
                ]
            )
        );

        return $aRes;
    }

    /**
     * Получить информаицю по штрафам
     * @return array|null
     */
    public function getPenalties()
    {
        $aRes =  DB::select(
            self::query_prepare(
                "EXEC sbsn.CalculatePenaltyByService 
                    @nSVKey = :svkey,
                    @nCNKey = :cnkey,
                    @nCTKey = :ctkey,
                    @nPRKey = :prkey,
                    @nCode  = :code,
                    @nSubCode1 = :subcode1,
                    @nSubCode2 = :subcode2,
                    @dtTurDate = :date_begin",
                [
                    'svkey'  => $this->svkey,
                    'cnkey'  => $this->cnkey,
                    'ctkey'  => $this->ctkey,
                    'code'  => $this->code,
                    'prkey' => $this->prkey,
                    'pkkey' => $this->pkkey,
                    'subcode1' => $this->subcode1,
                    'subcode2' => $this->subcode2,
                    'date_begin' => $this->date_begin->toDateString(),
                ]
            )
        );

        return $aRes;
    }


}

