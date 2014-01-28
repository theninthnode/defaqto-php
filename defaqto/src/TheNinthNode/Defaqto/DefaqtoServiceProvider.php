<?php namespace TheNinthNode\Defaqto;

use Illuminate\Support\ServiceProvider;

class DefaqtoServiceProvider extends ServiceProvider {

	/**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['defaqto'] = $this->app->share(
            function ($app) {
                return new Defaqto;
            }
        );
    }
}