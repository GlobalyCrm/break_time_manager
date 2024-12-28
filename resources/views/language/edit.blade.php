@extends('layouts.admin_layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
        <form action="{{route('language.update',$language->id??'')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-7">
                    <div class="">
                        <label class="form-label">{{translate_title('Name')}}</label>

                        <input type="text" name="name" value="{{$language->name??''}}" class="form-control" required placeholder="{{translate_title('Type something')}}" />
                    </div>
                </div>
                <div class="col-md-2">

                </div>
                <div class="col-md-3">
                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn edit_button">{{translate_title('Submit')}}</button>
                    </div>
                </div>
            </div>
        </form>

@endsection
