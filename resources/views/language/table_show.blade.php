@extends('layouts.admin_layout')

@section('title')
    {{ translate_title(" $type Translation") }}
@endsection
@section('content')
    <form class="form-horizontal" action="{{ route('table_translation.save') }}" method="POST">
        @csrf
        <input type="hidden" id="language_code" value="{{ $language->code??'' }}">
        <input type="hidden" name="id" value="{{ $language->id }}">
        <input type="hidden" name="type" value="{{ $type??''}}">
        <h5 class="">{{ $language->name??'' }}</h5>
        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
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
                        <td class="lang_key">{{ $translation->name??'' }}</td>
                        <td class="lang_value">

                            @switch($type)

                                @case('city')
                                    @php
                                        $translate_lang = \App\Models\CityTranslations::where('lang', $language->code??'')->where('city_id', $translation->city_id??'')->first();
                                    @endphp
                                    <input type="text" class="form-control value" id="input"
                                    style="width:100%" name="values[{{ $translation->city_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break

                                @case('category')
                                    @php
                                       $translate_lang = \App\Models\CategoryTranslations::where('lang', $language->code??'')->where('category_id', $translation->category_id??'')->first();
                                    @endphp
                                    <input type="text" class="form-control value" id="input"
                                    style="width:100%" name="values[{{ $translation->category_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break

                                @case('color')
                                    @php
                                        $translate_lang = \App\Models\ColorTranslations::where('lang', $language->code??'')->where('color_id', $translation->color_id??'')->first();
                                    @endphp
                                    <input type="text" class="form-control value" id="input"
                                    style="width:100%" name="values[{{ $translation->color_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break
                                @case('product')
                                    @php
                                        $translate_lang = \App\Models\ProductTranslations::where('lang', $language->code??'')->where('product_id', $translation->product_id??'')->first();
                                    @endphp
                                    <input type="text" class="form-control value" id="input"
                                    style="width:100%" name="values[{{ $translation->product_id }}]"
                                    @if (($translate_lang) != null) value="{{ $translate_lang->name }}" @endif>

                                    @break
                                @default
                                    <span>Something went wrong, please try again</span>
                            @endswitch

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

    <script src="{{ asset('js/language.js') }}"></script>

@endsection
