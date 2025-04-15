<?php

namespace App\Http\Controllers\Navbar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NavbarController extends Controller
{
    //about
    public function about(){
        return view('navbar.about');
    }
    //about
    public function contact(){
        return view('navbar.contact');
    }
}
