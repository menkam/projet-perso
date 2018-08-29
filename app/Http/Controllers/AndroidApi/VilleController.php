<?php

namespace App\Http\Controllers\AndroidApi;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\HttpException;
use PHPUnit\Runner\Exception;
use Illuminate\Http\Response;

class VilleController extends Controller{

  public function ajouter(Request $request){

  }

  public function supprimer(Request $request){

  }
  
  public function lister(Request $request){
    $solution = array();
    if(!empty($request->departement)){

      $departement = $request->departement;
      $result = DB::select("SELECT * FROM villes WHERE id_departement = '$departement'");

      if(!empty($result)) {
        $solution = $result;
        $solution['status'] = "1";
        $solution['message'] = "trouver";
      }else {
        $solution['status'] = "0";
        $solution['message'] = "vide";
      }
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

}
