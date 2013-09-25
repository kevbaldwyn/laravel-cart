<?php namespace KevBaldwyn\LaravelCart;

use Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot() {
		
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
		//Config::package('kevbaldwyn/laravel-cart', __DIR__.'/../../config');

		$this->app->bind('kevbaldwyn.laravelcart', function() {
			return new LaravelCart();
		});

	}




	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('kevbaldwyn.laravelcart');
	}

}