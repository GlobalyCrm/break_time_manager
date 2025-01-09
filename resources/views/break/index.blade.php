@extends('layouts.layouts')
@section('content')
    <style>
        .break_time_page {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
        }

        /*.video-background {*/
        /*    position: fixed;*/
        /*    top: 0;*/
        /*    left: 0;*/
        /*    width: 100%;*/
        /*    height: 100%;*/
        /*    overflow: hidden;*/
        /*    z-index: -1; !* Video orqada qoladi *!*/
        /*}*/

        /*.video-background video {*/
        /*    min-width: 100%;*/
        /*    min-height: 100%;*/
        /*    object-fit: cover; !* Video kattalashib, joyni to‘ldiradi *!*/
        /*}*/

        .break_time_page a[type='button']{
            z-index: 6;
        }
        .break_time_info{
            position: absolute;
            background-color: rgb(0, 0, 0, 0.4);
            width: 100%;
            height: calc(100% - 104px);
        }
        .break_time_content{
            height: 244px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white !important;
        }
        #timer{
            color: white !important;
            z-index: 7;
            font-size: 94px;
        }

    </style>
    <div class="break_time_page">
        <div class="break_time_info">
            <div class="break_time_content">
                <h1 id="timer">00:00:00</h1>
            </div>
        </div>
{{--        <div class="video-background">--}}
{{--            <video autoplay loop muted playsinline>--}}
{{--                <source src="{{asset('images/back/christmas_tree.mp4')}}" type="video/mp4">--}}
{{--            </video>--}}
{{--        </div>--}}
    </div>

    <script>
        let user_status = document.getElementById('user_status')
        let active_status_content = document.getElementById('active_status_content')
        let disactive_status_content = document.getElementById('disactive_status_content')
        let selected_status = "{{$user_self['status_']}}"
        let user_id = "{{$user_self['id']}}"
        let isPlayed = selected_status


        let disactivate = "{{\App\Constants::NOT_ACTIVE}}"

        let timer;


        function getCorrectSeconds(param_user_id){
            let response_data = ''
            // AJAX so'rovini yuborish
            $.ajax({
                url: "/../api/get-correct-seconds",
                method: 'GET',
                data: {
                    'user_id':param_user_id
                },
                success: function (data) {
                    console.log(data)
                    response_data = data
                    if(response_data.status == true){
                        seconds = parseInt(data.seconds)
                        if(data.break_status == 'active'){
                            isPlayed = true
                        }else if(data.break_status == 'not_active'){
                            isPlayed = false
                        }else{
                            isPlayed = false
                        }
                        clearInterval(timer);
                        startTimer()
                    }
                },
                error: function (xhr, status, error) {
                    // Xatolarni qayta ishlash
                    console.log(xhr.responseText);
                    console.log(error)
                    toastr.error('An error occurred: ' + xhr.status + ' ' + error);
                }
            });
        }
        if(isPlayed){
            setInterval(function () {
                if(isPlayed){
                    getCorrectSeconds(user_id)
                }
            }, 30000)
        }
        let seconds = parseInt('{{$interval_day}}');
        const timerDisplay = document.getElementById("timer");
        if(selected_status == "{{\App\Constants::ACTIVE}}"){
            isPlayed = true
            clearInterval(timer);
            startTimer();
        }else if(selected_status == "{{\App\Constants::NOT_ACTIVE}}"){
            isPlayed = false
            clearInterval(timer);
            startTimer();
        }

        let statusText = ''
        function changeButtons(selectedStatus){
            statusText = ''
            if(selectedStatus == '{{\App\Constants::ACTIVE}}'){
                if(disactive_status_content.classList.contains('d-none')){
                    disactive_status_content.classList.remove('d-none')
                }
                if(!active_status_content.classList.contains('d-none')){
                    active_status_content.classList.add('d-none')
                }
                statusText = "{{translate_title('Break has begun')}}"
                isPlayed = true;
                console.log('yondi')
                clearInterval(timer);
                startTimer()
            }else if(selectedStatus == '{{\App\Constants::NOT_ACTIVE}}'){
                if(active_status_content.classList.contains('d-none')){
                    active_status_content.classList.remove('d-none')
                }
                if(!disactive_status_content.classList.contains('d-none')){
                    disactive_status_content.classList.add('d-none')
                }
                statusText = "{{translate_title('Break has stopped')}}"
                isPlayed = false;
                console.log("o'chdi")
                clearInterval(timer);
                startTimer()
            }
        }
        function activateOrDisactivate(status) {
            changeStatus(status, user_id)
            if(isPlayed){
                setInterval(function () {
                    if(isPlayed){
                        getCorrectSeconds(user_id)
                    }
                }, 30000)
            }
        }

        function changeStatus(selectedStatus, userId){
            let response_data = ''
            // AJAX so'rovini yuborish
            $.ajax({
                url: "/../api/change/status",
                method: 'POST',
                data: {
                    'status':selectedStatus,
                    'user_id':userId
                },
                success: function (data) {
                    response_data = data
                    if(response_data.status == true){
                        changeButtons(selectedStatus)
                        if(data.break_status = 'active'){
                            toastr.success(statusText);
                        }else{
                            toastr.warning(statusText);
                        }
                    }else{
                        toastr.error('Something got wrong! try again');
                    }
                },
                error: function (xhr, status, error) {
                    // Xatolarni qayta ishlash
                    console.log(xhr.responseText);
                    toastr.error('An error occurred: ' + xhr.status + ' ' + error);
                }
            });
        }
        // Sekundlarni soat, minut, sekundga aylantirish va ekranga chiqarish
        function displayTime() {
            const hrs = String(Math.floor(seconds / 3600)).padStart(2, "0");
            const mins = String(Math.floor((seconds % 3600) / 60)).padStart(2, "0");
            const secs = String(seconds % 60).padStart(2, "0");
            timerDisplay.textContent = `${hrs}:${mins}:${secs}`;
        }

        displayTime()
        // Timer boshlash funksiyasi
        function startTimer() {
            if (isPlayed) { // Agar pause qilinmagan bo'lsa
                clearInterval(timer);
                timer = setInterval(() => {
                    seconds++;
                    displayTime();
                }, 1000);
            }else{
                clearInterval(timer);
            }
        }

        window.addEventListener('beforeunload', function (event) {
            changeStatus(disactivate, user_id)
        });
    </script>
@endsection
