@extends('layouts.admin_layout')

@section('title')
    {{translate_title('Employees')}}
@endsection
@section('content')
    <style>
        #datatable-buttons td{
            overflow-wrap: break-word;
        }
    </style>
    <div id="loader"></div>
    <div class="main-content-section d-none" id="myDiv">
        <div class="order-section">
            <!-- Tab panes -->
            <div class="card-body">
                <div class="tab-content" id="employees_">
                    <div class="tab-pane fade show active" id="employees" role="tabpanel" aria-labelledby="employees-tab">
                        <div class="card">
                            <div class="right_button_create">
                                <button class="form_functions global-button" data-bs-toggle="modal" data-bs-target="#create_modal" data-url="{{route('users.store')}}">
                                    <img src="{{asset('menubar/client_active.png')}}" alt="" height="20px">
                                    {{translate_title('Новый Сотрудник')}}
                                </button>
                            </div>
                            <div class="card-body overflow-auto">
                                <table id="datatable-buttons" class="restaurant_tables table table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{translate_title('Id')}}</th>
                                            <th>{{translate_title('Name')}}</th>
                                            <th>{{translate_title('Surname')}}</th>
                                            <th>{{translate_title('Phone')}}</th>
                                            <th>{{translate_title('Email')}}</th>
                                            <th>{{translate_title('Images')}}</th>
                                            <th>{{translate_title('Functions')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user['id']}}</td>
                                            <td>{{$user['name']}}</td>
                                            <td>{{$user['surname']}}</td>
                                            <td>{{$user['phone']}}</td>
                                            <td>{{$user['email']}}</td>
                                            <td>
                                                <a class="product_images_column" onclick='getImages("{{implode(" ", $user['images'])}}")' data-bs-toggle="modal" data-bs-target="#carousel-modal">
                                                    @foreach($user['images'] as $image)
                                                        <div style="margin-right: 2px">
                                                            <img src="{{$image}}" alt="" height="50px">
                                                        </div>
                                                    @endforeach
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-around align-items-center height_50 function_buttons">
                                                    <a class="edit_button btn" href="{{route('users.edit', $user['id'])}}">
                                                        <img src="{{asset('img/edit_icon.png')}}" alt="" height="18px">
                                                    </a>
                                                    <a class="edit_button btn" data-bs-toggle="modal" data-bs-target="#fullUserInfoModal" onclick='showUserInfo(
                                                    "{{$user['id']}}",
                                                    "{{$user['name']}}",
                                                    "{{$user['surname']}}",
                                                    "{{$user['middlename']}}",
                                                    "{{$user['phone']}}",
                                                    "{{$user['address']}}",
                                                    "{{$user['gender']}}",
                                                    "{{implode(" ", $user['images'])}}",
                                                    "{{$user['images'][0]}}",
                                                    "{{$user['status']}}",
                                                    "{{$user['role']}}",
                                                    "{{$user['email']}}",
                                                    "{{$user['created_at']}}")'>
                                                        <span class="fa fa-eye font-18"></span>
                                                    </a>
                                                    <button type="button" class="btn delete_button btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#delete_modal" data-url="{{route('users.destroy', $user['id'])}}">
                                                        <img src="{{asset('img/trash_icon.png')}}" alt="" height="18px">
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="change_user_status"
         aria-labelledby="scrollableModalTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle">{{translate_title('Activate or inactivate the user')}}</h5>
                    <a type="button"  data-bs-dismiss="modal" aria-label="Close">X</a>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" action="{{route('change_status')}}" method="POST" novalidate>
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <div class="mb-3 text-center">
                                <label class="form-label"><b>{{translate_title('Status')}}</b></label>
                                <input type="checkbox" id="user_status" data-plugin="switchery" data-color="#3db9dc"/>
                            </div>
                        </div>
                        <input type="hidden" id="userId" name="user_id">
                        <input type="hidden" id="userStatus" name="status">
                        <div class="width_100_percent d-flex justify-content-between mt-4">
                            <button type="button" class="btn modal_close" data-bs-dismiss="modal">{{translate_title('Close')}}</button>
                            <button type="submit" class="btn modal_confirm">{{translate_title('Confirm')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

    <div class="modal fade" tabindex="-1" role="dialog" id="create_modal"
         aria-labelledby="scrollableModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle">{{translate_title('New user')}}</h5>
                    <a type="button"  data-bs-dismiss="modal" aria-label="Close">X</a>
                </div>
                <form class="modal-body needs-validation" action="{{route('users.store')}}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('POST')
                    <div class="position-relative mb-3">
                        <label for="name" class="form-label">{{translate_title('Name')}}</label>
                        <input type="text" id="name" class="form-control" name="name" required>
                        <div class="invalid-tooltip">
                            {{translate_title('Please enter user name')}}
                        </div>
                    </div>
                    <div class="position-relative mb-3">
                        <label for="surname" class="form-label">{{translate_title('Surname')}}</label>
                        <input type="text" id="surname" class="form-control" name="surname" required>
                        <div class="invalid-tooltip">
                            {{translate_title('Please enter user surname.')}}
                        </div>
                    </div>
                    <div class="position-relative mb-3">
                        <label for="middlename" class="form-label">{{translate_title('Middlename')}}</label>
                        <input type="text" id="middlename" class="form-control" name="middlename">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{translate_title('Phone')}}</label>
                        <input type="text" id="phone" class="form-control" name="phone" placeholder="+9987777777">
                    </div>
                    <div class="mb-3">
                        <label for="male">{{translate_title('Male')}}</label>
                        <input type="radio" name="gender" id="male" value="{{\App\Constants::MALE}}" checked class="me-4">
                        <label for="female">{{translate_title('Female')}}</label>
                        <input type="radio" name="gender" id="female" value="{{\App\Constants::FEMALE}}">
                    </div>
                    <div class="mb-3">
                        <label for="image_input" class="form-label">{{translate_title('Images')}}</label>
                        <div class="d-flex">
                            <div class="default_image_content">
                                <img src="{{asset('img/default_image_plus.png')}}" alt="">
                            </div>
                            <span class="ms-1" id="images_quantity"></span>
                        </div>
                        <input type="file" id="image_input" name="images[]" class="form-control d-none" multiple>
                    </div>
                    <div class="position-relative mb-3">
                        <label for="email" class="form-label">{{translate_title('Email')}}</label>
                        <input type="email" id="email" class="form-control" name="email" required>
                        <div class="invalid-tooltip">
                            {{translate_title('Please enter email.')}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="status_">{{translate_title('Status')}}</label>
                        <input type="checkbox" name="status" id="status_">
                    </div>

                    <div class="position-relative mb-3">
                        <label class="form-label">{{translate_title('Region')}}</label>
                        <select name="region_id" class="form-control" id="region_id" required>
                            <option value="" disabled selected>{{translate_title('Select region')}}</option>
                        </select>
                        <div class="invalid-tooltip">
                            {{translate_title('Please enter region.')}}
                        </div>
                    </div>
                    <div class="position-relative mb-3">
                        <label class="form-label">{{translate_title('District')}}</label>
                        <select name="district_id" class="form-control" id="district_id" required>
                            <option value="" disabled selected>{{translate_title('Select district')}}</option>
                        </select>
                        <div class="invalid-tooltip">
                            {{translate_title('Please enter district.')}}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">{{translate_title('Address')}}</label>
                        <input type="text" id="address" class="form-control" name="address">
                    </div>
                    <div class="position-relative mb-3">
                        <label for="password" class="form-label">{{translate_title('Password')}}</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" placeholder="Enter new password" name="password" required>
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                        </div>
                        <div class="invalid-tooltip">
                            {{translate_title('Please enter password.')}}
                        </div>
                    </div>
                    <div class="position-relative mb-3">
                        <label for="password_confirm" class="form-label">{{translate_title('Password confirmation')}}</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password_confirm" class="form-control" placeholder="Confirm password" name="password_confirmation" required>
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                        </div>
                        <div class="invalid-tooltip">
                            {{translate_title('Please enter password confirmation.')}}
                        </div>
                    </div>
                    <input type="hidden" name="region" id="region">
                    <input type="hidden" name="district" id="district">
                    <div class="width_100_percent d-flex justify-content-between mt-5">
                        <button type="button" class="btn modal_close" data-bs-dismiss="modal">{{translate_title('Close')}}</button>
                        <button type="submit" class="btn modal_confirm">{{translate_title('Create')}}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="fullUserInfoModal"
         aria-labelledby="scrollableModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle">{{translate_title('New user')}}</h5>
                    <a type="button"  data-bs-dismiss="modal" aria-label="Close">X</a>
                </div>
                <table class="table table-borderless modal-body">
                    <thead>
                        <tr>
                            <th>{{translate_title('Title')}}</th>
                            <th>{{translate_title('Value')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{translate_title('id')}}</td>
                            <td id="user_info_id"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('name')}}</td>
                            <td id="user_info_name"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('surname')}}</td>
                            <td id="user_info_surname"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('middlename')}}</td>
                            <td id="user_info_middlename"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('phone')}}</td>
                            <td id="user_info_phone"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('address')}}</td>
                            <td id="user_info_address"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('gender')}}</td>
                            <td id="user_info_gender"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('images')}}</td>
                            <td id="user_info_images"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('status')}}</td>
                            <td id="user_info_status"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('role')}}</td>
                            <td id="user_info_role"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('email')}}</td>
                            <td id="user_info_email"></td>
                        </tr>
                        <tr>
                            <td>{{translate_title('created_at')}}</td>
                            <td id="user_info_created_at"></td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div id="carousel-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carousel_product_images">

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">{{translate_title('Previous')}}</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">{{translate_title('Next')}}</span>
                    </a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        let page = false
        let current_region = ''
        let current_district = ''
        if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null){
            localStorage.removeItem('region_id')
        }
        if(localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null){
            localStorage.removeItem('district_id')
        }
        if(localStorage.getItem('region') != undefined && localStorage.getItem('region') != null){
            localStorage.removeItem('region')
        }
        if(localStorage.getItem('district') != undefined && localStorage.getItem('district') != null){
            localStorage.removeItem('district')
        }

        $(document).ready(function () {
            if($('#profession_select') != undefined && $('#profession_select') != null){
                $('#profession_select').select2({
                    dropdownParent: $('#create_modal') // modal ID ni kiriting
                });
            }
        })
        let user_info_id = document.getElementById('user_info_id')
        let user_info_name = document.getElementById('user_info_name')
        let user_info_surname = document.getElementById('user_info_surname')
        let user_info_middlename = document.getElementById('user_info_middlename')
        let user_info_phone = document.getElementById('user_info_phone')
        let user_info_address = document.getElementById('user_info_address')
        let user_info_gender = document.getElementById('user_info_gender')
        let user_info_images = document.getElementById('user_info_images')
        let user_info_status = document.getElementById('user_info_status')
        let user_info_role = document.getElementById('user_info_role')
        let user_info_email = document.getElementById('user_info_email')
        let user_info_created_at = document.getElementById('user_info_created_at')
        function showUserInfo(id, name, surname, middlename, phone,
        address_id, gender, images, image, status, is_admin, email, created_at){
            user_info_id.innerText = id
            user_info_name.innerText = name
            user_info_surname.innerText = surname
            user_info_middlename.innerText = middlename
            user_info_phone.innerText = phone
            user_info_address.innerText = address_id
            user_info_gender.innerText = gender
            user_info_images.innerHTML = `<a class="product_images_column" onclick='getImages("${images}")' data-bs-toggle="modal" data-bs-target="#carousel-modal"> <div style="margin-right: 2px"> <img src="${image}" alt="" height="50px"> </div> </a>`
            user_info_status.innerText = status
            user_info_role.innerHTML = is_admin
            user_info_email.innerText = email
            user_info_created_at.innerText = created_at
        }

        let user_status = document.getElementById('user_status')
        let userId = document.getElementById('userId')
        let userStatus = document.getElementById('userStatus')
    </script>
    <script src="{{asset('js/cities.js')}}"></script>
@endsection
