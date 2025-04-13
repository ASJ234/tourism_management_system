<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class landingpageController extends Controller
{
    public function about()
    {
        
        return view('landingpage.about');
    }
    public function contact()
    {
        
        return view('landingpage.contact');
    }
}
