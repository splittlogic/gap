<?php

namespace splittlogic\gap\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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
    $step = 1;
    $totalSteps = 8;

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
    $this->info($step . ' of ' . $totalSteps . ' - Users table migration file updated');
    $step++;

    // Reset variables
    $files = null;
    $file = null;
    $contents = null;

    // Update User.php model file
    $contents = file_get_contents($path . '/User.php');
    file_put_contents(app_path('Models/') . 'User.php', $contents);
    $this->info($step . ' of ' . $totalSteps . ' - User model updated');
    $step++;

    // Reset variables
    $files = null;
    $file = null;
    $contents = null;

    // Run database migrations
    \Artisan::call('migrate');
    $this->info($step . ' of ' . $totalSteps . ' - Database files migrated');
    $step++;

    // Run ui bootstrap for auth
    \Artisan::call('ui bootstrap -n --auth');
    $this->info($step . ' of ' . $totalSteps . ' - Bootstrap ui ran');
    $step++;

    // npm install
    $this->info('   ...Running npm install.  This will take some time.');
    $process = new Process(['npm', 'install']);
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    $this->info('   1 of 3 - npm install');

    $process = new Process(['npm', 'install', 'resolve-url-loader@^5.0.0', '--save-dev', '--legacy-peer-deps']);
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    $this->info('   2 of 3 - npm install');

    $process = new Process(['npm', 'run', 'dev']);
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    $this->info('   3 of 3 - npm install');

    $this->info($step . ' of ' . $totalSteps . ' - npm install');
    $step++;

    // Update Admin middleware
    $contents = file_get_contents($path . '/Admin.php');
    file_put_contents(app_path('Http/Middleware') . '/Admin.php', $contents);

    $this->info($step . ' of ' . $totalSteps . ' - Admin middleware updated');

    // Update Kernel
    $contents = file_get_contents($path . '/Kernel.php');
    file_put_contents(app_path('Http/') . '/Kernel.php', $contents);

    $this->info($step . ' of ' . $totalSteps . ' - Kernel updated');

    // Update LoginController
    $contents = file_get_contents($path . '/LoginController.php');
    file_put_contents(app_path('Http/Controllers/Auth') . '/LoginController.php', $contents);

    $this->info($step . ' of ' . $totalSteps . ' - Login Controller updated');

    // Create Default Admin
    $user = new User();
    $user->password = Hash::make('password');
    $user->email = 'admin@email.com';
    $user->name = 'Default Admin';
    $user->save();

    $this->info($step . ' of ' . $totalSteps . ' - Default Admin Created');

  }

}
