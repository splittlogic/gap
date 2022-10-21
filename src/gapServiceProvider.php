<?php

namespace splittlogic\gap;

use Illuminate\Support\ServiceProvider;

use splittlogic\gap\Console\gapInstall;
use splittlogic\gap\Console\ScriptLogging;

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
      $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

  		if ($this->app->runningInConsole()) {
  			$this->bootForConsole();
  		}

      // Register Console Commands
      if ($this->app->runningInConsole()) {
          $this->commands([
              gapInstall::class,
              ScriptLogging::class,
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
      /*
      $this->publishes([
          __DIR__.'/../resources/views' => base_path('resources/views/vendor/splittlogic'),
      ], 'gap.views');
      */

      // Publishing assets.
      $this->publishes([
          __DIR__.'/../resources/assets' => public_path('vendor/splittlogic'),
      ], 'public');

      // Publishing migrations
      $this->publishes([
          __DIR__ . '/../database/migrations/create_scriptslogs_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_scriptslogs_table.php'),
          // you can add any number of migrations here
        ], 'migrations');

  	}
}
