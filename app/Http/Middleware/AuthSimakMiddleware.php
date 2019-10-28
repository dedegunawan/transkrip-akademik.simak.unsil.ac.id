<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class AuthSimakMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session_code = session('session_code');
        if (!$session_code || !$this->getSessionData()) {
            return $this->redirectToLoginUrl();
        }
        return $next($request);
    }

    public function getSessionData()
    {
        $url = env(
            'SESSION_CHECKER',
            'https://simak.unsil.ac.id/session.php?menu=current_session&app=transkrip-akademik'
        );
        $url = $url.session('session_code');
        $last_check = session('last_check', 0);
        $time_expired = 60; // in second
        if($last_check && time() <= $last_check+$time_expired) return true;
        $guzzle = new Client();
        $data = $guzzle->get($url, [
            'verify' => false
        ]);
        $datas = \GuzzleHttp\json_decode($data->getBody(), true);
        $status = @$datas['status'];

        if ($status) {
            $this->checkIsRightPermission($datas);
            session()->put($datas);
            session()->put('last_check', time());
            return true;
        }
        $login_url = @$datas['login_url'];
        if ($login_url) {
            return Redirect::away($login_url)->send();
        }
        return false;

    }

    public function checkIsRightPermission($datas) {

    }

    public function redirectToLoginUrl()
    {
        $url = env(
            'LOGIN_URL',
            'https://simak.unsil.ac.id/session.php?menu=current_session&app=transkrip-akademik'
        );
        return redirect($url);
    }
}
