<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use Config;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller {
    public function index(Request $request,$locale){
      //set’s application’s locale
      app()->setLocale($locale);
      
      //Gets the translated message and displays it
      echo trans('lang.features');
   }

   public function switchLang($lang) {
        if (array_key_exists($lang, Config::get('app.locales'))) {
            Session::set('locale', $lang);
        }

        /*echo "calling";
        exit();*/
/*
        dd(redirect()->back());
        echo redirect()->back();

        exit();*/
        return Redirect::back();
    }
}
