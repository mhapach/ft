<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\User;
use Illuminate\Http\Request;
use App\Models\Api\Services\SearchHotels;

class ProfileController extends Controller
{
    /**
     * Страница результатов поиска
     */
    public function index(Request $request)
    {
        /** @var \App\User $oUser */
        $oUser = \Auth::user();
        return view('profile', ['oUser' => $oUser, 'oClient' => $oUser->client]);
    }

    /**
     * Обновление профиля
     */
    public function update(Request $request)
    {
        /** @var \App\User $oUser */
        $oUser = \Auth::user();
        $data = $request->all();
//        $data = CommonHelper::isDev() ? $data : CommonHelper::encode_to($data, 'cp1251', 'utf-8');
        $oClient = $oUser->client;

        $oClient->CL_NAMERUS = $data['name'];
        if( $data['password'] ) {
            $data['password_confirmation'] = $data['password_confirmation']?:'';
            if( $data['password'] == $data['password_confirmation'] ) {
                $oUser->password = bcrypt($data['password']);
                $oUser->save();
            }
            else{
                return back()->withErrors(
                    ['password' => 'passwords are not equal']
                );
            }
        }
        $oClient->CL_BIRTHDAY = $oClient->createDate($data['birth_date'])->toDateString();
        $oClient->CL_ADDRESS = $data['address'];
        $oClient->CL_PHONE = $data['phone'];
        $oClient->CL_PASPORTNUM = $data['passport'];
        $oClient->CL_PASPORTDATEEND = $oClient->createDate($data['passport_issue'])->toDateString();
        $oClient->CL_TYPE = !empty($data['is_mailing_agree']) ? $oClient->CL_TYPE | 8 : $oClient->CL_TYPE ^ 8;
        $oClient->save();
        return view('profile', ['oUser' => $oUser, 'oClient' => $oUser->client]);
    }

}
