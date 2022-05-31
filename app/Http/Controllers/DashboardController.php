<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller{
    /** index */
        public function index(Request $request){
            return view('dashboard');
        }
    /** index */
}
