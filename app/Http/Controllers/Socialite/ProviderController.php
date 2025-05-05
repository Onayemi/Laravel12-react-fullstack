<?php

namespace App\Http\Controllers\Socialite;

use \Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    // public function providerRedirect(string $provider) {
    public function providerRedirect($provider) {
        if(!in_array($provider, ['github','google'])){
            return redirect(route('login'))->withErrors('provider', 'Invalid Provider');
         }
         
         try {
           return Socialite::driver($provider)->redirect();
         }catch(Exception $e){
            return redirect(route('login'))->withErrors('provider', 'Something went wrong');
         }
    }

    public function providerCallback($provider) {

        if(!in_array($provider, ['github','google'])){
            return redirect(route('login'))->withErrors('provider', 'Invalid Provider');
         }
         
        $socialUser = Socialite::driver($provider)->user();
 
        $user = User::updateOrCreate([
            'provider_id' => $socialUser->id,
            'provider_name' => $socialUser,
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
