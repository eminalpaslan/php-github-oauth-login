<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGithub()
    {
        if (auth()->check()) {
            auth()->logout();
        }
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        // PHP 5.6 uyumlu: null coalescing (??) yerine ternary/boşluk kontrolü
        $name = $githubUser->getName();
        if (!isset($name) || $name === '') {
            $name = $githubUser->getNickname();
        }

        $user = User::firstOrCreate(
            ['email' => $githubUser->getEmail()],
            ['name'  => $name]
        );

        auth()->login($user);

        return redirect('/hosgeldin');
    }
}
