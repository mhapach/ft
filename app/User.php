<?php

namespace App;

use App\Helpers\CommonHelper;
use App\Models\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
/**
 * App\User
 *
 * @property INT $id
 * @property string $name
 * @property string $lname
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 * @property \Carbon\Carbon $birth_date
 * @property \Carbon\Carbon $passport_issue
 * @property string $address
 * @property string $phone
 * @property string $passport
 * @property int $is_mailing_agree
 *
 * @property Client $client
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lname', 'email', 'password', 'birth_date', 'passport_issue', 'address', 'phone', 'passport', 'is_mailing_agree'
    ];
    protected $dates = ['birth_date', 'passport_issue'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Получить соответвующего пользователя
     */
    public function client()
    {
        return $this->hasOne(Models\Client::class, 'cl_mail', 'email');
    }

//    /** MUTATORS */
//    public function getNameAttribute(){
//        return CommonHelper::isDev() ? $this->attributes['name'] : CommonHelper::encode_to($this->attributes['name'], 'utf-8', 'cp1251');
//    }
//    public function getLnameAttribute(){
//        return CommonHelper::isDev() ? $this->attributes['lname'] : CommonHelper::encode_to($this->attributes['lname'], 'utf-8', 'cp1251');
//    }
}
