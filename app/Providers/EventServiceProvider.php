<?php

namespace App\Providers;

use App\Helpers\CommonHelper;
use App\User;
use App\Models\Client;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Mail\ActivateAccount;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
//        BasketItemService::created(function($model){
//            /** @var BasketItemService $model */
//            $model->name = Service::name($model->svkey, $model->code, $model->subcode1, $model->subcode2, $model->prkey, $model->pkkey, $model->date_begin, $model->date_end);
//        });

        User::created(function (User $user) {
            /*
            // @var Client $oClient
            $oClient  = Client::firstOrNew([
                'cl_mail' => $user->email,
                'CL_ISONLINE' => 1
            ]);

            $oRes = \DB::selectOne("
                EXEC sbsn.wrapper_GetNewKey @strKeyTable = 'clients'
            ");

            if( !empty($oRes) && !empty($oRes->id) ) {
                $oClient->CL_KEY = $oRes->id;
                $oClient->CL_OPERUPDATE = 0;
                $oClient->CL_FNAMERUS = $user->name;
                $oClient->CL_NAMERUS  = $user->lname;
                $oClient->CL_NAMELAT = Client::translit($user->name);
                $oClient->CL_FNAMELAT = Client::translit($user->lname);

                $oClient->CL_BIRTHDAY = $user->birth_date;
                $oClient->CL_PASPORTDATEEND = $user->passport_issue ;
                $oClient->CL_ADDRESS = $user->address ;
                $oClient->CL_PHONE = $user->phone ;
                $oClient->CL_PASPORTNUM = $user->passport ;
                $oClient->CL_TYPE = $user->is_mailing_agree ? ($oClient->CL_TYPE | 8) : ($oClient->CL_TYPE ^ 8) ;

                $oClient->save();
            }
            */

            // Send user message for activation account.
            \Mail::to($user->email)->send(new ActivateAccount($user));
        });

    }
}
