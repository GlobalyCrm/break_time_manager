@extends('layouts.admin_layout')

@section('title')
    {{ translate_title("Language translate") }}
@endsection
@section('content')
    <div id="loader"></div>
    <div class="main-content-section d-none" id="myDiv">
        <div class="order-section">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item ms-2 mb-2" role="presentation">
                    <a class="nav-link active" id="language-tab" data-bs-toggle="tab" href="#language" role="tab" aria-controls="language" aria-selected="true">{{translate_title('Translate')}}</a>
                </li>
                <li class="nav-item ms-2 mb-2" role="presentation">
                    <a class="nav-link" id="table-translate-tab" data-bs-toggle="tab" href="#table-translate" role="tab" aria-controls="table-translate" aria-selected="false">{{translate_title('Table translate')}}</a>
                </li>
            </ul>
            <div class="card mt-4">
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="language" role="tabpanel" aria-labelledby="language-tab">
                            <form class="parsley-examples" action="{{ route('env_key_update.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <h2>{{ translate_title('Default language') }}</h2>
                                    </div>
                                    <div class="col-md-2 ">
                                        <div class=" mt-2">
                                            <input type="hidden" name="types[]" value="DEFAULT_LANGUAGE">
                                            <select  class="form-select"    id="country" name="DEFAULT_LANGUAGE">
                                                @foreach ($languages as $key => $language)
                                                    <option value="{{ $language->code??'' }}" <?php if (env('DEFAULT_LANGUAGE') == $language->code??'') {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $language->name??'' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn edit_button mt-2">{{ translate_title('Save') }}</button>
                                    </div>
                                </div>

                            </form>
                            <table class="table mt-2" style="text-align:center !important">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="row">№</th>
                                        <td>{{ translate_title('Language') }}</td>
                                        <td>{{ translate_title('Code') }}</td>
                                        <td>{{ translate_title('Action') }}</td>
                                    </tr>
                                </thead>
                                <tbody class="text-align:center !important">
                                @empty(!$languages)
                                    @php
                                        $i = 1;
                                    @endphp

                                    @foreach ($languages as $value)
                                        <tr>
                                            <th scope="row">{{ $i++ }}</th>
                                            <td> {{ $value->name??'' }}</td>
                                            <td>{{ $value->code??'' }}</td>
                                            <td>
                                                <a href="{{ route('language.show', $value->id) }}"
                                                   title="{{ translate_title('Translation') }}"  >
                                                    <button type="button" class="btn edit_button waves-effect waves-light">
                                                        <i class="fa fa-language"></i>
                                                    </button>
                                                </a>
                                                <a href="{{ route('language.edit', encrypt($value->id)) }}">
                                                    <button type="button" class="btn edit_button waves-effect waves-light">
                                                        <img src="{{asset('img/edit_icon.png')}}" alt="" height="18px">
                                                    </button>
                                                </a>
                                                @if ($value->code != 'en')
                                                    <button type="button" class="btn delete_button" data-bs-toggle="modal" data-bs-target="#delete_modal" data-url="{{ route('language.destroy', $language->id) }}">
                                                        <img src="{{asset('img/trash_icon.png')}}" alt="" height="18px">
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endempty
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="table-translate" role="tabpanel" aria-labelledby="table-translate-tab">
                            <div class="justify-content-center">
                                <ul class="translation_content">
                                    <li class="translation_list">
                                        <a href="{{ route('table.show', 'city') }}"><div class="translation_menu">{{translate_title('City translate')}}</div></a>
                                    </li>
                                    <li class="translation_list">
                                        <a href="{{ route('table.show', 'category') }}"><div class="translation_menu">{{translate_title('Category translate')}}</div></a>
                                    </li>
                                    <li class="translation_list">
                                        <a href="{{ route('table.show', 'color') }}"><div class="translation_menu">{{translate_title('Color translate')}}</div></a>
                                    </li>
                                    <li class="translation_list">
                                        <a href="{{ route('table.show', 'product') }}"><div class="translation_menu">{{translate_title('Product translate')}}</div></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/language.js') }}"></script>

@endsection

