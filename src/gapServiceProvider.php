<?php

namespace splittlogic\gap;

use Illuminate\Support\ServiceProvider;

use splittlogic\gap\Console\gapInstall;

class gapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

      $this->loadViewsFrom(__DIR__.'/../resources/views', 'gap');
  		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
      /*
       * Optional methods to load your package assets
       */
       // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

  		if ($this->app->runningInConsole()) {
  			$this->bootForConsole();
  		}

      // Register gapInstall
      if ($this->app->runningInConsole()) {
          $this->commands([
              gapInstall::class,
          ]);
      }

    }

    /**
     * Register the application services.
     */
    public function register()
    {

      $this->mergeConfigFrom(__DIR__.'/../config/gap.php', 'gap');

  		$this->app->singleton('gap', function ($app) {
  			return new gap;
  		});

    }

    public function provides()
  	{

  		return ['gap'];

  	}

    protected function bootForConsole()
  	{

  		// Publishing the configuration file.
  		$this->publishes([
  			__DIR__.'/../config/gap.php' => config_path('gap.php'),
  		], 'gap.config');

  		// Publishing the views.
      $this->publishes([
          __DIR__.'/../resources/views' => base_path('resources/views/vendor/splittlogic'),
      ], 'gap.views');

      // Publishing assets.
      $this->publishes([
          __DIR__.'/../resources/assets' => public_path('vendor/splittlogic'),
      ], 'public');

  	}
}
