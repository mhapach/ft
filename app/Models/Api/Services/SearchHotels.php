<?php

namespace App\Models\Api\Services;

use App\Models\Api\Services\Prices\HotelPrice;
use App\Models\BaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchHotels extends HotelPrice
{
    public function getResult(Request $request){
        $sPeriod = $request->input('dates_period');
        list($sBeginDate, $sEndDate) = explode("-", $sPeriod);
        $dBeginDate = $this->createDate(trim($sBeginDate));
        $dEndDate   = $this->createDate(trim($sEndDate));
        $nPage = $request->input('page', 1);
        $nShowMin = $request->input('show_min');
        $nShowMin = isset($nShowMin) ? (int)$nShowMin : 1;
        $nLimit = 15;

        $aRes =  DB::select(
            BaseModel::query_prepare("
                SBSN.getHotelsCost  @nCountryId      = :nCountryId,
                                    @nCityId         = :nCityId,
                                    @nResortId       = :nResortId,
                                    @nCode2          = :nPansionId,
                                    @nHotelId        = :nHotelId,--360,
                                    @nRoomId         = :nRoomId, --[1 SGL, 2- DBL , 3-TPL и тд]
                                    @nRoomCategoryId = 0, --71,
                                    @nAccmdId        = 1, --[1-Adult, 2-Adult ExBed, 3 - CH привязанный к отелю]
                                    @nShowOnlyMinPrices = :nShowMin,
                                    @dBeginDate      = :dBeginDate, 
                                    @dEndDate        = :dEndDate,
                                    @nOffset         = :nOffset,
                                    @nLimit          = :nLimit,
                                    @nStarsId        = :nStarsId",
                [
                    'nCountryId' => (int)$request->input('country_id'),
                    'nCityId' => (int)$request->input('city_id'),
                    'nResortId' => (int)$request->input('resort_id'),
                    'nPansionId' => (int)$request->input('pansion_id'),
                    'nHotelId' => (int)$request->input('hotel_id'),
                    'nRoomId' => (int)$request->input('room_id'),
                    'nShowMin' => $nShowMin,
                    'dBeginDate' => $dBeginDate->format('Y-m-d'),
                    'dEndDate'   => $dEndDate->format('Y-m-d'),
                    'nOffset' => ($nPage-1) * $nLimit,
                    'nLimit' => $nLimit,
                    'nStarsId' => (int)$request->input('stars_id')
                ]
            )
        );

//        $aRes =   new Paginator($aRes, count($aRes), $num_per_page, $page);
        /** @var self[] $aRes */
        $aRes = self::hydrate($aRes);
        return $aRes;
    }

    /*
        public function getHotelNameAttribute(){
            return 'test';// $this->attributes['HD_NAME']
        }
    /*
        public function getNameAttribute(){
            return $this->attributes['HD_NAME'];
        }
    */


    /** Без этих мутаторов почему то не желает конвертиться в Json см. тут \App\Http\Controllers\Api\SearchHotelsController::index*/
    public function getNRmKeyAttribute(){
        return $this->attributes['nRmKey'];
    }
    public function getNRcKeyAttribute(){
        return $this->attributes['nRcKey'];
    }
    public function getNAcKeyAttribute(){
        return $this->attributes['nAcKey'];
    }
    public function getNPriceAttribute(){
        return $this->attributes['nPrice'];
    }
    public function getNExtraBedCode1Attribute(){
        return $this->attributes['nExtraBedCode1'];
    }
    public function getNExtraBedPriceAttribute(){
        return $this->attributes['nExtraBedPrice'];
    }

}
