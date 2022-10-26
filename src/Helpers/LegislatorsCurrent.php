<?php

/*

addLegislator() - Add Legislator from given data and return new id
addLegislatorDistrictOffice() - Add Legislator district office from given data and return new id
addLegislatorSocial() - Add legislator from given data and return new id
getLegislator() - Gets current legislator by bioguide number or returns null
getLegislatorDistrictOffice() - Get legislator district office by bioguide number or returns null
getLegislatorSocial() - Gets Legislators Social Media information by bioguide id
getLegislatorHistorical() - Gets Historical legislator by bioguide number or returns null

*/

use splittlogic\gap\Models\LegislatorsCurrent;
use splittlogic\gap\Models\LegislatorsCurrentFamily;
use splittlogic\gap\Models\LegislatorsCurrentLeadershipRole;
use splittlogic\gap\Models\LegislatorsCurrentPartyAffiliation;
use splittlogic\gap\Models\LegislatorsCurrentTerm;

use splittlogic\gap\Models\LegislatorsHistorical;
use splittlogic\gap\Models\LegislatorsHistoricalFamily;
use splittlogic\gap\Models\LegislatorsHistoricalLeadershipRole;
use splittlogic\gap\Models\LegislatorsHistoricalPartyAffiliation;
use splittlogic\gap\Models\LegislatorsHistoricalTerm;

use splittlogic\gap\Models\LegislatorsDistrictOffice;
use splittlogic\gap\Models\LegislatorsSocialMedia;

