<?php

namespace App\Http\Controllers\AndroidApi;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\HttpException;
use PHPUnit\Runner\Exception;
use Illuminate\Http\Response;
use App\Fonction;

class ContactController extends Controller{


  /**
  * ajouterMembre
  **/
  public function ajouterMembre(Request $request){
    $solution = array();
    if(!empty($request->iduser) && !empty($request->telephone)) {
      $date = Fonction::getDate();
      $iduser = $request->iduser;
      $telephone = $request->telephone;

      $id = DB::select("SELECT DISTINCT(id_contact) FROM contacts WHERE id_user = '$iduser'");
      $idm = DB::select("SELECT DISTINCT(id_user) FROM users WHERE telephone_user = '$telephone' AND statut_user = '1'");

      if(!empty($id)) {
        if(!empty($idm)) {
          $idcontact = $id[0]->id_contact;
          $idmembre = $idm[0]->id_user;

          $membre = DB::select("SELECT count(id_membre_contact) FROM membre_contacts WHERE id_user = '$idmembre' AND id_contact = '$idcontact'");

          //echo "idmembre = $idmembre & idcontact = $idcontact & count = ".$membre[0]->count;
          if($membre[0]->count==0) {
            $newmembre = DB::insert("INSERT INTO membre_contacts (id_user, id_contact, date_ajout_membre_contact) 
            VALUES ('$idmembre', '$idcontact', '$date')");
            if($newmembre) {
              $solution['status'] = "1";
              $solution['message'] = "Ajouté";

            } else {
              $solution['status'] = "0";
              $solution['message'] = "impossible";
            }
          }else {
            $solution['status'] = "0";
            $solution['message'] = "existe déjà";
          } 
        }else {
          $solution['status'] = "0";
          $solution['message'] = "compte fermé";
        }
      }else {
        $solution['status'] = "0";
        $solution['message'] = "votre contact est introuvable";
      }
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

  /**
  * modifierMembre
  **/
  public function modifierMembre(Request $request) {
    $solution = array();
    if(!empty($request->idmembrecontact) && !empty($request->newstatut)) {

      $idmembrecontact = $request->idmembrecontact;
      $newstatut = $request->newstatut;

      $modifier = DB::update("UPDATE membre_contacts SET statut_membre_contact='$newstatut' WHERE id_membre_contact ='$idmembrecontact';");

      if($modifier) {
        if($newstatut=="1") {
          $solution['status'] = "1";
          $solution['message'] = "Compte activé";
        }else {
          $solution['status'] = "1";
          $solution['message'] = "Compte desactivé";
        }
      }else {
        $solution['status'] = "0";
        $solution['message'] = "erreur de modification du compte";
      }
      
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

  /**
  * listerMembre
  **/
  public function listerMembre(Request $request) {
    $solution = array();
    if(!empty($request->iduser)) {

      $iduser = $request->iduser;

      $idc = DB::select("SELECT DISTINCT(id_contact) FROM contacts WHERE id_user = '$iduser' AND statut_contact = '1'");

      if(!empty($idc)) {

        $idcontact = $idc[0]->id_contact;
        $sol = DB::select("SELECT * FROM membre_contacts WHERE id_contact = '$idcontact'");

        if(!empty($sol)) {
          $solution = $sol;
          $solution['status'] = "1";
          $solution['message'] = "trouvé";         
        }else {
          $solution['status'] = "0";
          $solution['message'] = "contact vide!!!";
        }
      }else {
        $solution['status'] = "0";
        $solution['message'] = "votre contact est bloqué";
      }
    }
    //dd($solution);
    return Response()->json(compact('solution'));
  }
}
