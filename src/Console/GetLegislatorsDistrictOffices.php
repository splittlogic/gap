<?php

namespace splittlogic\gap\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;


class GetLegislatorsDistrictOffices extends Command
{
    protected $signature = 'splittlogic:GetLegislatorsDistrictOffices';

    protected $description = 'Download YAML file of Legislators District Offices';

    public function handle()
    {

      // Start script logging
      \Artisan::call('splittlogic:ScriptLogging --name=GetLegislatorsDistrictOffices');

      // Get script logging id
      $id = \Artisan::output();
      $id = trim($id);

      // Declare yaml variable
      $yaml = null;

      // Get current yaml file
      $file = file_get_contents('https://theunitedstates.io/congress-legislators/legislators-district-offices.yaml');

      // Check that files directory exists
      if(!File::isDirectory(public_path('files')))
      {
        // Make directory
        File::makeDirectory(public_path('files'), 0777, true, true);
      }

      // Declare current legislators files array
      $clfiles = array();

      // Scan directory for files
      $files = scandir(public_path('files'));

      // Cycle files to get only current legislators files
      foreach ($files as $f)
      {

        if (Str::contains($f,'legislators-district-offices'))
        {
          $clfiles[] = $f;
        }

      }

      // Check if current legislators files array is empty
      if (empty($clfiles))
      {

        file_put_contents(public_path('files') . '/' . now()->format('Y-m-d') . '_legislators-district-offices.yaml', $file);
        $yaml = $file;
        $this->info('New file saved');

      } else {

        // Get newest current legislators file
        $last = last($clfiles);

        // Get newest file's contents
        $file2 = file_get_contents(public_path('files') . '/' . $last);

        // Check if the file has been updated
        if ($file == $file2)
        {

          $yaml = $file;
          $this->info('No changes made');

        } else {

          file_put_contents(public_path('files') . '/' . now()->format('Y-m-d') . '_legislators-district-offices.yaml', $file2);
          $yaml = $file2;
          $this->info('New file saved');

        }

      }

      // Check yaml variable
      if (!is_null($yaml))
      {
        $y = Yaml::parse($yaml);

        // Cycle through yaml array
        foreach ($y as $data)
        {
          // Check if bioguide is set
          if (isset($data['id']['bioguide']))
          {

            $legislator = getLegislatorDistrictOffice($data['id']['bioguide']);

            // Check if legislator exists
            if (is_null($legislator))
            {
              // Add new legislator District Office
              addLegislatorDistrictOffice($data);

            // Need to update existing legislator
            } else {

            }
          }
        }
      }

      // End script logging
      \Artisan::call('splittlogic:ScriptLogging --id=' . $id);

    }

}
