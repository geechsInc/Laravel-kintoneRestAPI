<?php namespace Geechs\KintoneRestApi;

use Illuminate\Support\ServiceProvider;

class KintoneRestApiServiceProvider extends ServiceProvider {

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
		$this->publishes([
        	__DIR__.'/config/kintone-rest-api.php' => config_path('kintone-rest-api.php'),
	    ]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['KintoneRestApi'] = $this->app->share(function($app)
	    {
	      return new KintoneRestApi;
	    });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
