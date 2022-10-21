<?php

namespace splittlogic\gap\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use splittlogic\gap\gap;

class gapAdminController extends Controller
{


  public function index ()
  {

    $content = 'This is admin'; 

    return view('gap::blank', ['content' => $content]);

  }


}
