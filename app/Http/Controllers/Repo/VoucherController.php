<?php

namespace App\Http\Controllers\Repo;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Models\Dogovor;
use App\Models\Hotel;
use App\Models\Service;
use App\Mail\PrintedDocuments;

class VoucherController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        /** @var \App\User $oUser */
        $oUser = \Auth::user();
        /** @var Dogovor $oDogovor */
        $oDogovor = Dogovor::find($id);
        $errors = new MessageBag();
        if ( $oDogovor->DG_CLIENTKEY != $oUser->client->CL_KEY ){
            $errors->add('elements', "There is no such order");
        }
        if ( $oDogovor->DG_PRICE > $oDogovor->DG_PAYED ){
            $errors->add('elements', "Your order is not payed");
        }
        if ( $oDogovor->status->OS_CODE != $oDogovor::OK_STATUS ){
            $errors->add('elements', "Your order is not confirmed");
        }
        if( $errors->count() > 0 ) {
            return view('dogovor_info', ['errors' => $errors]);
        }

        /** Формируем уебан массив для ваучера со старого сайта */
        /** @var \App\Models\DogovorService $oDGService */
        $aDGServices = $oDogovor->services->where('DL_SVKEY', 3);
        $hTpl = [];
        $hTpl['nPic']=1;
        if ($oDogovor->DG_NMEN >= 2) {
            $hTpl['nPic'] = $oDogovor->DG_NMEN;
        }

        $oMainService = $oMainServiceInfo = null;
        foreach ($aDGServices as $oDGService) {
            $oMainService = $oDGService;
            $oMainServiceInfo = $oService = $oDGService->getServiceInfo();

            /** @var HotelRoom $oHotelRoom */
            $oHotelRoom = $oService->subcode1Object;
            $hTpl['sRoom'] = $oHotelRoom->room->RM_NAME;
            $hTpl['sRoomCategory'] = $oHotelRoom->roomCategory->RC_NAMELAT;
            /** @var \App\Models\Pansion $oPansion */
            $oPansion = $oService->subcode2Object;
            $hTpl['sPansionCode'] = $oPansion->PN_CODE;
            $hTpl['sPansionName'] = $oPansion->PN_NAME;
            $hTpl['nChild'] = 0;
            if( preg_match('/ch/i', $oHotelRoom->accmd->AC_NAMELAT)){
                $hTpl['nPic'] = '2_1';
                $hTpl['nChild'] = $oDGService->DL_NMEN;
            }
            else{
                $hTpl['nAdult'] = $oDGService->DL_NMEN;
            }
        }
        $aDGServices = $oDogovor->services->where('DL_SVKEY', 2);
        foreach ($aDGServices as $oDGService) {
            $oService = $oDGService->getServiceInfo();
            $hTpl['sTransfer'] = $oService->mainObject->TF_NAME;

            /** @var \App\Models\Transport $oTransport */
            $oTransport = $oService->subcode1Object;
            $hTpl['sTransport'] = $oTransport->TR_NAMELAT;

        }
        $hTpl['oDogovor'] = $oDogovor;
        $hTpl['oMainService'] = $oMainService;
        $hTpl['oMainServiceInfo'] = $oMainServiceInfo;
        $hTpl['oDogovor'] = $oDogovor;
        $hTpl['oMainHotel'] = Hotel::find($oMainService->DL_CODE);

        //инфо о том что ваучер распечатали
        $oHistory = new History();
        $oHistory->HI_DGCOD = $oDogovor->DG_CODE;
        $oHistory->HI_TEXT = 'Voucher was printed';
        $oHistory->HI_REMARK = 'IP: '.CommonHelper::get_ip();
        $oHistory->save();

        //отправляем письма манагеру и администратору
        if( $oDogovor->manager->US_MAILBOX ) {
            \Mail::to($oDogovor->manager->US_MAILBOX)->send(new PrintedDocuments($oDogovor));
        }
        return view('repo.voucher', $hTpl);
    }
}
