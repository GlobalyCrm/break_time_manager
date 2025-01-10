<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\BreakLogs;
use App\Models\Cities;
use App\Models\DailyBreakLogs;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $title;
    public $current_page = 'home';

    public function __construct()
    {
        $this->title = $this->getTableTitle('Home');
    }

    public function index(){

        return view('index', [
            'title'=>$this->title,
            'current_page'=>$this->current_page,
        ]);
    }

    public function dashboard(){
//        $employees = User::whereNull('is_admin')->get();
        $employees = User::all();
        $employees_quantity = count($employees);
        $daily_data = [];
        date_default_timezone_set("Asia/Tashkent");
        $today_hour = date('H');
        $yesterday = date('Y-m-d H:i:s', strtotime('yesterday'));
        if((int)$today_hour>=7 && (int)$today_hour<=23){
            $from_time = date('Y-m-d', strtotime('yesterday 6:30'));
            $to_time = date('Y-m-d 06:30:00');
        }elseif((int)$today_hour>=0 && (int)$today_hour<5){
            $from_time = date('Y-m-d', strtotime('before yesterday 6:30'));
            $to_time = date('Y-m-d', strtotime('yesterday 6:30'));
        }

        $unuse_break_logs = BreakLogs::whereNull('break_start')->whereNull('break_start_id')->get();
        foreach($unuse_break_logs as $unuse_break_log){
            $unuse_break_log->delete();
        }
        $started_break_logs = BreakLogs::whereNotNull('break_start')->where('break_start', '<', date('Y-m-d H:i:s', strtotime('-4 hours')))->get();
        foreach($started_break_logs as $started_break_log){
            if(!$started_break_log->getBreakEnd){
                $started_break_log->delete();
            }
        }
        $ended_break_logs = BreakLogs::whereNotNull('break_end')->get();
        foreach($ended_break_logs as $ended_break_log){
            if(!$ended_break_log->getBreakStart){
                $ended_break_log->delete();
            }
        }

        foreach($employees as $employee){
            $yesterday_daily_break_logs = DailyBreakLogs::where('day', '>=', $from_time)->where('day', '<=', $to_time)->where('user_id', $employee->id)->first();
            $first_name = $employee->name?$employee->name.' ':'';
            $last_name = $employee->surname?$employee->surname.' ':'';
            $middle_name = $employee->middlename?$employee->middlename:'';
            $employee_name = $first_name.''.$last_name.''.$middle_name;

            $images = [];
            if($employee->images) {
                $images_ = json_decode($employee->images);
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

            $employee_data = [
                'full_name'=>$employee_name,
                'phone'=>$employee->phone,
                'address'=>$employee->address,
                'images'=>$images,
                'status'=>$employee->status,
                'email'=>$employee->email,
                'created_at'=>$employee->created_at,
                'updated_at'=>$employee->updated_at
            ];

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

            $this_month_break_time_seconds = $this->getMonthBreakTime('this month', $employee->id);

            $formatted_this_month_break_time = $this->getFormattedSeconds((int)$this_month_break_time_seconds);

            $january_break_time_seconds = $this->getMonthBreakTime('January', $employee->id);
            $formatted_january_break_time = $this->getFormattedSeconds((int)$january_break_time_seconds);
            $february_break_time_seconds = $this->getMonthBreakTime('February', $employee->id);
            $formatted_february_break_time = $this->getFormattedSeconds((int)$february_break_time_seconds);
            $march_break_time_seconds = $this->getMonthBreakTime('March', $employee->id);
            $formatted_march_break_time = $this->getFormattedSeconds((int)$march_break_time_seconds);
            $april_break_time_seconds = $this->getMonthBreakTime('April', $employee->id);
            $formatted_april_break_time = $this->getFormattedSeconds((int)$april_break_time_seconds);
            $may_break_time_seconds = $this->getMonthBreakTime('May', $employee->id);
            $formatted_may_break_time = $this->getFormattedSeconds((int)$may_break_time_seconds);
            $june_break_time_seconds = $this->getMonthBreakTime('June', $employee->id);
            $formatted_june_break_time = $this->getFormattedSeconds((int)$june_break_time_seconds);
            $july_break_time_seconds = $this->getMonthBreakTime('July', $employee->id);
            $formatted_july_break_time = $this->getFormattedSeconds((int)$july_break_time_seconds);
            $august_break_time_seconds = $this->getMonthBreakTime('August', $employee->id);
            $formatted_august_break_time = $this->getFormattedSeconds((int)$august_break_time_seconds);
            $september_break_time_seconds = $this->getMonthBreakTime('September', $employee->id);
            $formatted_september_break_time = $this->getFormattedSeconds((int)$september_break_time_seconds);
            $october_break_time_seconds = $this->getMonthBreakTime('October', $employee->id);
            $formatted_october_break_time = $this->getFormattedSeconds((int)$october_break_time_seconds);
            $november_break_time_seconds = $this->getMonthBreakTime('November', $employee->id);
            $formatted_november_break_time = $this->getFormattedSeconds((int)$november_break_time_seconds);
            $december_break_time_seconds = $this->getMonthBreakTime('December', $employee->id);
            $formatted_december_break_time = $this->getFormattedSeconds((int)$december_break_time_seconds);

            $the_seconds = 0;
            if($yesterday_daily_break_logs){
                $the_seconds = (int)$yesterday_daily_break_logs->seconds;
            }
            $formatted_time = $this->getFormattedSeconds($the_seconds);

            $nine_hour = 9*3600;
            $month_time = 9*28*3600;
            $daily_data[] = [
                'employee'=>$employee_data,
                'break_time'=>$formatted_time,
                'month_time'=>$month_time,
                'interval_day'=>$the_seconds,
                'formatted_this_month_break_time'=>$formatted_this_month_break_time,
                'nine_hour'=>$nine_hour,
                'this_month_break_time_seconds'=>$this_month_break_time_seconds,
                'months'=>[
                    [
                      'name'=>translate_title('January'),
                      'break_time'=>$formatted_january_break_time,
                    ],
                    [
                      'name'=>translate_title('February'),
                      'break_time'=>$formatted_february_break_time,
                    ],
                    [
                      'name'=>translate_title('March'),
                      'break_time'=>$formatted_march_break_time,
                    ],
                    [
                      'name'=>translate_title('April'),
                      'break_time'=>$formatted_april_break_time,
                    ],
                    [
                      'name'=>translate_title('May'),
                      'break_time'=>$formatted_may_break_time,
                    ],
                    [
                      'name'=>translate_title('June'),
                      'break_time'=>$formatted_june_break_time,
                    ],
                    [
                      'name'=>translate_title('July'),
                      'break_time'=>$formatted_july_break_time,
                    ],
                    [
                      'name'=>translate_title('August'),
                      'break_time'=>$formatted_august_break_time,
                    ],
                    [
                      'name'=>translate_title('September'),
                      'break_time'=>$formatted_september_break_time,
                    ],
                    [
                      'name'=>translate_title('October'),
                      'break_time'=>$formatted_october_break_time,
                    ],
                    [
                      'name'=>translate_title('November'),
                      'break_time'=>$formatted_november_break_time,
                    ],
                    [
                      'name'=>translate_title('December'),
                      'break_time'=>$formatted_december_break_time,
                    ],
                ]
            ];

        }
        return view('dashboard', [
            'title'=>$this->title,
            'daily_data'=>$daily_data,
            'current_page'=>$this->current_page,
            'employees_quantity'=>$employees_quantity
        ]);
    }

    function getFormattedSeconds($seconds){
        $hour = '00';
        $minute = '00';
        $second = '00';
        if($seconds){
            $the_seconds = (int)$seconds;
            if($the_seconds){
                if($the_seconds/3600>=1){
                    $hour = floor($the_seconds/3600);
                    $left_time = $the_seconds%3600;
                    if($left_time>=1){
                        $minute = floor($left_time/60);
                    }
                    if($left_time%60>=1){
                        $second = floor($left_time%60);
                    }
                }else{
                    if($the_seconds/60>=1){
                        $minute = floor($the_seconds/60);
                    }
                    if($the_seconds%60>=1){
                        $second = floor($the_seconds%60);
                    }
                }
            }
        }
        if($hour>10){
            $hour = '0'.$hour;
        }
        if($minute>10){
            $minute = '0'.$minute;
        }
        if($second>10){
            $second = '0'.$second;
        }
        return $hour.':'.$minute.':'.$second;
    }

    function getMonthBreakTime($month, $employee_id){
        $this_month_start = '';
        $next_month_start = '';
        switch($month){
            case 'this month':
                $this_month_start = date('Y-m-01 00:00:00');
                $next_month_start = date('Y-m-01 00:00:00', strtotime('+1 month'));
                break;
            case 'January':
                $this_month_start = date('Y-01-01 00:00:00');
                $next_month_start = date('Y-02-01 00:00:00');
                break;
            case 'February':
                $this_month_start = date('Y-02-01 00:00:00');
                $next_month_start = date('Y-03-01 00:00:00');
                break;
            case 'March':
                $this_month_start = date('Y-03-01 00:00:00');
                $next_month_start = date('Y-04-02 00:00:00');
                break;
            case 'April':
                $this_month_start = date('Y-04-01 00:00:00');
                $next_month_start = date('Y-05-02 00:00:00');
                break;
            case 'May':
                $this_month_start = date('Y-05-01 00:00:00');
                $next_month_start = date('Y-06-02 00:00:00');
                break;
            case 'June':
                $this_month_start = date('Y-06-01 00:00:00');
                $next_month_start = date('Y-07-02 00:00:00');
                break;
            case 'July':
                $this_month_start = date('Y-07-01 00:00:00');
                $next_month_start = date('Y-08-02 00:00:00');
                break;
            case 'August':
                $this_month_start = date('Y-08-01 00:00:00');
                $next_month_start = date('Y-09-02 00:00:00');
                break;
            case 'September':
                $this_month_start = date('Y-09-01 00:00:00');
                $next_month_start = date('Y-10-02 00:00:00');
                break;
            case 'October':
                $this_month_start = date('Y-10-01 00:00:00');
                $next_month_start = date('Y-11-02 00:00:00');
                break;
            case 'November':
                $this_month_start = date('Y-11-01 00:00:00');
                $next_month_start = date('Y-12-02 00:00:00');
                break;
            case 'December':
                $this_month_start = date('Y-12-01 00:00:00');
                $next_month_start = date('Y-01-02 00:00:00', strtotime('+1 year'));
                break;
        }
        $breakMonthlyLogs = DailyBreakLogs::where('day', '>=', $this_month_start)->where('day', '<', $next_month_start)->where('user_id', $employee_id)->get();
        $interval_month = 0;
        foreach($breakMonthlyLogs as $breakMonthlyLog){
            $interval_month = $interval_month + (int)$breakMonthlyLog->seconds;
        }
        return $interval_month;
    }


    public function welcome(){

        return view('welcome');
    }

    public function setCities(){
        if(!Cities::withTrashed()->exists()){
            $jsonPath = public_path('json/cities.json');
            $response = file_get_contents($jsonPath);
            $cities = json_decode($response, true);
            foreach ($cities as $city){
                if(!Cities::where('name', $city['region'])->exists()){
                    $model_region = new Cities();
                    $model_region->name = $city['region'];
                    $model_region->type = 'region';
                    $model_region->parent_id = 0;
                    $model_region->lng = $city['long'];
                    $model_region->lat = $city['lat'];
                    $model_region->save();
                    foreach($city['cities'] as $city_district){
                        $model = new Cities();
                        $model->name = $city_district['name'];
                        $model->type = 'district';
                        $model->parent_id = $model_region['id'];
                        $model->lng = $city_district['long'];
                        $model->lat = $city_district['lat'];
                        $model->save();
                    }
                }else{
                    $model_region = Cities::where('name', $city['region'])->first();
                    $model_region->lng = $city['long'];
                    $model_region->lat = $city['lat'];
                    $model_region->save();
                }
            }
        }
    }

    public function getCities(Request $request){
        if(session()->has('locale')){
            $language = session('locale');
        }else{
            $language = env('DEFAULT_LANGUAGE','ru');
        }
        $cities = Cities::where('parent_id', 0)->orderBy('id', 'ASC')->get();
        $data = [];
        foreach ($cities as $city){
            $city_translate = table_translate_title($city,'city',$language);
            $cities_ = [];
            foreach ($city->getDistricts as $district){
                $district_translate = table_translate_title($district,'city',$language);
                $cities_[] = [
                    'id'=>$district->id,
                    'name'=>$district_translate,
                    'lat'=>$district->lat,
                    'long'=>$district->lng
                ];
            }
            $data[] = [
                'id'=>$city->id,
                'name'=>$city_translate,
                'lat'=>$city->lat,
                'long'=>$city->lng,
                'cities'=>$cities_,
            ];
        }
        if(!empty($data)){
            return response()->json([
                'data' => $data ?? NULL,
                'status' => true,
                'message' => 'Success'
            ], 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
        }else{
            return response()->json([
                'status' => true,
                'message' => 'No cities'
            ], 200, [], JSON_INVALID_UTF8_SUBSTITUTE); // $error_type
        }
    }
}
