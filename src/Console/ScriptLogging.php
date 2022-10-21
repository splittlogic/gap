<?php

namespace splittlogic\gap\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use splittlogic\gap\Models\Scriptslog;

class ScriptLogging extends Command
{
    protected $signature = 'splittlogic:ScriptLogging {--name=} {--id=}';

    protected $description = 'Log the events of splittlogic scripts';

    public function handle()
    {

      // Set option variables
      $name = $this->option('name');
      $id = $this->option('id');

      // Check for id
      if (is_null($id))
      {

        $log = new Scriptslog;
        $log->name = $name;
        $log->starttime = \Carbon\Carbon::now()->toDateTimeString();
        $log->save();

        // Output id
        $this->info($log->id);

      } else {

        $log = Scriptslog::find($id);
        $log->endtime = \Carbon\Carbon::now()->toDateTimeString();
        $log->save();

      }

    }

}
