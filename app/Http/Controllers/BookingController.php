<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\BasketItem;
use App\Models\BasketItemService;
use App\Models\Service;
use App\Models\BasketItemTourist;

use Validator;
class BookingController extends Controller
{
    /**
     * @param Request $request
     * @param int $code
     * @param int $code1
     * @param int $code2
     * @param int $extra_bed_code1
     * @param string $dates_period
     * @param int $room_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $code, $code1, $code2, $extra_bed_code1 = 0, $dates_period, $room_id=2)
    {
        $aBookingInfo = [];
        /**
         * 1. Создаем корзину
         * 2. Получаем инфу об отеле
         * 3. ПОлучаем услуги с ценами :
         *    Отель
         *    Доп места
         *    Трансфер
         */

        $sSession = $request->session()->token();
        $aDates = explode(" - ", $dates_period);
        $sFromDate = $aDates[0];
        $sToDate = $aDates[1];
        $oFromDate = Basket::createDate($sFromDate);
        $oToDate = Basket::createDate($sToDate);
        $nRoomId = $room_id;
        if (strlen($nRoomId) == 1)
            $nRoomId = $nRoomId . '0';
        list($nAdults, $nChild) = str_split($nRoomId);
        $nAdults = (int)$nAdults;
        $nChild = (int)$nChild;
        $nAdultsOnMainPlaces = $nAdults >= 2 ? 2 : 1;//В нашем случае больше 2 чел на основном месте быть не может

        $validator = Validator::make(
            [
                'session_id' => $request->session()->token(),
                'code' => $code,
                'code1' => $code1,
                'code2' => $code2,
                'dates_period' => $dates_period,
                'date_begin' => $oFromDate->toDateString(),
                'date_end' => $oToDate->toDateString()
            ],
            [
                'session_id' => 'required|min:5|max:50',
                'code' => 'required|integer',
                'code1' => 'required|integer',
                'code2' => 'required|integer',
                'dates_period' => 'required|string|min:23|max:23',
                'date_begin' => 'required|date_format:Y-m-d',
                'date_end' => 'required|date_format:Y-m-d'
            ]
        );
        //Если ошибка пишем об ошибке
        if ( !$validator->passes() ) {
            return view('booking', [
                'errors' => $validator->errors()
            ]);
        }

        /** @var Basket */
        $oBasket = null;
        if( !($oBasket = Basket::where('session_id', $sSession)->first()) ) {
            $oBasket = new Basket();
        }
        $oBasket->session_id = $sSession;

        //Создаем сессию
        if( $oBasket->save() ) {
            //Если успешно создана сессия то создаем корзину
            $oBasketItem = new BasketItem();
            $oBasketItem->basket_id = $oBasket->id;
            $oBasketItem->date_begin = $oFromDate->toDateString();
            $oBasketItem->currency = $oBasketItem::DEFAULT_CURRENCY;
            $oBasketItem->site_id = $oBasketItem::SITE_ID;
            $oBasketItem->is_public = 1;
            //Если успешно создана корзина то создаем Услугу в корзине
            if( $oBasketItem->save() ) {
                $oService = new Service([
                    'svkey' => 3,
                    'code' => $code,
                    'date_begin' => $oFromDate->toDateString(), //Можно и $oFromDate но тогда припишет текущее время сука
                    'date_end' => $oToDate->toDateString(),
                    'subcode1' => $code1,
                    'subcode2' => $code2,
                    'nmen' => $nAdultsOnMainPlaces
                ]);
                $oMainService = $oBasketItem->insertService($oService);

                /** @var \App\Models\Hotel $oMainHotel */
                $oMainHotel = $oService->mainObject;
                /** Создаем доп кровать  */
                if ($extra_bed_code1) {
                    $oService->nmen = 1;
                    $oService->subcode1 = $extra_bed_code1;
                    $oExtraBedMainService = $oBasketItem->insertService($oService);
                }

                /** Создаем транфсер */
                if( !empty($oMainHotel->transfer) ) {
                    $nTransferId = $oMainHotel->transfer->TF_KEY;
                    $aTransportIds = Service::getSubcode1WithPrices(2, $nTransferId, $oFromDate, $oMainService->prkey);
                    $nCheapestTransportId = $aTransportIds[0];

                    $oService = new Service([
                        'svkey' => 2,
                        'code' => $nTransferId,
                        'date_begin' => $oFromDate->toDateString(),
                        'date_end' => $oFromDate->toDateString(),
                        'subcode1' => $nCheapestTransportId,
                        'prkey' => $oMainService->prkey,
                        'pkkey' => $oMainService->pkkey,
                        'nmen' => ($room_id > 2) ? 3 : $room_id
                    ]);
                    $nIsTransferDisabled = $oMainService->mainObject->hd_transfer_strict ? 0 : 1;
                    $oTransferService = $oBasketItem->insertService($oService, $nIsTransferDisabled);
                }
                /** Создаем туристов сразу */
                for ($i = 0; $i < $nAdults + $nChild; $i++) {
                    $oBasketItemTourist = new BasketItemTourist();
                    $oBasketItemTourist->basket_item_id = $oBasketItem->id;
                    $oBasketItemTourist->save();
                }

                return redirect('/basket_item/'.$oBasketItem->id);
            }
            else {
                return view('booking', [
                    'errors' => "Basket wasn't created"
                ]);
            }
        }
        else{
            return view('booking', [
                'errors' => "Session wasn't created"
            ]);
        }

//        Если Все успешно то переходим на BasketItemCotroller
        //return redirect()->route('home');

//        return view('booking', [
//            'aBookingInfo' => $aBookingInfo
//        ]);
    }

}
