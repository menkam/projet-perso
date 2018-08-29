<?php

namespace App\Http\Controllers\AndroidApi;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\HttpException;
use PHPUnit\Runner\Exception;
use Illuminate\Http\Response;
use App\Fonction;

class TchatController extends Controller{

  public function creer(Request $request){
    $solution = array();
    if(!empty($request->iduser) && !empty($request->idmembrecontact) && !empty($request->msg)){

      $iduser = $request->iduser;
      $idmembrecontact = $request->idmembrecontact;
      $msg = $request->msg;
      $date = Fonction::getDate();      
      $heure = Fonction::getTime("h:m");

      $newmsg = DB::insert("INSERT INTO messages (contenu_message, date_message, heure_message) VALUES ('$msg', '$date', '$heure')");

      if($newmsg) {

        $idm = DB::select("SELECT DISTINCT(id_message) FROM messages WHERE contenu_message = '$msg' AND date_message = '$date'");

        if(!empty($idm)){

          $idmsg = $idm[0]->id_message;
          $newtchat = DB::insert("INSERT INTO tchats (id_user, id_membre_contact, id_message) VALUES ('$iduser', '$idmembrecontact', '$idmsg')");

          if(!empty($newtchat)) {
            $solution['status'] = "1";
            $solution['message'] = "message envoyé";
          }else {
            $solution['status'] = "0";
            $solution['message'] = "erreur";  
            $delete = DB::delete("DELETE FROM messages WHERE id_message = '$idmsg'");      
          }
        }else {
          $solution['status'] = "0";
          $solution['message'] = "erreur";        
        }
      }else {
        $solution['status'] = "0";
        $solution['message'] = "erreur";        
      }
      
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }
  
  public function effacer(Request $request){
    $solution = array();
    if(!empty($request->idtchat) && !empty($request->typeuser)){

      $idtchat = $request->idtchat;
      $typeuser = $request->typeuser;  
      $modifier='';    

      if($typeuser=="emetteur") {
        $modifier = DB::update("UPDATE tchats SET visibilite_emetteur='-1' WHERE id_tchat ='$idtchat';");

      }else if($typeuser=="recepteur") {
        $modifier = DB::update("UPDATE tchats SET visibilite_recepteur='-1' WHERE id_tchat ='$idtchat';");
      }

      if(!empty($modifier)) {
        $solution['status'] = "0";
        $solution['message'] = "message effacer";

      }else {
        $solution['status'] = "0";
        $solution['message'] = "erreur";
      }
      
    }else {
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

  public function liseterMsgParContact(Request $request){
    $solution = array();
    if(!empty($request->iduser) && !empty($request->idmembrecontact)){

      $iduser = $request->iduser; 
      $idmembrecontact = $request->idmembrecontact; 

      $idu2 = DB::select("SELECT DISTINCT(id_user) FROM membre_contacts WHERE id_membre_contact = '$idmembrecontact'");

      if(!empty($idu2)) {
        $iduser2 = $idu2[0]->id_user;

        $liste = DB::select("
          SELECT 
            messages.id_message, 
            messages.contenu_message, 
            messages.date_message, 
            messages.heure_message, 
            messages.statut_message, 
            tchats.id_tchat, 
            tchats.id_user, 
            tchats.id_membre_contact, 
            tchats.date_reception, 
            tchats.heure_reception, 
            tchats.date_lecture, 
            tchats.heure_lecture, 
            tchats.visibilite_emetteur, 
            tchats.visibilite_recepteur, 
            users.id_user, 
            users.nom_user, 
            users.prenom_user, 
            users.telephone_user, 
            users.photo_user, 
            users.statut_user
          FROM 
            public.users, 
            public.messages, 
            public.tchats, 
            public.membre_contacts
          WHERE 
            (users.id_user = tchats.id_user AND
            tchats.id_message = messages.id_message AND
            membre_contacts.id_membre_contact = tchats.id_membre_contact AND
            tchats.id_user = '$iduser' AND 
            tchats.id_membre_contact = '$idmembrecontact' AND
            tchats.visibilite_emetteur != '-1') OR (
            users.id_user = tchats.id_user AND
            tchats.id_message = messages.id_message AND
            membre_contacts.id_membre_contact = tchats.id_membre_contact AND
            tchats.id_user = '$iduser2' AND 
            membre_contacts.id_user = '$iduser' AND
            tchats.visibilite_recepteur != '-1'
            )
          ORDER BY
            messages.date_message ASC, 
            messages.heure_message ASC;
        ");

        if(!empty($liste)) {
          $solution = $liste;        
          $solution['status'] = "1";
          $solution['message'] = "trouvé";

        }else{
          $solution['status'] = "0";
          $solution['message'] = "pas de message";
        }
      } else {
          $solution['status'] = "0";
          $solution['message'] = "pas de message";
      }      
      
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      dd($solution);
      return Response()->json(compact('solution'));
  } 

  

  public function liseterMsg(Request $request){
    $solution = array();
    if(!empty($request->iduser)){

      $iduser = $request->iduser; 

      $liste = DB::select("SELECT DISTINCT(id_message) FROM messages WHERE contenu_message = '$msg' AND date_message = '$date'");

      if(!empty($liste)) {
        $solution = $liste;        
        $solution['status'] = "1";
        $solution['message'] = "trouvé";

      }else{
        $solution['status'] = "0";
        $solution['message'] = "pas de message";
      }
      
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur reçue";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

}