if (! function_exists('addLegislator')) {
  function addLegislator($data, $current = null)
  {

    // Declare id
    $id = null;

    // Check for current legislator
    if (is_null($current))
    {
      $f = new LegislatorsHistoricalFamily;
      $l = new LegislatorsHistorical;
      $lr = new LegislatorsHistoricalLeadershipRole;
      $p = new LegislatorsHistoricalPartyAffiliation;
      $t = new LegislatorsHistoricalTerm;
    } else {
      $f = new LegislatorsCurrentFamily;
      $l = new LegislatorsCurrent;
      $lr = new LegislatorsCurrentLeadershipRole;
      $p = new LegislatorsCurrentPartyAffiliation;
      $t = new LegislatorsCurrentTerm;
    }

    // Check for bioguide
    if (isset($data['id']['bioguide']))
    {
      $l->bioguide = $data['id']['bioguide'];
    }

    // Check for thomas
    if (isset($data['id']['thomas']))
    {
      $l->thomas = $data['id']['thomas'];
    }

    // Check for lis
    if (isset($data['id']['lis']))
    {
      $l->lis = $data['id']['lis'];
    }

    // Check for govtrack
    if (isset($data['id']['govtrack']))
    {
      $l->govtrack = $data['id']['govtrack'];
    }

    // Check for opensecrets
    if (isset($data['id']['opensecrets']))
    {
      $l->opensecrets = $data['id']['opensecrets'];
    }

    // Check for votesmart
    if (isset($data['id']['votesmart']))
    {
      $l->votesmart = $data['id']['votesmart'];
    }

    // Check for fec
    if (isset($data['id']['fec']))
    {
      // Check for first fec
      if (isset($data['id']['fec'][0]))
      {
        $l->fec1 = $data['id']['fec'][0];
      }
      // Check for second fec
      if (isset($data['id']['fec'][1]))
      {
        $l->fec2 = $data['id']['fec'][1];
      }
      // Check for third fec
      if (isset($data['id']['fec'][2]))
      {
        $l->fec3 = $data['id']['fec'][2];
      }
    }

    // Check for cspan
    if (isset($data['id']['cspan']))
    {
      $l->cspan = $data['id']['cspan'];
    }

    // Check for wikipedia
    if (isset($data['id']['wikipedia']))
    {
      $l->wikipedia = $data['id']['wikipedia'];
    }

    // Check for house history
    if (isset($data['id']['house_history']))
    {
      $l->house_history = $data['id']['house_history'];
    }

    // Check for ballotpedia
    if (isset($data['id']['ballotpedia']))
    {
      $l->ballotpedia = $data['id']['ballotpedia'];
    }

    // Check for maplight
    if (isset($data['id']['maplight']))
    {
      $l->maplight = $data['id']['maplight'];
    }

    // Check for icpsr
    if (isset($data['id']['icpsr']))
    {
      $l->icpsr = $data['id']['icpsr'];
    }

    // Check for wikidata
    if (isset($data['id']['wikidata']))
    {
      $l->wikidata = $data['id']['wikidata'];
    }

    // Check for google entity
    if (isset($data['id']['google_entity_id']))
    {
      $l->google_entity_id = $data['id']['google_entity_id'];
    }

    // Check Names
    if (isset($data['name']))
    {
      // Check for first
      if (isset($data['name']['first']))
      {
        $l->name_first = $data['name']['first'];
      }
      // Check for last
      if (isset($data['name']['last']))
      {
        $l->name_last = $data['name']['last'];
      }
      // Check for Offical Full
      if (isset($data['name']['official_full']))
      {
        $l->name_official_full = $data['name']['official_full'];
      }
      // Check for middle
      if (isset($data['name']['middle']))
      {
        $l->name_middle = $data['name']['middle'];
      }
      // Check for nickname
      if (isset($data['name']['nickname']))
      {
        $l->name_nickname = $data['name']['nickname'];
      }
      // Check for suffix
      if (isset($data['name']['suffix']))
      {
        $l->name_suffix = $data['name']['suffix'];
      }
    }

    // Check bio
    if (isset($data['bio']))
    {
      // Check birthday
      if (isset($data['bio']['birthday']))
      {
        $l->birthday = $data['bio']['birthday'];
      }
      // Check for gender
      if (isset($data['bio']['gender']))
      {
        $l->gender = $data['bio']['gender'];
      }
    }

    $l->save();

    $id = $l->id;

    // Check if terms is set
    if (isset($data['terms']))
    {
      // Check if is an array
      if (is_array($data['terms']))
      {
        // Check if is empty
        if (!empty($data['terms']))
        {
          // Cycle through terms
          foreach ($data['terms'] as $term)
          {

              // Check for current legislator
              if (is_null($current))
              {
                $t = new LegislatorsHistoricalTerm;
                $t->legislators_historical_id = $id;
              } else {
                $t = new LegislatorsCurrentTerm;
                $t->legislators_current_id = $id;
              }

            // Check for type
            if (isset($term['type']))
            {
              $t->type = $term['type'];
            }

            // Check for start
            if (isset($term['start']))
            {
              $t->start = $term['start'];
            }

            // Check for end
            if (isset($term['end']))
            {
              $t->end = $term['end'];
            }

            // Check for state
            if (isset($term['state']))
            {
              $t->state = $term['state'];
            }

            // Check for district
            if (isset($term['district']))
            {
              $t->district = $term['district'];
            }

            // Check for party
            if (isset($term['party']))
            {
              $t->party = $term['party'];
            }

            // Check for url
            if (isset($term['url']))
            {
              $t->url = $term['url'];
            }

            // Check for class
            if (isset($term['class']))
            {
              $t->class = $term['class'];
            }

            // Check for address
            if (isset($term['address']))
            {
              $t->address = $term['address'];
            }

            // Check for phone
            if (isset($term['phone']))
            {
              $t->phone = $term['phone'];
            }

            // Check for fax
            if (isset($term['fax']))
            {
              $t->fax = $term['fax'];
            }

            // Check for contact form
            if (isset($term['contact_form']))
            {
              $t->contact_form = $term['contact_form'];
            }

            // Check for office
            if (isset($term['office']))
            {
              $t->office = $term['office'];
            }

            // Check for state rank
            if (isset($term['state_rank']))
            {
              $t->state_rank = $term['state_rank'];
            }

            // Check for rss url
            if (isset($term['rss_url']))
            {
              $t->rss_url = $term['rss_url'];
            }

            // Check for how
            if (isset($term['how']))
            {
              $t->how = $term['how'];
            }

            // Check for caucus
            if (isset($term['caucus']))
            {
              $t->caucus = $term['caucus'];
            }

            // Check for end type
            if (isset($term['end-type']))
            {
              $t->end_type = $term['end-type'];
            }

            $t->save();

            // Check for party affiliations
            if (isset($term['party_affiliations']))
            {
              // Check if is an array
              if (is_array($term['party_affiliations']))
              {
                // Check it is not empty
                if (!empty($term['party_affiliations']))
                {
                  // Cycle through party affiliations
                  foreach ($term['party_affiliations'] as $pa)
                  {

                      // Check for current legislator
                      if (is_null($current))
                      {
                        $p = new LegislatorsHistoricalPartyAffiliation;
                        $p->legislators_historical_id = $id;
                      } else {
                        $p = new LegislatorsCurrentPartyAffiliation;
                        $p->legislators_current_id = $id;
                      }

                    // Check for start
                    if (isset($pa['start']))
                    {
                      $p->start = $pa['start'];
                    }
                    // Check for end
                    if (isset($pa['end']))
                    {
                      $p->end = $pa['end'];
                    }
                    // Check for party
                    if (isset($pa['party']))
                    {
                      $p->party = $pa['party'];
                    }

                    $p->save();

                  }
                }
              }
            }
          }
        }
      }
    }

    // Check if leadership roles is set
    if (isset($data['leadership_roles']))
    {
      // Check if is an array
      if (is_array($data['leadership_roles']))
      {
        // Check if empty
        if (!empty($data['leadership_roles']))
        {
          // Cycle through leadership roles
          foreach ($data['leadership_roles'] as $role)
          {

              // Check for current legislator
              if (is_null($current))
              {
                $lr = new LegislatorsHistoricalLeadershipRole;
                $lr->legislators_historical_id = $id;
              } else {
                $lr = new LegislatorsCurrentLeadershipRole;
                $lr->legislators_current_id = $id;
              }


            // Check for title
            if (isset($role['title']))
            {
              $lr->title = $role['title'];
            }
            // Check for chamber
            if (isset($role['chamber']))
            {
              $lr->chamber = $role['chamber'];
            }
            // Check for start
            if (isset($role['start']))
            {
              $lr->start = $role['start'];
            }
            // Check for end
            if (isset($role['end']))
            {
              $lr->end = $role['end'];
            }

            $lr->save();
          }
        }
      }
    }

    // Check if family is set
    if (isset($data['family']))
    {
      // Check if is an array
      if (is_array($data['family']))
      {
        // Check if empty
        if (!empty($data['family']))
        {
          // Cycle through array
          foreach ($data['family'] as $fam)
          {

              // Check for current legislator
              if (is_null($current))
              {
                $f = new LegislatorsHistoricalFamily;
                $f->legislators_historical_id = $id;
              } else {
                $f = new LegislatorsCurrentFamily;
                $f->legislators_current_id = $id;
              }


            // Check if name is set
            if (isset($fam['name']))
            {
              $f->name = $fam['name'];
            }
            // Check if relation is set
            if (isset($fam['relation']))
            {
              $f->relation = $fam['relation'];
            }

            $f->save();
          }
        }
      }
    }

    return $id;

  }
}


