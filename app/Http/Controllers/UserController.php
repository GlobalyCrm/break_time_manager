<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Address;
use App\Models\BreakLogs;
use App\Models\Orders;
use App\Models\ServicePrice;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    public $title;
    public $userService;
    public $current_page = 'Users';

    public function __construct(UserService $userService)
    {
        date_default_timezone_set("Asia/Tashkent");
        $this->userService = $userService;
        $this->title = $this->getTableTitle('Users');
    }

    public function index()
    {
        $users_ = User::all();
        $users = [];
        foreach($users_ as $user){
            $images = [];
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
                $status_text = translate_title('Active');
            }else{
                $status_text = translate_title('Not active');
            }

            if($user->address){
                $address = $this->userService->getAddress($user->address);
            }else{
                $address = '';
            }

            $gender = '';
            if($user->gender == Constants::MALE){
                $gender = translate_title('Men');
            }elseif($user->gender == Constants::FEMALE){
                $gender = translate_title('Women');
            }

            $role = '';
            if($user->is_admin == Constants::USER_ADMIN){
                $role = translate_title('Admin');
            }else{
                $role = translate_title('Employee');
            }

            $users[] = [
                'id'=>$user->id,
                'name'=>$user->name,
                'surname'=>$user->surname,
                'middlename'=>$user->middlename,
                'phone'=>$user->phone,
                'instagram_url'=>$user->instagram_url,
                'images'=>$images,
                'gender'=>$gender,
                'status_'=>$user->status,
                'status'=>$status_text,
                'email'=>$user->email,
                'role'=>$role,
                'address'=>$address,
                'created_at'=>$user->created_at,
                'updated_at'=>$user->updated_at,
            ];
        }

        return view('users.index', [
            'users'=>$users,
            'title'=>$this->title,
            'current_page'=>$this->current_page,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $address = new Address();
        if($request->district && $request->region){
            $address = new Address();
            if($request->district){
                $address->city_id = $request->district;
            }elseif($request->region){
                $address->city_id = $request->region;
            }
            $address->name = $request->address;
            $address->save();
        }

        if($request->new_password && $request->new_password != $request->password_confirmation){
            return redirect()->back()->with('error', translate_title('Your new password confirmation is incorrect'));
        }
        if($request->new_password && !$request->email){
            return redirect()->back()->with('error', translate_title('Your don\'t have email'));
        }

        $address->name = $request->address;
        $address->save();
        $users = new User();
        $users->name = $request->name;
        $users->surname = $request->surname;
        $users->middlename = $request->middlename;
        $users->phone = $request->phone;
        $users->address_id = $address->id;
        if($request->female){
            $users->gender = Constants::FEMALE;
        }elseif($request->male){
            $users->gender = Constants::MALE;
        }
        if($request->status){
            $users->status = Constants::ACTIVE;
        }else{
            $users->status = Constants::NOT_ACTIVE;
        }
        $users->images = $this->imageSave($users, $request->images, 'store');
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->save();

        return redirect()->route('users.index')->with('success', translate_title('Successfully created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $images = [];
        if($user->images){
            $images = json_decode($user->images);
        }
        return view('users.edit', [
            'user'=>$user,
            'images'=>$images,
            'title'=>$this->title,
            'current_page'=>$this->current_page,
        ]);
    }

    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $users = User::find($id);
        if ($request->password && $request->new_password && $request->password_confirmation) {
            if($request->email){
                if (Hash::check($request->password, $users->password) && $request->new_password == $request->password_confirmation) {
                    $users->password = Hash::make($request->new_password);
                }else{
                    if(!Hash::check($request->password, $users->password)){
                        return redirect()->back()->with('error', translate_title('Your password is incorrect'));
                    }elseif($request->new_password != $request->password_confirmation){
                        return redirect()->back()->with('error', translate_title('Your new password confirmation is incorrect'));
                    }
                }
            }else{
                return redirect()->back()->with('error', translate_title('Your don\'t have email'));
            }
        }elseif($request->password && $request->new_password && !$request->password_confirmation){
            return redirect()->back()->with('error', translate_title('Your new password confirmation is incorrect'));
        }
        if($users->address){
            $address = $users->address;
        }else{
            $address = new Address();
        }
        if($request->district){
            $address->city_id = $request->district;
        }elseif($request->region){
            $address->city_id = $request->region;
        }
        $address->name = $request->address;
        $address->user_id = $users->id;
        $address->save();

        $users->name = $request->name;
        $users->surname = $request->surname;
        $users->middlename = $request->middlename;
        $users->phone = $request->phone;
        $users->address_id = $address->id;
        if($request->status){
            $users->status = Constants::ACTIVE;
        }else{
            $users->status = Constants::NOT_ACTIVE;
        }
        if($request->female){
            $users->gender = Constants::FEMALE;
        }elseif($request->male){
            $users->gender = Constants::MALE;
        }
        $users->images = $this->imageSave($users, $request->images, 'update');
        $users->email = $request->email;
        $users->address_id = $address->id;
        $users->password = Hash::make($request->new_password);
        $users->save();
        return redirect()->route('users.index')->with('success', translate_title('Successfully updated'));
    }

    public function userCreate(){
        return view('users_auth', [
            'title'=>$this->title,
            'current_page'=>$this->current_page]
        );
    }

    public function imageSave($user, $images, $text){
        if($text == 'update'){
            if($user->images && !is_array($user->images)){
                $user_images = json_decode($user->images);
            }else{
                $user_images = [];
            }
        }else{
            $user_images = [];
        }
        if(isset($images)){
            $UserImage = [];
            foreach($images as $image){
                $random = $this->setRandom();
                $image_image_name = $random.''.date('Y-m-dh-i-s').'.'.$image->extension();
                $image->storeAs('public/users/', $image_image_name);
                $UserImage[] = $image_image_name;
            }
            $all_user_images = array_values(array_merge($user_images, $UserImage));
        }
        $UserImage = json_encode($all_user_images??$user_images);
        return $UserImage;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = User::find($id);
        if($model){
            $images = json_decode($model->images);
            foreach($images as $image){
                if ($image) {
                    $avatar = storage_path('app/public/users/'.$image);
                } else {
                    $avatar = 'no';
                }
                if (file_exists($avatar)) {
                    unlink($avatar);
                }
            }
            $model->delete();
        }
        return redirect()->route('users.index')->with('status', translate_title('Successfully deleted'));
    }

    public function deleteProductImage(Request $request){
        $user = User::find($request->id);
        if($user->images && !is_array($user->images)){
            $user_images_base = json_decode($user->images);
        }else{
            $user_images_base = [];
        }
        if(is_array($user_images_base)){
            if(isset($request->image_file)){
                $selected_product_key = array_search($request->image_file, $user_images_base);
                if(!$request->image_file){
                    $request->image_file = 'no';
                }
                $user_image_ = storage_path('app/public/users/'.$request->image_file);
                if(file_exists($user_image_)){
                    unlink($user_image_);
                }
                unset($user_images_base[$selected_product_key]);
            }
            $user->images = json_encode(array_values($user_images_base));
            $user->save();
        }
        return response()->json([
            'status'=>true,
            'message'=>'Success'
        ], 200);
    }

    public function changeStatus(Request $request){
        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        $response = [
            'status'=>true,
            'message'=>'Success'
        ];
        $break_logs = new BreakLogs();
        $break_logs->user_id = $user->id;
        $break_logs_last = BreakLogs::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
        if((int)$user->status == Constants::ACTIVE){
            $break_logs->break_start = date('Y-m-d H:i:s');
        }elseif((int)$user->status == Constants::NOT_ACTIVE){
            $break_logs->break_end = date('Y-m-d H:i:s');
            if($break_logs_last){
                if($break_logs_last->break_start){
                    $break_logs->break_start_id = $break_logs_last->id;
                }
            }
        }
        $break_logs->save();
        return response()->json($response);
    }
}
