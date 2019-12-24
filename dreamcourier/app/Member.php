<?php

namespace App;

use App\Notifications\MemberResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        #'name', 'email', 'password',
        'member_code', 'email', 'password',
        'last_name','first_name','last_name_kana','first_name_kana','birthday','sex',
        'postal_code1','postal_code2',
        'address1','address2','address3','address4','address5','address6',
        'phone_number1','phone_number2','phone_number3',
        'enrollment_datetime','unsubscribe_reason','status','purchase_stop_division',
        'temporary_update_operator_code','temporary_update_approval_operator_code',
        'create_at','update_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MemberResetPassword($token));
    }
}
