<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\Dogovor;
use App\Models\History;
use App\Models\Hotel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\BasketItem;
use App\Models\BasketItemService;
use App\Models\Service;
use App\Models\BasketItemTourist;
use Illuminate\Support\MessageBag;
use Validator;
use Illuminate\Support\Facades\Input;
class BasketItemController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        $request->flash();
        /** @var array $aTpl */
        $oBasketItem = BasketItem::find($id);

        /** @var Basket $a */
        if( $request->session()->token() != $oBasketItem->basket->session_id ){
            $errors = new MessageBag();
            $errors->add('elements', "Basket session and item session are not the same");
            return view('basket_item', [
                'errors' => $errors
            ]);
        }

        $aTpl['oBasketItem'] = $oBasketItem;

        /** @var BasketItemService $oMainItemService */
        $oMainItemService = null;
        foreach( $oBasketItem->basketItemServices as $oMainItemService ) if( $oMainItemService->svkey == 3 ){
            break;
        }
        $aTpl['oMainService'] = $oMainItemService;

        /** @var BasketItemService $oBIService */
        foreach ($oBasketItem->basketItemServices as $oBIService) {
            if ($oBIService->svkey != 3 && !$oMainItemService->mainObject->hd_transfer_strict) {
                $aTpl['optionalServices'][] = $oBIService;
            }
            else {
                $aTpl['requiredServices'][] = $oBIService;
            }
        }

        /** Считаемм штрафы и суммируем по релизам */
        $aPenalties = [];
        foreach ($oBasketItem->basketItemServices as $oBIService) {
            foreach ($oBIService->getPenalties() as $oPenalty){
                if( empty($aPenalties[$oPenalty->DLP_Release]) )
                    $aPenalties[$oPenalty->DLP_Release] = 0;
                $aPenalties[$oPenalty->DLP_Release] += (int)($oBIService->brutto * ($oPenalty->DLP_ValueProc/100));
            }
        }
        $aTpl['aPenalties'] = $aPenalties;
        return view('basket_item', $aTpl);
    }

    public function createDogovor(Request $request, $id){
        if( !\Auth::check() ){
            return redirect('/login');
        }

        //Потому что база на prod работает через freetds работает по cp1251
        $aParams = $request->all();
//        $aParams = CommonHelper::isDev() ? $aParams : CommonHelper::encode_to($aParams, 'cp1251', 'utf-8');
        BasketItemTourist::where('basket_item_id',$id)->delete();
        $aTourists = [];
        for ($i = 0; $i < count($aParams['first_name']); $i++) {
            $oBasketItemTourist = new BasketItemTourist();
            $oBasketItemTourist->basket_item_id = $id;
            $oBasketItemTourist->birthdate = $oBasketItemTourist->createDate($aParams['birthdate'][$i])->toDateString();
            $oBasketItemTourist->first_name = BasketItemTourist::translit($aParams['first_name'][$i]);
            $oBasketItemTourist->last_name = BasketItemTourist::translit($aParams['last_name'][$i]);
            $oBasketItemTourist->paspnum = $aParams['paspnum'][$i];
            $oBasketItemTourist->save();
            $aTourists[]=$oBasketItemTourist->id;
        }

        /** @var BasketItem $oBasketItem */
        $oBasketItem = BasketItem::find($id);

        $aServicesCollection = BasketItemService::where('basket_item_id',$id)->get();
        /** @var BasketItemService $oBIService */
        foreach($oBasketItem->basketItemServices as $oBIService){
            $aTourists = array_slice($aTourists, 0, $oBIService->nmen);
            //По одному потому что сиквель 2005 не умеет делать insert into [tb_item_service_tourists] ([itemservice_id], [itemtourist_id]) values (110, 115), (110, 116)
            foreach($aTourists as $nTouristId) {
                $oBIService->basketItemTourist()->attach($nTouristId);
            }
            //минусуем дату окончания у отелей, потому что getServicePrice Считает по дням а в tbl_dogovorlist хранится в ночах те -1 к дате окончания  - это пиздец
            if($oBIService->svkey == 3 ) {
                $oBIService->date_end = $oBIService->date_end->subDay(1);
                $oBIService->save();
            }
            if ($oBIService->is_disabled){
                $oBIService->delete();
            }
        }

        $oUser = \Auth::user();
        $oBasketItem->contact_name = $oUser->client->CL_FNAMERUS .' '. $oUser->client->CL_NAMERUS ;
        $oBasketItem->phone = $oUser->phone;
        $oBasketItem->email = $oUser->email;
        $oBasketItem->remark = !empty($aParams['remark']) ? $aParams['remark'] : '';
//        $oBasketItem->contact_name = $request->input('contact_name');
//        $oBasketItem->phone = $request->input('phone');
//        $oBasketItem->email = $request->input('email');
        $oBasketItem->save();

        $oRes = $oBasketItem->createDogovor();

        //Восстанавливаем дату окончания у отелей
        foreach($oBasketItem->basketItemServices as $oBIService){
            if($oBIService->svkey == 3 ) {
                $oBIService->date_end = $oBIService->date_end->addDay(1);
                $oBIService->save();
            }
        }

        /**
         * @todo Получить тут инфу о догвооре и прописать тут DG_CLIENTKEY
         */
        /** @var Dogovor $oDogovor */
        $oDogovor = Dogovor::where('DG_CODE', $oRes->DOGOVOR)->firstOrFail();
        $oDogovor->DG_CLIENTKEY = $oUser->client->CL_KEY;
        $oDogovor->update();

        /** Вставляем ремарку */
        $oHistory = new History();
        $oHistory->HI_DGCOD = $oDogovor->DG_CODE;
        $oHistory->HI_TEXT = $oBasketItem->remark;
        $oHistory->save();

        return redirect('order_info/'.$oDogovor->DG_Key);
    }

    public function editDisabledStatus(Request $request, $bi_service_id, $is_disabled){
        $oBIService = BasketItemService::find($bi_service_id);
        $oBIService->is_disabled = $is_disabled;
        $oBIService->save();
        return json_encode(['status' => 'ok'], JSON_PRETTY_PRINT);
    }

}
