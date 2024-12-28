<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function address(){
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
    public function breakLogsToday(){
        date_default_timezone_set("Asia/Tashkent");
        $today_hour = date('H');
        if((int)$today_hour>=20 && (int)$today_hour<=23){
            $from_time = date('Y-m-d H:i:s', strtotime('today'));
            $to_time = date('Y-m-d H:i:s', strtotime('tomorrow 6:00'));
        }elseif((int)$today_hour>=0 && (int)$today_hour<7){
            $from_time = date('Y-m-d H:i:s', strtotime('yesterday 20:00'));
            $to_time = date('Y-m-d 05:00:00', strtotime('today'));
        }else{
            return [];
        }
        return $this->hasMany(BreakLogs::class, 'user_id', 'id')->where('created_at', '>=', $from_time)->where('created_at', '<', $to_time)->whereNULL('break_start');
    }

    public function breakLogsYesterday(){
        date_default_timezone_set("Asia/Tashkent");
        $today_hour = date('H');
        if((int)$today_hour>=7 && (int)$today_hour<=23){
            $from_time = date('Y-m-d', strtotime('yesterday 6:30'));
            $to_time = date('Y-m-d 06:30:00');
        }elseif((int)$today_hour>=0 && (int)$today_hour<5){
            $from_time = date('Y-m-d', strtotime('before yesterday 6:30'));
            $to_time = date('Y-m-d', strtotime('yesterday 6:30'));
        }
        return $this->hasMany(BreakLogs::class, 'user_id', 'id')->where('created_at', '>=', $from_time)->where('created_at', '<', $to_time)->whereNULL('break_start');
    }
}
