<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\BreakLogs;
use App\Models\DailyBreakLogs;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class BreakLogsController extends Controller
{
    public $title;
    /**
     * Display a listing of the resource.
     */
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->title = $this->getTableTitle('Settings');
    }
    public function index(){
        $employees = User::all();
        date_default_timezone_set("Asia/Tashkent");
        $today_hour = date('H');
        $now = date('Y-m-d H:i:s');
        $yesterday = date('Y-m-d H:i:s', strtotime('yesterday'));
        if((int)$today_hour>=7 && (int)$today_hour<=23){
            $from_time = date('Y-m-d', strtotime('yesterday 6:30'));
            $to_time = date('Y-m-d 06:30:00');
        }elseif((int)$today_hour>=0 && (int)$today_hour<6){
            $from_time = date('Y-m-d', strtotime('before yesterday 6:30'));
            $to_time = date('Y-m-d', strtotime('yesterday 6:30'));
        }
        foreach($employees as $employee){
            $yesterday_daily_break_logs = DailyBreakLogs::where('day', '>=', $from_time)->where('day', '<=', $to_time)->where('user_id', $employee->id)->first();
            if($employee->breakLogsYesterday){
                $interval_day = 0;
                $get_break_logs_yesterday = $employee->breakLogsYesterday;
                foreach($get_break_logs_yesterday as $get_break_log){
                    $yesterday_end_strtotime = strtotime($get_break_log->break_end);
                    $get_break_start = $get_break_log->getBreakStart;
                    if($get_break_start){
                        $yesterday_start_strtotime = strtotime($get_break_start->break_start);
                        $interval_day = $interval_day + $yesterday_end_strtotime - $yesterday_start_strtotime;
                        $get_break_start->delete();
                    }
                    $get_break_log->delete();
                }

                if($interval_day>0){
                    if(!$yesterday_daily_break_logs){
                        $yesterday_daily_break_logs = new DailyBreakLogs();
                    }
                    $yesterday_daily_break_logs->user_id = $employee->id;
                    $yesterday_daily_break_logs->seconds = $interval_day;
                    $yesterday_daily_break_logs->day = $yesterday;
                    $yesterday_daily_break_logs->save();
                }
            }

            $breakDailyLogsLast = DailyBreakLogs::where('user_id', $employee->id)->latest('created_at')->first();
            if($breakDailyLogsLast){
                $breakDailyLogsLastDay = $breakDailyLogsLast->day;
                $fromTime = $breakDailyLogsLastDay;
                $toTime = date('Y-m-d H:i:s', strtotime('6:30'));
                $intervalDay = 0;
                $break_logs_left = BreakLogs::where('break_end', '>=', $fromTime)->where('break_end', '<', $toTime)->where('user_id', $employee->id)->get();
                $set_day = '';
                foreach($break_logs_left as $break_log_left){
                    $last_end_strtotime = strtotime($break_log_left->break_end);
                    $get_left_break_start = $break_log_left->getBreakStart;
                    if($get_left_break_start){
                        $set_day_ = explode(' ', $get_left_break_start->break_start)[0];
                        $set_day = date('Y-m-d', strtotime($set_day_));
                        $last_start_strtotime = strtotime($get_left_break_start->break_start);
                        $intervalDay = $intervalDay + $last_end_strtotime - $last_start_strtotime;
                        $get_left_break_start->delete();
                    }
                    $break_log_left->delete();
                }
                if($intervalDay>0 && $set_day != ''){
                    $left_daily_break_logs = new DailyBreakLogs();
                    $left_daily_break_logs->user_id = $employee->id;
                    $left_daily_break_logs->seconds = $intervalDay;
                    $left_daily_break_logs->day = $set_day;
                    $left_daily_break_logs->save();
                }
            }
        }

        $user = Auth::user();
        $images = [];
        $interval_day = 0;
        if($user->breakLogsToday){
            $get_break_logs_today = $user->breakLogsToday;
            foreach($get_break_logs_today as $get_break_log){
                $today_end_strtotime = strtotime($get_break_log->break_end);
                $get_break_start = $get_break_log->getBreakStart;
                if($get_break_start){
                    $today_start_strtotime = strtotime($get_break_start->break_start);
                    $interval_day = $interval_day + $today_end_strtotime - $today_start_strtotime;
                }
            }
        }

        if((int)$today_hour>=20 && (int)$today_hour<=23){
            $from_time = date('Y-m-d H:i:s', strtotime('today'));
            $to_time = date('Y-m-d H:i:s', strtotime('tomorrow 6:00'));
        }elseif((int)$today_hour>=0 && (int)$today_hour<7){
            $from_time = date('Y-m-d H:i:s', strtotime('yesterday 20:00'));
            $to_time = date('Y-m-d 05:00:00', strtotime('today'));
        }else{
            return [];
        }
        $breakLogsTodayStarted = BreakLogs::where('created_at', '>=', $from_time)->where('created_at', '<', $to_time)->whereNull('break_end')->latest('created_at')->first();
        if($breakLogsTodayStarted){
            if(!$breakLogsTodayStarted->getBreakEnd){
                $today_now_strtotime = strtotime($now);
                $today_start_strtotime = strtotime($breakLogsTodayStarted->break_start);
                $interval_day = $interval_day + $today_now_strtotime - $today_start_strtotime;
            }
        }
        if($user->images) {
            $images_ = json_decode($user->images);
            $is_image = 0;
            foreach ($images_ as $image) {
                if(!$image){
                    $image = 'no';
                }
                $avatar_main = storage_path('app/public/users/' . $image);
                if (file_exists($avatar_main)) {
                    $is_image = 1;
                    $images[] = asset("storage/users/$image");
                }
            }
            if($is_image == 0){
                $images = [asset('icon/no_photo.jpg')];
            }
        }else{
            $images = [asset('icon/no_photo.jpg')];
        }

        if($user->status == Constants::ACTIVE){
            $status_text = translate_title('Tanaffusni tugatish');
        }else{
            $status_text = translate_title('Tanaffusni boshlash');
        }

        if($user->address){
            $address = $this->userService->getAddress($user->address);
        }else{
            $address = '';
        }

        $first_name = $user->name?$user->name.' ':'';
        $last_name = $user->surname?$user->surname.' ':'';
        $middle_name = $user->middlename?$user->middlename:'';
        $employee_name = $first_name.''.$last_name.''.$middle_name;

        $gender = '';
        if($user->gender == Constants::MALE){
            $gender = translate_title('Men');
        }elseif($user->gender == Constants::FEMALE){
            $gender = translate_title('Women');
        }

        $unuse_break_logs = BreakLogs::whereNull('break_start')->whereNull('break_start_id')->get();
        foreach($unuse_break_logs as $unuse_break_log){
            $unuse_break_log->delete();
        }

        $status_activate_text = translate_title('Tanaffusni boshlash');
        $status_disactivate_text = translate_title('Tanaffusni tugatish');
        $user_self = [
            'id'=>$user->id,
            'name'=>$user->name,
            'surname'=>$user->surname,
            'middlename'=>$user->middlename,
            'full_name'=>$employee_name,
            'phone'=>$user->phone,
            'address'=>$address,
            'gender'=>$gender,
            'images'=>$images,
            'status'=>$status_text,
            'is_admin'=>$user->is_admin,
            'email'=>$user->email,
            'status_'=>$user->status,
            'created_at'=>$user->created_at,
            'updated_at'=>$user->updated_at,
        ];
        return view('break.index', [
            'user_self'=>$user_self,
            'title'=>$this->title,
            'interval_day'=>$interval_day,
            'status_activate_text'=>$status_activate_text,
            'status_disactivate_text'=>$status_disactivate_text,
        ]);
    }
}