if (! function_exists('addLegislatorDistrictOffice')) {
  function addLegislatorDistrictOffice($data)
  {

    // Check for offices
    if (isset($data['offices']))
    {
      if (is_array($data['offices']))
      {
        foreach ($data['offices'] as $office)
        {

          $d = new LegislatorsDistrictOffice;

          // Check for bioguide
          if (isset($data['id']['bioguide']))
          {
            $d->bioguide = $data['id']['bioguide'];
          }

          // Check for thomas
          if (isset($data['id']['thomas']))
          {
            $d->thomas = $data['id']['thomas'];
          }

          // Check for govtrack
          if (isset($data['id']['govtrack']))
          {
            $d->govtrack = $data['id']['govtrack'];
          }

          // Check for office id
          if (isset($office['id']))
          {
            $d->office_id = $office['id'];
          }

          // Check for address
          if (isset($office['address']))
          {
            $d->address = $office['address'];
          }

          // Check for suite
          if (isset($office['suite']))
          {
            $d->suite = $office['suite'];
          }

          // Check for city
          if (isset($office['city']))
          {
            $d->city = $office['city'];
          }

          // Check for state
          if (isset($office['state']))
          {
            $d->state = $office['state'];
          }

          // Check for zip
          if (isset($office['zip']))
          {
            $d->zip = $office['zip'];
          }

          // Check for latitude
          if (isset($office['latitude']))
          {
            $d->latitude = $office['latitude'];
          }

          // Check for longitude
          if (isset($office['longitude']))
          {
            $d->longitude = $office['longitude'];
          }

          // Check for phone
          if (isset($office['phone']))
          {
            $d->phone = $office['phone'];
          }

          // Check for fax
          if (isset($office['fax']))
          {
            $d->fax = $office['fax'];
          }

          // Check for building
          if (isset($office['building']))
          {
            $d->building = $office['building'];
          }

          // Check for hours
          if (isset($office['hours']))
          {
            $d->hours = $office['hours'];
          }

          $d->save();

        }
      }
    }
  }
}


