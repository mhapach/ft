<?php

namespace App\Http\Controllers\Repo;

use App\Http\Controllers\Controller;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Models\Dogovor;
use App\Models\Hotel;
use App\Models\Service;

class InvoiceController extends Controller
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
        $hTpl['nHotelPrice'] = 0;
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
            $hTpl['nHotelPrice'] = (int)$hTpl['nHotelPrice'] + (int)$oDGService->DL_BRUTTO;
        }
        $aDGServices = $oDogovor->services->where('DL_SVKEY', 2);
        $hTpl['nTransferPrice'] = 0;
        foreach ($aDGServices as $oDGService) {
            $oService = $oDGService->getServiceInfo();
            $hTpl['sTransfer'] = $oService->mainObject->TF_NAME;

            /** @var \App\Models\Transport $oTransport */
            $oTransport = $oService->subcode1Object;
            $hTpl['sTransport'] = $oTransport->TR_NAMELAT;
            $hTpl['nTransferPrice'] += (int)$oDGService->DL_BRUTTO;

        }
        $hTpl['oDogovor'] = $oDogovor;
        $hTpl['oMainService'] = $oMainService;
        $hTpl['oMainServiceInfo'] = $oMainServiceInfo;
        $hTpl['oDogovor'] = $oDogovor;
        $hTpl['oMainHotel'] = Hotel::find($oMainService->DL_CODE);
        return view('repo.invoice', $hTpl);
    }
}
