<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
#use App\Http\Validators\OriginalValidator;  #追加

class OperatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        #オリジナルのバリデータを組み込む。
        /*
        $validator = $this->app['validator'];
        $validator->resolver(function($translator,$data,$rules,$messages)){
            return new OriginalValidator($translator,$data,$rules,$messages);
        }*/

    }
}
