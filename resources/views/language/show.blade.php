@extends('layouts.admin_layout')

@section('title')
    {{ translate_title("Language translate") }}
@endsection
@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('translation.save') }}" method="POST">
                @csrf
                <input type="hidden" id="language_code" value="{{ $language->code??'' }}">
                <input type="hidden" name="id" value="{{ $language->id??'' }}">

                <div class="right_button_create">
                    <h4 class="">{{ $language->name??'' }}</h4>
                </div>
                <table class="table datatable table-striped table-bordered dt-responsive nowrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate_title('Key') }}</th>
                        <th> {{ translate_title('Translation') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if (count($lang_keys) > 0)
                        @php
                            $n = 1;
                        @endphp
                        @foreach ($lang_keys as $key => $translation)
                            <tr>
                                <td>{{ $n++ }}</td>
                                <td class="lang_key">{{ $translation->lang_key??'' }}</td>
                                <td class="lang_value">
                                    <input type="text" class="form-control value" id="input"
                                           style="width:100%" name="values[{{ $translation->lang_key??'' }}]"
                                           @if (($traslate_lang = \App\Models\Translation::where('lang', $language->code??'')->where('lang_key', $translation->lang_key??'')->first()) != null) value="{{ $traslate_lang->lang_value??'' }}" @endif>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="row ">
                    <div class="col-xl-6 col-md-6">

                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="form-group mt-2 text-right">
                            <button type="button" class="btn edit_button"
                                    onclick="copyTranslation()">{{ translate_title('Copy Translations') }}</button>
                            <button type="submit" class="btn delete_button">{{ translate_title('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


<script src="{{ asset('js/language.js') }}"></script>

@endsection
