<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintainController extends Controller
{

    public function index()
    {
        if(config('maintain') == 'up'){
            return redirect()->to(url('/home'));
        }
        return view('down');
    }

    public function edition()
    {
        return view('edition');
    }
}
