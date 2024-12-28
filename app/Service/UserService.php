<?php
namespace App\Service;


use Illuminate\Support\Facades\App;

class UserService
{
    public function getAddress($address)
    {
        $language = App::getLocale();
        if ($address->cities) {
            if ($address->cities->region) {
                $region_translation = table_translate_title($address->cities->region, 'city', $language);
            }
            $city_translation = table_translate_title($address->cities->region, 'city', $language);
        }
        $address_name = $address->name ?? '';
        $address = $region_translation.' '.$city_translation.' '.$address_name;
        return $address;
    }

    public function getAge($user){
        $birth_date_array = explode(' ', $user->born_at);
        $now_time = strtotime('now');
        $birth_time = strtotime($birth_date_array[0]);
        $month = date('m', ($now_time));
        $day = date('d', ($now_time));
        $birth_month = date('m', ($birth_time));
        $birth_date = date('d', ($birth_time));
        $year = date('Y', ($now_time));
        $birth_year = date('Y', ($birth_time));
        $year_old = 0;
        if($year > $birth_year){
            $year_old = $year - $birth_year - 1;
            if($month > $birth_month){
                $year_old = $year_old +1;
            }elseif($month == $birth_month){
                if($day >= $birth_date){
                    $year_old = $year_old +1;
                }
            }
        }
        return $year_old;
    }

}
?>























