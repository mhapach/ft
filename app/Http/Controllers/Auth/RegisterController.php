<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\CommonHelper;
use App\Mail\ActivateAccount;
use App\Models\BaseModel;
use App\Models\Client;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/registered';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        //Потому что база через freetds работает по cp1251
//        $data = CommonHelper::isDev() ? $data : CommonHelper::encode_to($data, 'cp1251');

        return User::create([
            'name' => $data['name'],
            'lname' => $data['lname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'birth_date' => BaseModel::createDate($data['birth_date'])->toDateString(),
            'address' => $data['address'],
            'phone' => $data['phone'],
            'passport' => $data['passport'],
            'passport_issue' => BaseModel::createDate($data['passport_issue'])->toDateString(),
            'is_mailing_agree' => !empty($data['is_mailing_agree']) ? (int)$data['is_mailing_agree'] : 0,
        ]);
    }

    /**
     * Make user activation.
     */
    public function activation($userId, $token)
    {
        $user = User::findOrFail($userId);

        // Check token in user DB. if null then check data (user make first activation).
        if (is_null($user->remember_token)) {
            // Check token from url.
            if (md5($user->email) == $token) {
                // Change status and login user.
                $user->status = 1;
                $user->save();

                \Session::flash('flash_message', trans('interface.ActivatedSuccess'));

                // Make login user.
                \Auth::login($user, true);

                $this->createClient($user);
            } else {
                // Wrong token.
                \Session::flash('flash_message_error', trans('interface.ActivatedWrong'));
            }
        } else {
            // User was activated early.
            \Session::flash('flash_message_error', trans('interface.ActivatedAlready'));
        }
        return redirect('/');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param   Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register( Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    private function createClient(User $user){
        /** @var Client $oClient */
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

        // Send user message for activation account.
        \Mail::to($user->email)->send(new ActivateAccount($user));
    }
}
