@extends('layouts.admin_layout')

@section('title')
    {{ translate_title('Currency') }}
@endsection
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
    crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" crossorigin="anonymous">
@section('content')
    <div class="d-flex aad">
        <div class="mainMargin">
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <a href="{{ route('forthebuilder.settings.index') }}"
                        class="plus2 profileMaxNazadInformatsiyaKlient"><img
                            src="{{ asset('/backend-assets/forthebuilders/images/icons/arrow-left.png') }}"
                            alt=""></a>
                    <h2 class="panelUprText">{{ translate_title('Language') }}</h2>
                </div>
            </div>

            <div class="nastroykiData">
                <form class="form-horizontal" action="" method="POST">
                    @csrf
                    <div class="d-flex">
                        <h2 class="panelUprText yazik_poUmolchaniya yazikPo_umolchaniya">{{ translate_title('Default language') }}
                        </h2>

                        <select class="yazikHeader demo-select2-placeholder" id="country" name="DEFAULT_LANGUAGE">
                            @foreach ($languages as $key => $language)
                                <option value="{{ $language->code??'' }}" <?php if (env('DEFAULT_LANGUAGE') == $language->code??'') {
                                    echo 'selected';
                                } ?>>
                                    {{ $language->name??'' }}
                                </option>
                            @endforeach
                        </select>
                        <button class="yazik_soxranitBtn">{{ translate_title('Save') }}</button>
                    </div>
                </form>
                <div class="sozdatRassrochkaDataUae">
                    <div class="checkboxDivInput">
                        №
                    </div>
                    <div class="checkboxDivTextInput3565">
                        {{ translate_title('Language') }}
                    </div>
                    <div class="checkboxDivTextInput3565">
                        {{ translate_title('Code') }}
                    </div>
                    <div class="checkboxDivTextInput35652">
                        {{ translate_title('Action') }}
                    </div>
                </div>
                @empty(!$languages)
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($languages as $value)
                        <div class="sozdatRassrochkaDataUae2">
                            <div class="checkboxDivInput">
                                {{ $i++ }}
                                @php
                                    $i = $i;
                                @endphp
                            </div>
                            <div class="checkboxDivTextInput3565">
                                {{ $value->name }}
                            </div>
                            <div class="checkboxDivTextInput3565">
                                {{ $value->code }}
                            </div>
                            <div class="checkboxDivTextInput35652">
                                <div class="seaDiv">
                                    <a href="{{ route('forthebuilder.language.show', encrypt($value->id)) }}"
                                        title="{{ translate_title('Translation') }}">
                                        <img class="mt-1" width="20" height="20"
                                            src="{{ asset('/backend-assets/forthebuilders/images/translate.png') }}"
                                            alt="Trash">
                                    </a>
                                </div>
                                <div class="seaDiv">
                                    <a href="{{ route('forthebuilder.language.edit', encrypt($value->id)) }}">
                                        <img class="mt-1" width="20" height="20"
                                            src="{{ asset('/backend-assets/forthebuilders/images/edit.png') }}">
                                    </a>
                                </div>
                                @if ($value->code != 'en')
                                    <div class="seaDiv">
                                        <a href="{{ route('forthebuilder.language.destroy', encrypt($value->id)) }}"
                                            @disabled(true)>
                                            <img class="mt-1" width="20" height="20"
                                                src="{{ asset('/backend-assets/forthebuilders/images/trash.png') }}"
                                                alt="Trash">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <form action="{{ route('languages.store') }}" method="POST">
                        @csrf
                        <div class="sozdatRassrochkaDataUae2">
                            <div class="checkboxDivInput">
                                {{ $i }}
                            </div>
                            <div class="checkboxDivTextInput3565">
                                <input style="border:none; height:46px;" type="text" class="form-control" name="name"
                                    placeholder="{{ translate_title('Name') }}" required>
                            </div>
                            <div class="checkboxDivTextInput3565">
                                <select style="border:none; height:46px;" class="form-control" id="id_select2_example"
                                    name="code" style="width:100%">
                                    @foreach (\File::files(base_path('public/uploads/flags')) as $path)
                                        <option data-value="{{ pathinfo($path)['filename'] }}"
                                            name="{{ pathinfo($path)['filename'] }}"
                                            value="{{ pathinfo($path)['filename'] }}">
                                            {{ pathinfo($path)['filename'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="checkboxDivTextInput35652">
                                <div class="seaDiv">
                                    <button type="submit" style="background: transparent; border:none">
                                        <img class="mt-1" width="20" height="20"
                                            src="{{ asset('/backend-assets/forthebuilders/images/Verifed.png') }}"
                                            alt="Trash">
                                    </button>
                                </div>
                                <div class="seaDiv">
                                    <a href="{{ route('forthebuilder.language.index') }}" @disabled(true)>
                                        <img class="mt-1" width="20" height="20"
                                            src="{{ asset('/backend-assets/forthebuilders/images/trash.png') }}"
                                            alt="Trash">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                @endempty
            </div>
        </div>
    </div>
    <script>
        let page_name = 'currency';
    </script>
@endsection
@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.js"></script>
    <script type="text/javascript">
        let page_name = 'language';

        function custom_template(obj) {
            var data = $(obj.element).data();
            var text = $(obj.element).text();
            if (data && data['value']) {

                template = $("<div><img src='/uploads/flags/" + data['value'] +
                    ".png' style='width:30px; height:20px;'/><b text-align:center; padding-left:10px>" + text +
                    "</b></div>");
                return template;
            }
        }
        var options = {
            'templateSelection': custom_template,
            'templateResult': custom_template,
        }
        $('#id_select2_example').select2(options);
        $('.select2-container--default .select2-selection--single').css({
            'height': '200px'
        });
    </script>
@endsection
