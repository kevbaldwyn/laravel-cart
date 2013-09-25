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
		$app = $this->app;
		$this->app['kevbaldwyn.laravelcart.model'] = $this->app->share(function($app)
		{
			//$model = $app['config']['kevbaldwyn/laravel-cart::cart.model'];
			$model = '\KevBaldwyn\LaravelCart\Models\Basket';
			return new $model;
		});

		$this->app->bind('kevbaldwyn.laravelcart', function() use($app) {
			return new LaravelCart($app['kevbaldwyn.laravelcart.model']);
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