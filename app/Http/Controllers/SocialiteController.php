<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToSocialite(string $provider) {
        // dd($provider);
        if(!in_array($provider, ['github', 'google'])) {
            return redirect(route('login'))->withErrors(['provider' => 'Invalid provider']);
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch(\Exception $e) {
            return redirect(route('login'))->withErrors(['provider' => 'Something went wrong']);
        }
    }

    public function handleCallback(string $provider) {

        if(!in_array($provider, ['github', 'google'])) {
            return redirect(route('login'))->withErrors(['provider' => 'Invalid provider']);
        }

        $socialUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate([
            'provider_id' => $socialUser->id,
            'provider_name' => $provider,
        ], [
            'name' => $socialUser->name,
            'email' => $socialUser->email,
            'provider_token' => $socialUser->token,
            'provider_refresh_token' => $socialUser->refreshToken,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
