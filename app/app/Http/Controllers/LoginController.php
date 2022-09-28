<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

use App\User;
use App\IdentityProvider;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only(["email", "password"]))) {
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'failed'], 400);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out'], 200);
    }

    public function isAdmin()
    {
        $user = auth()->user();
        if(!$user){
            return response()->json(['result' => false], 200);
        }

        $user_admin_flag = $user->admin_flag;
        if($user_admin_flag == 1) {
            return response()->json(['result' => true], 200);
        } elseif($user_admin_flag == 0) {
            return response()->json(['result' => false], 200);
        } else {
            return response()->json(['result' => 'failed'], 400);
        }
    }

    public function isAuth()
    {
        $user = auth()->user();

        if($user) {
            return response()->json(['result' => true], 200);

        } else {
            return response()->json(['result' => false], 200);
        }
    }

    public function hasScreenName()
    {
        $screen_name = auth()->user()->screen_name;
        if($screen_name) {
            return response()->json(['result' => true], 200);

        } else {
            return response()->json(['result' => false], 200);
        }
    }

    public function redirectToProvider($social)
    {
        try {
            return Socialite::driver($social)->redirect()->getTargetUrl();
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return $authUser;
    }

    public function findOrCreateUser($providerUser, $provider)
    {
        $account = IdentityProvider::whereProviderName($provider)
                    ->whereProviderId($providerUser->getId())
                    ->first();
        if ($account) {
            return $account->user;
        } else {
            $user = User::whereEmail($providerUser->getEmail())->first();
            if (!$user) {
                if($provider == 'google'){
                    $user = User::create([
                        'email' => $providerUser->getEmail(),
                        'name'  => $providerUser->getName(),
                    ]);

                    $user->IdentityProviders()->create([
                        'provider_id'   => $providerUser->getId(),
                        'provider_name' => $provider,
                    ]);

                    return $user;

                } else if($provider == 'twitter'){
                    $user = User::create([
                        'name'  => $providerUser->getName(),
                    ]);

                    $user->IdentityProviders()->create([
                        'provider_id'   => $providerUser->getId(),
                        'provider_name' => $provider,
                    ]);

                    return $user;
                }
            }
            $user->IdentityProviders()->create([
                'provider_id'   => $providerUser->getId(),
                'provider_name' => $provider,
            ]);
            return $user;
        }
    }
}
