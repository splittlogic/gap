<?php

namespace splittlogic\gap\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class gapInstall extends Command
{

  protected $signature = 'splittlogic:gapInstall';

  protected $description = 'Initialization of the GAP package';

  public function handle()
  {

    // Declare variables
    $files = null;
    $file = null;
    $contents = null;

    $this->info('This is gapInstall');

    $this->info(database_path('migrations'));

    // Update create users migration file
    $files = scandir(database_path('migrations'));

    foreach ($files as $f)
    {
      if (str_contains($f, 'create_users_table'))
      {
        $file = $f;
      }
    }

    if (is_null($file))
    {
      $file = '2014_10_12_000000_create_users_table.php';
    }

    $path = base_path('vendor/splittlogic/gap/install');

    $contents = file_get_contents($path . '/create_users_table.php');

    file_put_contents(database_path('migrations/') . $file, $contents);

    // Reset variables
    $files = null;
    $file = null;
    $contents = null;

    // Update User.php model file
    $contents = file_get_contents($path . '/User.php');
    file_put_contents(app_path('Models/') . 'User.php', $contents);

    // Reset variables
    $files = null;
    $file = null;
    $contents = null;

    // Run database migrations
    \Artisan::call('migrate');

  }

}
