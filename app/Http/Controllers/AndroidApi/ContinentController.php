<?php

namespace App\Http\Controllers\AndroidApi;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\HttpException;
use PHPUnit\Runner\Exception;
use Illuminate\Http\Response;

class ContinentController extends Controller{

  public function ajouter(Request $request){

  }

  public function supprimer(Request $request){

  }

  public function lister(Request $request){
    $solution = array();

    $result = DB::select("SELECT * FROM continents");

    if(!empty($result)) {
      $solution = $result;
      $solution['status'] = "1";
      $solution['message'] = "trouver";
    }else {
      $solution['status'] = "0";
      $solution['message'] = "vide";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

}