if (! function_exists('addLegislatorSocial')) {
  function addLegislatorSocial($data)
  {

    $s = new LegislatorsSocialMedia;

    // Check for bioguide
    if (isset($data['id']['bioguide']))
    {
      $s->bioguide = $data['id']['bioguide'];
    }

    // Check for thomas
    if (isset($data['id']['thomas']))
    {
      $s->thomas = $data['id']['thomas'];
    }

    // Check for govtrack
    if (isset($data['id']['govtrack']))
    {
      $s->govtrack = $data['id']['govtrack'];
    }

    // Check for twitter
    if (isset($data['social']['twitter']))
    {
      $s->twitter = $data['social']['twitter'];
    }

    // Check for facebook
    if (isset($data['social']['facebook']))
    {
      $s->facebook = $data['social']['facebook'];
    }

    // Check for youtube_id
    if (isset($data['social']['youtube_id']))
    {
      $s->youtube_id = $data['social']['youtube_id'];
    }

    // Check for twitter_id
    if (isset($data['social']['twitter_id']))
    {
      $s->twitter_id = $data['social']['twitter_id'];
    }

    // Check for youtube
    if (isset($data['social']['youtube']))
    {
      $s->youtube = $data['social']['youtube'];
    }

    // Check for instagram
    if (isset($data['social']['instagram']))
    {
      $s->instagram = $data['social']['instagram'];
    }

    // Check for instagram_id
    if (isset($data['social']['instagram_id']))
    {
      $s->instagram_id = $data['social']['instagram_id'];
    }

    $s->save();

    return $s->id;

  }
}


if (! function_exists('getLegislator')) {
  function getLegislator($bioguide)
  {
    $legislator = LegislatorsCurrent::where('bioguide', $bioguide)->first();

    return $legislator;
  }
}


if (! function_exists('getLegislatorDistrictOffice')) {
  function getLegislatorDistrictOffice($bioguide)
  {
    $office = LegislatorsDistrictOffice::where('bioguide', $bioguide)->first();

    return $office;
  }
}


if (! function_exists('getLegislatorSocial')) {
  function getLegislatorSocial($bioguide)
  {
    $social = LegislatorsSocialMedia::where('bioguide', $bioguide)->first();

    return $social;
  }
}


if (! function_exists('GetLegislatorsHistorical')) {
  function GetLegislatorsHistorical($bioguide)
  {
    $legislator = LegislatorsHistorical::where('bioguide', $bioguide)->first();

    return $legislator;
  }
}
