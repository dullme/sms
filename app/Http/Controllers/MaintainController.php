<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Request;

class MaintainController extends ResponseController
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

    public function byICCIDFindAmount($iccid)
    {
        $card =  Card::where('name', $iccid)->select('name as iccid', 'amount')->first();
        if($card){
            return $this->responseSuccess($card);
        }

        return $this->responseError('error');
    }
}
