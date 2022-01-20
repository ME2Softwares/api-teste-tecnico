<?php

namespace App\Providers;

use App\Http\Validators\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {

            $headers = [
                'userid' => $request->headers->get('user-id'),
                'apitoken' => $request->headers->get('api-token'),
            ];

            (new UserRequest)->validateHeaders($headers);

            $user = User::findOrFail($headers['userid']);

            if (Hash::check($user->api_token, $headers['apitoken'])) {
                return $user;
            }

            return null;
        });
    }
}
