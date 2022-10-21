<?php

namespace splittlogic\gap\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use splittlogic\gap\gap;

class gapController extends Controller
{


  public function index ()
  {

    $content = 'This is splittlogic/gap'; 

    return view('gap::blank', ['content' => $content]);

  }


}
