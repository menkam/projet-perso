<?php

namespace App\Http\Controllers\AndroidApi;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\HttpException;
use PHPUnit\Runner\Exception;
use Illuminate\Http\Response;

class ForumController extends Controller{

  public function creer(Request $request){

  }
  
  public function modifier(Request $request){
    $solution = array();
    if(!empty($request->departement)){

      
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

}
