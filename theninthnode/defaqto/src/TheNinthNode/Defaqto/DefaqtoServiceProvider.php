<?php namespace TheNinthNode\Defaqto;

use Illuminate\Support\ServiceProvider;

class DefaqtoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('theninthnode/defaqto');
    }

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

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('defaqto');
    }

}