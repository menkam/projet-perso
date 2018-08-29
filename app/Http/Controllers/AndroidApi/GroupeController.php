<?php

namespace App\Http\Controllers\AndroidApi;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\HttpException;
use PHPUnit\Runner\Exception;
use Illuminate\Http\Response;
use App\Fonction;

class GroupeController extends Controller{

  public function creerGroupe(Request $request){
    $solution = array();
    if(!empty($request->iduser) && !empty($request->idmembre) && !empty($request->nomgroupe)){

      $date = Fonction::getDate();
      $iduser = $request->iduser;
      $idmembre = $request->idmembre;
      $nomgroupe = $request->nomgroupe;

      $exitgroupe = DB::select("SELECT count(id_groupe) FROM groupes WHERE id_user = '$iduser' AND nom_groupe = '$nomgroupe'");

      if($exitgroupe[0]->count==0) {

        $groupe = DB::insert("INSERT INTO groupes (id_user, nom_groupe, date_creation_groupe) VALUES ('$iduser', '$nomgroupe', '$date')");

        if($groupe) {

          $idg = DB::select("SELECT DISTINCT(id_groupe) FROM groupes WHERE id_user = '$iduser' AND date_creation_groupe = '$date'");
          $idgroupe = $idg[0]->id_groupe;

          if($idgroupe) {

            $newmembre = DB::insert("INSERT INTO membre_groupes (id_user, id_groupe, date_adhesion_membre_goupe, statut_membre_goupe) VALUES 
              ('$iduser', '$idgroupe', '$date', '2'),
              ('$idmembre', '$idgroupe', '$date', '1')
            ");

            if($newmembre) {
              $solution['status'] = "1";
              $solution['message'] = "groupe <<$nomgroupe>> créé avec succes";
            }else{
              $solution['status'] = "0";
              $solution['message'] = "erreur de creation du groupe";
              $delgroupe = DB::delete("DELETE FROM groupes WHERE id_groupe = '$idgroupe'");
            }
          }else{
            $solution['status'] = "0";
            $solution['message'] = "erreur de creation du groupe";
          } 
        }else{
          $solution['status'] = "0";
          $solution['message'] = "erreur de creation du groupe";
        }
      }else {
        $solution['status'] = "0";
        $solution['message'] = "le groupe <<$nomgroupe>> existe déjà!!!";        
      }      
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
    //dd($solution);
    return Response()->json(compact('solution'));
  }

  public function renommerGroupe(Request $request){
    $solution = array();
    if(!empty($request->idgroupe) && !empty($request->newnom)){

      $idgroupe = $request->idgroupe;
      $newnom = $request->newnom;

      $modifier = DB::update("UPDATE groupes SET nom_groupe='$newnom' WHERE id_groupe ='$idgroupe';");

      if($modifier) {
        $solution['status'] = "1";
        $solution['message'] = "groupe renommer avec succes";
      }else {
        $solution['status'] = "0";
        $solution['message'] = "erreur de modification du groupe";
      }
      
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
    //dd($solution);
    return Response()->json(compact('solution'));
  }

  public function modifierGroupe(Request $request) {
    $solution = array();
    if(!empty($request->idgroupe) && !empty($request->newstatut)) {

      $idgroupe = $request->idgroupe;
      $newstatut = $request->newstatut;

      $modifier = DB::update("UPDATE groupes SET statut_groupe='$newstatut' WHERE id_groupe ='$idgroupe';");

      if($modifier) {
        if($newstatut=="1") {
          $solution['status'] = "1";
          $solution['message'] = "Groupe activé";

        }else if($newstatut=="-1") {
          $solution['status'] = "1";
          $solution['message'] = "Groupe supprimé";

        }else if($newstatut=="2"){
          $solution['status'] = "1";
          $solution['message'] = "Groupe desactivé";
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


  public function ajouterMembre(Request $request) {
    $solution = array();
    if(!empty($request->idgroupe) && !empty($request->idnewmembre)) {
      $date = Fonction::getDate();
      $idgroupe = $request->idgroupe;
      $idnewmembre = $request->idnewmembre;

      $membre = DB::select("SELECT count(id_membre_groupe) FROM membre_groupes WHERE id_user = '$idnewmembre' AND id_groupe = '$idgroupe'");

      //echo "idnewmembre = $idnewmembre & idgroupe = $idgroupe & count = ".$membre[0]->count;
      if($membre[0]->count==0) {
        $newmembre = DB::insert("INSERT INTO membre_groupes (id_user, id_groupe, date_adhesion_membre_groupe) 
        VALUES ('$idnewmembre', '$idgroupe', '$date')");
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
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }
  
  public function modifierMembre(Request $request) {
    $solution = array();
    if(!empty($request->idmembregroupe) && !empty($request->newstatut)) {

      $idmembregroupe = $request->idmembregroupe;
      $newstatut = $request->newstatut;

      $modifier = DB::update("UPDATE membre_groupes SET statut_membre_groupe='$newstatut' WHERE id_membre_groupe ='$idmembregroupe';");

      if($modifier) {

        if($newstatut=="1") {
          $solution['status'] = "1";
          $solution['message'] = "membre activé";

        }else if($newstatut=="2") {
          $solution['status'] = "1";
          $solution['message'] = "membre devenu administrateur";

        }else if($newstatut=="-1"){
          $solution['status'] = "1";
          $solution['message'] = "membre desactivé";

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

  public function listerMembre(Request $request) {
    $solution = array();
    if(!empty($request->idgroupe)) {

      $idgroupe = $request->idgroupe;
      $sol = DB::select("SELECT * FROM membre_groupes WHERE id_groupe = '$idgroupe' AND statut_membre_groupe != '-1'");

      if(!empty($sol)) {
        $solution = $sol;
        $solution['status'] = "1";
        $solution['message'] = "trouvé";         
      }else {
        $solution['status'] = "0";
        $solution['message'] = "groupe vide!!!";
      }
    }else {
      $solution['status'] = "0";
      $solution['message'] = "Aucune valeur reçue";
    }
    //dd($solution);
    return Response()->json(compact('solution'));
  }

  public function listerGroupe(Request $request) {
    $solution = array();
    if(!empty($request->idmembre)) {

      $idmembre = $request->idmembre;
      $sol = DB::select("
        SELECT 
          groupes.id_groupe, 
          groupes.id_user, 
          groupes.nom_groupe, 
          groupes.date_creation_groupe, 
          groupes.statut_groupe
        FROM 
          public.users, 
          public.membre_groupes, 
          public.groupes
        WHERE 
          membre_groupes.id_user = users.id_user AND
          membre_groupes.id_groupe = groupes.id_groupe AND
          users.id_user = '$idmembre' AND 
          groupes.statut_groupe != '-1' AND 
          membre_groupes.statut_membre_groupe != '-1';
      ");

      if(!empty($sol)) {
        $solution = $sol;
        $solution['status'] = "1";
        $solution['message'] = "Trouvé";         
      }else {
        $solution['status'] = "0";
        $solution['message'] = "Pas de groupe trouvé!!!";
      }
    }else {
      $solution['status'] = "0";
      $solution['message'] = "Aucune valeur reçue";
    }
    //dd($solution);
    return Response()->json(compact('solution'));
  }

}
