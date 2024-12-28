<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Contracts\Support\Renderable;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Models\Language;
use App\Models\Translation;
use App\Models\LanguageTranslation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Utils\Paginate;



class LanguageController extends Controller
{
    public $title;
    public $current_page = 'settings';
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct()
    {
        $this->title = $this->getTableTitle('Settings');
    }

    public function changeLanguage(Request $request)
    {
        if(isset($request->locale)){
            $request->session()->put('locale', $request->locale);
            app()->setLocale($request->locale);
        }
        //  flash(translate_title('Language changed to ') . $language->name)->success();
    }



    public function env_key_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
                $this->overWriteEnvFile($type, $request[$type]);
        }
        return back();
    }



    public function overWriteEnvFile($type, $val)
    {
        //TODO::fixing server base_path
        try{
            // if(env('DEMO_MODE') != 'On'){
                $type=str_replace(' ', '_', $type);
                $path = base_path('.env');
                // dd($type);
                // dd($val);
                if (file_exists($path)) {
                    $val = '"'.trim($val).'"';
                    // dd($val);
                    if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                        file_put_contents($path, str_replace(
                            $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                        ));
                        // file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
                    }
                    else{
                        file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
                    }
                }
            // }
        }catch(Exception $e){

        }

    }




    // public function defaultLanguage(Request $request)
    // {
    //     return $request->all();
    //     $default_language=env('DEFAULT_LANGUAGE');
    //     $default_language = $language->code;
    //     $default_language->save();

    //     $request->session()->put('locale', $request->locale);
    //     $language = Language::where('code', $request->locale)->first();
    //     return redirect()->back();
    //     //  flash(translate_title('Language changed to ') . $language->name)->success();
    // }

    public function index()
    {
        $languages = Language::orderBy('id', 'ASC')->get();
        return view('language.index', [
            'languages' => $languages,
            'title'=>$this->title,
            'current_page' => $this->current_page,
        ]);


    }
    public function show(Request $request, $id)
    {
        $sort_search = null;
        $language = Language::findOrFail($id);
        $lang_keys = Translation::where('lang', env('DEFAULT_LANGUAGE', 'uz'))->get();
        if ($request->has('search')) {
            $sort_search = $request->search;
            // $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
            $lang_keys = $lang_keys->where('lang_key', request()->input('search'));
        }
        // $lang_keys = $lang_keys->paginate(10);
        // $lang_keys = $lang_keys->orderByDesc()->paginate(10);



        return view('language.show', [
            'language' => $language,
            'lang_keys' => $lang_keys,
            'sort_search' => $sort_search,
            'title'=>$this->title,
            'current_page' => $this->current_page,
        ]);
    }




    public function translation_save(Request $request)
    {
        $language = Language::findOrFail($request->id);
        foreach ($request->values as $key => $value) {
            $translation_def = Translation::where('lang_key', $key)->where('lang', $language->code)->first();
            if ($translation_def == null) {
                $translation_def = new Translation;
                $translation_def->lang = $language->code;
                $translation_def->lang_key = $key;
                $translation_def->lang_value = $value;
                $translation_def->save();
            } else {
                $translation_def->lang_value = $value;
                $translation_def->save();
            }
        }

        return back();
    }




    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $languages = Language::get();
        return view('language.create', [
            'languages'=>$languages,
            'title'=>$this->title,
            'current_page' => $this->current_page,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $language = Language::updateOrCreate(
            ['name' => $request->name, 'code' => $request->code]
        );
        // $language->name = $request->name;
        if ($language->save()) {

            foreach (Language::all() as $language) {
                // Language Translations
                $language_translations = LanguageTranslation::firstOrNew(['lang' => $language->code, 'language_id' => $language->id]);
                $language_translations->name = $language->name;
                $language_translations->save();
            }

            return redirect()->route('language.index');
        }
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function languageEdit($id)
    {

        // $languages = Language::get();
        $language = Language::findOrFail(decrypt($id));
        return view('language.edit', [
            'language'=>$language,
            'title'=>$this->title,
            'current_page' => $this->current_page,
        ]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request ,$id)
    {
        $language = Language::where('id', $id)->first();
        $language->name = $request->name;
        if ($language->save()) {

            if (LanguageTranslation::where('language_id', $language->id)->where('lang', default_language())->first()) {
                foreach (Language::all() as $language) {
                    $language_translations = LanguageTranslation::firstOrNew(['lang' => $language->code, 'language_id' => $language->id]);
                    $language_translations->name = $request->name;
                    $language_translations->save();
                }
            }
            return redirect()->route('language.index');
        }
    }

    public function languageDestroy($id)
    {
        $language = Language::findOrFail($id);
        if (env('DEFAULT_LANGUAGE', 'ru') == $language->code) {
            return back();
        } else {
            $language->delete();
        }
        return redirect()->route('language.index');
    }


    public function updateValue(Request $request)
    {
        $tr = new GoogleTranslate;
        return GoogleTranslate::trans($request->status, $request->code);
    }

    protected function Paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
