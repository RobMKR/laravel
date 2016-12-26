<?php

namespace App\Providers;
use Validator;
use Illuminate\Support\ServiceProvider;
use App\User as User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Validating Old Password
        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {
            return \Hash::check($value, current($parameters));
        });

        // Validating Custom
        Validator::extend('user_in_department', function ($attribute, $value, $parameters, $validator) {
            $user = User::find($value);
            if(!$user || $user->in_department !== (int) $parameters[0]){
                return false;
            }
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
