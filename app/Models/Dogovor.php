<?php

namespace App\Models;

/**
 * App\Models\Dogovor
 *
 * @property INT $DG_Key
 * @property string $DG_CODE
 * @property string $DG_CLIENTKEY
 * @property string $DG_TURDATE
 * @property string $DG_TRKEY
 * @property string $DG_CNKEY
 * @property string $DG_CTKEY
 * @property string $DG_NMEN
 * @property string $DG_PRICE
 * @property string $DG_PAYED
 * @property string $DG_NDAY
 * @property string $DG_MAINMEN
 * @property string $DG_MAINMENPHONE
 * @property string $DG_MAINMENADRESS
 * @property string $DG_MAINMENPASPORT
 * @property string $DG_PARTNERKEY
 * @property string $DG_OPERATOR
 * @property string $DG_PRINTDOGOVOR
 * @property string $DG_PRINTVAUCHER
 * @property string $DG_TYPECOUNT
 * @property string $DG_DISCOUNT
 * @property string $DG_DISCOUNTSUM
 * @property string $DG_CREATOR
 * @property string $DG_OWNER
 * @property string $DG_SOR_CODE
 * @property string $DG_CRDATE
 * @property string $DG_RATE
 * @property string $DG_ADVERTISE
 * @property string $DG_LOCKED
 * @property string $DG_CAUSEDISC
 * @property string $DG_VISADATE
 * @property string $DG_PPAYMENTDATE
 * @property string $DG_PAYMENTDATE
 * @property string $DG_TURPUTDATE
 * @property string $DG_DOCUMENT
 * @property string $DG_TURPUTPLACE
 * @property string $DG_PROCENT
 * @property string $DG_OLDTOURDATE
 * @property string $DG_TurDateBfrAnnul
 * @property string $DG_ARKey
 * @property string $DG_CodePartner
 * @property string $DG_IsOutDoc
 * @property string $DG_NOTES
 * @property string $DG_INVOICECOST
 * @property string $DG_ISMAKEDISCOUNT
 * @property string $DG_DISCSUMBFRANN
 * @property string $DG_PRICEBFRANN
 * @property string $DG_RazmerP
 * @property string $DG_LEADDEPARTMENT
 * @property string $DG_MAINMENEMAIL
 * @property string $DG_MAINMENCOMMENT
 * @property string $DG_ConfirmedDate
 * @property string $DG_PRTDOGKEY
 * @property string $DG_CTDepartureKey
 * @property string $DG_SalePrice
 * @property string $DG_SaleDiscount
 * @property string $DG_PDTType
 * @property string $DG_NATIONALCURRENCYPRICE
 * @property string $DG_NATIONALCURRENCYDISCOUNTSUM
 * @property string $DG_DAKey
 * @property string $DG_NATIONALCURRENCYPAYED
 * @property string $dg_legal_accept_date
 * @property string $dg_legal_accepted
 *
 * @property DogovorService[] $services
 * @property DogovorTourist[] $tourists
 * @property Client $client
 * @property Country $country
 * @property City $city
 * @property DogovorStatus $status
 * @property History $history
 * @property Manager $manager
 *
 */

class Dogovor extends BaseModel
{
    protected $primaryKey = 'DG_Key';
    protected $table = 'tbl_dogovor';
    public $timestamps = false;
    protected $dates = ['DG_TURDATE', 'DG_CRDATE', 'DG_ConfirmedDate', 'dg_legal_accept_date'];
    /**
     * Получить клинета созадтеля
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'DG_CLIENTKEY', 'CL_KEY');
    }

    /**
     * Получить страну
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'DG_CNKEY', 'CN_KEY');
    }

    /**
     * Получить страну
     */
    public function status()
    {
        return $this->belongsTo(DogovorStatus::class, 'DG_SOR_CODE', 'OS_CODE');
    }

    /**
     * Получить город
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'DG_CTKEY', 'CT_KEY');
    }

    /**
     * Получить Услуги
     */
    public function services()
    {
        return $this->hasMany(DogovorService::class, 'DL_DGKEY', 'DG_Key');
    }

    /**
     * Получить туристы
     */
    public function tourists()
    {
        return $this->hasMany(DogovorTourist::class, 'TU_DGKEY', 'DG_Key');
    }

    /**
     * Получить историю
     */
    public function history()
    {
        return $this->hasMany(History::class, 'HI_DGCOD', 'DG_CODE');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'DG_OWNER', 'US_KEY');
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
