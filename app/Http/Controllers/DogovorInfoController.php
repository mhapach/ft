<?php

namespace App\Http\Controllers;

use App\Models\DogovorServicePenalty;
use App\Models\History;
use Illuminate\Http\Request;
use App\Models\Dogovor;
use Illuminate\Support\MessageBag;

class DogovorInfoController extends Controller
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
     * @param string $dogovor
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        /** @var \App\User $oUser */
        $oUser = \Auth::user();
        /** @var Dogovor $oDogovor */
        $oDogovor = Dogovor::find($id);
        if ( $oDogovor->DG_CLIENTKEY != $oUser->client->CL_KEY ){
            $errors = new MessageBag();
            $errors->add('elements', "There is no such order");
            return view('dogovor_info', ['errors' => $errors]);
        }

        /** Основная  услуга */
        $oMainService = $oDogovor->services->where('DL_SVKEY', 3)->first();

        /** История ремарок */
        $aHistory = $oDogovor->history->whereIn('HI_MOD', ['WWW', 'WAR'])->all();

        /** Считаемм штрафы и суммируем по релизам */
        $aPenalties = [];
        foreach ($oDogovor->services as $oDGService) {
            /** @var DogovorServicePenalty $oPenalty */
//            dd($oDGService->penalties);
            foreach ($oDGService->penalties as $oPenalty){
                if( empty($aPenalties[$oPenalty->DLP_Release]) )
                    $aPenalties[$oPenalty->DLP_Release] = 0;
                $aPenalties[$oPenalty->DLP_Release] += (int)($oDGService->DL_BRUTTO * ($oPenalty->DLP_ValueProc/100));
            }
        }
        $aTpl = ['oDogovor' => $oDogovor, 'oMainService' => $oMainService, 'aHistory' => $aHistory];
        $aTpl['aPenalties'] = $aPenalties;
        return view('dogovor_info', $aTpl);
    }
    public function createMessage(Request $request, $id){
        /** @var \App\User $oUser */
        $oUser = \Auth::user();
        /** @var Dogovor $oDogovor */
        $oDogovor = Dogovor::find($id);
        if ( $oDogovor->DG_CLIENTKEY != $oUser->client->CL_KEY ){
            $errors = new MessageBag();
            $errors->add('elements', "There is no such order");
            return view('dogovor_info', ['errors' => $errors]);
        }

        if( $request->get('message') ) {
            $oHistory = new History();
            $oHistory->HI_DGCOD = $oDogovor->DG_CODE;
            $oHistory->HI_TEXT = $request->get('message');
            $oHistory->save();
        }


        return redirect("/order_info/{$id}");

    }
}
