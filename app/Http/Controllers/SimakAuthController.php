<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimakAuthController extends Controller
{
    public function check()
    {
        $session_code = @$_GET['code'];
        if ($session_code) session()->put(compact('session_code'));
        return redirect('/');
    }
}
