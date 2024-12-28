@extends('layouts.admin_layout')

@section('title')
    {{translate_title('Edit employee')}}
@endsection
@section('content')
    <style>
        .delete_product_func{
            text-decoration: none;
        }
    </style>
    <div class="main-content-section">
        <div class="order-section">
            <div class="card">
                <div class="card-header">
                    <h4 class="mt-0 header-title">{{translate_title('Edit employee')}}</h4>
                </div>
                <div class="card-body">
                    <form class="modal-body needs-validation" action="{{route('users.update', $user->id)}}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="position-relative col-6 mb-3">
                                <label for="name" class="form-label">{{translate_title('Name')}}</label>
                                <input type="text" id="name" class="form-control" name="name" value="{{$user['name']}}" required>
                                <div class="invalid-tooltip">
                                    {{translate_title('Please enter name.')}}
                                </div>
                            </div>
                            <div class="position-relative col-6 mb-3">
                                <label for="surname" class="form-label">{{translate_title('Surname')}}</label>
                                <input type="text" id="surname" class="form-control" name="surname" value="{{$user['surname']}}" required>
                                <div class="invalid-tooltip">
                                    {{translate_title('Please enter user surname')}}
                                </div>
                            </div>
                            <div class="position-relative col-6 mb-3">
                                <label for="middlename" class="form-label">{{translate_title('Middlename')}}</label>
                                <input type="text" id="middlename" class="form-control" name="middlename" value="{{$user['middlename']}}">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="male">{{translate_title('Male')}}</label>
                                <input type="radio" name="gender" id="male" value="{{\App\Constants::MALE}}" checked class="me-4">
                                <label for="female">{{translate_title('Female')}}</label>
                                <input type="radio" name="gender" id="female" value="{{\App\Constants::FEMALE}}">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="phone" class="form-label">{{translate_title('Phone')}}</label>
                                <input type="text" id="phone" class="form-control" name="phone" value="{{$user['phone']}}">
                            </div>
                            <div class="col-6 d-flex overflow-auto">
                                @foreach($images as $image)
                                    @php
                                        $avatar_main = storage_path('app/public/users/'.$image);
                                    @endphp
                                    @if(file_exists(storage_path('app/public/users/'.$image)))
                                        <div class="mb-3 user_image">
                                            <div class="d-flex justify-content-between">
                                                <img src="{{asset('storage/users/'.$image)}}" alt="">
                                                <a class="delete_product_func">X</a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-6 mb-3">
                                <label for="image_input" class="form-label">{{translate_title('Images')}}</label>
                                <div class="d-flex">
                                    <div class="default_image_content">
                                        <img src="{{asset('img/default_image_plus.png')}}" alt="">
                                    </div>
                                    <span class="ms-1" id="images_quantity"></span>
                                </div>
                                <input type="file" id="image_input" name="images[]" class="form-control d-none" multiple>
                            </div>
                            <div class="col-4 position-relative mb-3">

                            </div>
                            <div class="col-4 mb-3">
                                <label for="email" class="form-label">{{translate_title('Email')}}</label>
                                <input type="text" id="email" class="form-control" name="email" value="{{$user['email']}}">
                            </div>
                            <div class="col-4 mb-3 d-flex align-items-center">
                                <label class="form-label me-2" for="status_">{{translate_title('Status')}}</label>
                                <input type="checkbox" name="status" id="status_" @if((int)$user['status'] == \App\Constants::ACTIVE) 'checked' @endif>
                            </div>
                            <div class="position-relative col-6 mb-3">
                                <label class="form-label">{{translate_title('Region')}}</label>
                                <select name="region_id" class="form-control" id="region_id" required>
                                    <option value="" disabled selected>{{translate_title('Select region')}}</option>
                                </select>
                                <div class="invalid-tooltip">
                                    {{translate_title('Please enter region.')}}
                                </div>
                            </div>
                            <div class="position-relative col-6 mb-3">
                                <label class="form-label">{{translate_title('District')}}</label>
                                <select name="district_id" class="form-control" id="district_id" required>
                                    <option value="" disabled selected>{{translate_title('Select district')}}</option>
                                </select>
                                <div class="invalid-tooltip">
                                    {{translate_title('Please enter district.')}}
                                </div>
                            </div>
                            <div class="col-6 mb-3"><label for="address" class="form-label">{{translate_title('Address')}}</label>
                                <input type="text" id="address" class="form-control" name="address" value="{{$user->address?$user->address->name:''}}">
                            </div>
                            <div class="position-relative col-6 mb-3">
                                <label for="password" class="form-label">{{translate_title('Password')}}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" placeholder="Enter password" name="password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative col-6 mb-3">
                                <label for="new_password" class="form-label">{{translate_title('New password')}}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="new_password" class="form-control" placeholder="Enter new password" name="new_password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative col-6 mb-3">
                                <label for="password_confirm" class="form-label">{{translate_title('Password confirmation')}}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirm" class="form-control" placeholder="Confirm password" name="password_confirmation">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="region" id="region">
                        <input type="hidden" name="district" id="district">
                        <div class="d-flex justify-content-end width_100_percent">
                            <button type="submit" class="btn modal_confirm">{{translate_title('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let page = true
        @if($user->address)
            @if($user->address->cities)
                let current_region = "{{$user->address->cities->region?$user->address->cities->region->id:''}}"
                let current_district = "{{$user->address->cities->id??''}}"
            @else
                let current_region = ''
                let current_district = ''
            @endif
        @else
            let current_region = ''
            let current_district = ''
        @endif


        let user_image = document.getElementsByClassName('user_image')
        let delete_product_func = document.getElementsByClassName('delete_product_func')
        let deleted_text = "{{translate_title('User image was deleted')}}"
        let user_images = []
        @if(is_array($images))
            @foreach($images as $image)
                user_images.push("{{$image}}")
            @endforeach
        @endif

        function deleteProductFunc(item, val) {
            delete_product_func[item].addEventListener('click', function (e) {
                e.preventDefault()
                $.ajax({
                    url: '/api/delete-product',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id:"{{$user->id}}",
                        image_file: user_images[item]
                    },
                    success: function(data){
                        if(data.status == true){
                            toastr.success(deleted_text)
                        }
                    }
                });
                if(!user_image[item].classList.contains('display-none')){
                    user_image[item].classList.add('display-none')
                }
            })
        }
        Object.keys(delete_product_func).forEach(deleteProductFunc)
    </script>
    <script src="{{asset('js/cities.js')}}"></script>
@endsection
