<?php

namespace App\Http\Controllers\AndroidApi;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\HttpException;
use PHPUnit\Runner\Exception;
use Illuminate\Http\Response;
use App\Fonction;

class AuthController extends Controller{

  /**
  * verifier Email
  **/
  public function verifierTelephone(Request $request){
    $solution = array();
    if(!empty($request->telephone)){
      $telephone = $request->telephone;
      $result = DB::select("SELECT COUNT(id_user) FROM users WHERE telephone_user = '$telephone'");
      $solution['status'] = $result[0]->count;
      if($result[0]->count) {
        $solution['message'] = "$telephone existe";
      }else {
        $solution['message'] = "$telephone n'existe pas!!!";
      }
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur recus";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

  /**
  * verifier Password
  **/
  public function verifierPassword(Request $request){
    $solution = array();
    if(!empty($request->password)){
      $password = md5($request->password);
      $result = DB::select("SELECT COUNT(id_user) FROM users WHERE password_user = '$password'");
      $solution['status'] = $result[0]->count;
      if($result[0]->count) {
        $solution['message'] = "existe";
      }else {
        $solution['message'] = "n'existe pas";
      }
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur recus";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

  /**
  *  Login
  **/
  public function login(Request $request) {
    $solution = array();
    if(!empty($request->telephone) && !empty($request->password)){

      $telephone = $request->telephone;
      $password = md5($request->password);

      $result = DB::select("
        SELECT 
          users.id_user, 
          users.nom_user, 
          users.prenom_user, 
          users.telephone_user, 
          users.photo_user, 
          tl_sexe.libelle_sexe, 
          tl_jour_moi.numero_jour, 
          tl_moi_annee.libelle_moi, 
          tl_annee.numero_annee, 
          users.statut_user, 
          user_ville.annee_user_ville, 
          villes.libelle_ville, 
          departements.libelle_departement, 
          regions.libelle_region, 
          pays.libelle_pays, 
          continents.libelle_continent
        FROM 
          public.users, 
          public.tl_sexe, 
          public.tl_jour_moi, 
          public.tl_moi_annee, 
          public.tl_annee, 
          public.user_ville, 
          public.villes, 
          public.departements, 
          public.regions, 
          public.pays, 
          public.continents
        WHERE 
          users.id_annee = tl_annee.id_annee AND
          tl_sexe.id_sexe = users.id_sexe AND
          tl_jour_moi.id_jour = users.id_jour AND
          tl_moi_annee.id_moi = users.id_moi AND
          user_ville.id_user = users.id_user AND
          user_ville.id_ville = villes.id_ville AND
          villes.id_departement = departements.id_departement AND
          departements.id_region = regions.id_region AND
          regions.id_pays = pays.id_pays AND
          pays.id_continent = continents.id_continent AND
          user_ville.statut_user_ville = 1 AND
          users.telephone_user = '$telephone' AND 
          users.password_user = '$password'
      ");        

      if(!empty($result)) {
        if($result[0]->statut_user=="1") {
          //$solution = $result;
          $solution['status'] = "1";
          $solution['message'] = "connecter";
          $solution['id_user'] = $result[0]->id_user;
          $solution['nom_user'] = $result[0]->nom_user;
          $solution['prenom_user'] = $result[0]->prenom_user;
          $solution['sexe'] = $result[0]->libelle_sexe;
          $solution['jourNais'] = $result[0]->numero_jour;
          $solution['moiNais'] = $result[0]->libelle_moi;
          $solution['anneeNais'] = $result[0]->numero_annee;
          $solution['telephone_user'] = $result[0]->telephone_user;
          $solution['villeActuelle'] = $result[0]->libelle_ville;
          $solution['departementActuelle'] = $result[0]->libelle_departement;
          $solution['regionActuelle'] = $result[0]->libelle_region;
          $solution['paysActuelle'] = $result[0]->libelle_pays;
          $solution['continentActuelle'] = $result[0]->libelle_continent;

          $path = "images/profil/".$result[0]->photo_user;
          if(file_exists($path)){
            $photo = base64_encode(file_get_contents($path));
          }else {
            $path = "images/profil/default_profil.png";
            $photo = base64_encode(file_get_contents($path));
          }            
          $solution['photo'] = $photo;

        }else {
          $solution['status'] = "0";
          $solution['message'] = "compte bloquer";
        }
      }else {
        $solution['status'] = "0";
        $solution['message'] = "verifier numero ou mot de passe";
      }
      
    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur recus";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

  /**
  *  Register
  **/
  public function register(Request $request) {
      $solution = array();
      if(!empty($request->nom) && !empty($request->prenom) && !empty($request->idsexe) && !empty($request->telephone) && !empty($request->password) && !empty($request->idville)) {

        $date = Fonction::getDate();

        $nom = $request->nom;
        $prenom = $request->prenom;
        $idsexe = $request->idsexe;
        $idjour = $request->idjour;
        $idmoi = $request->idmoi;
        $idannee = $request->idannee;
        $telephone = $request->telephone;
        $password = md5($request->password);

        $message = "";
        $status = "";

        $idville = $request->idville;
        $iduser = '';

        $result = DB::insert("
          INSERT INTO users (nom_user, prenom_user, id_sexe, id_jour, id_moi, id_annee, telephone_user, password_user)
          VALUES ('$nom', '$prenom', '$idsexe', '$idjour', '$idmoi', '$idannee', '$telephone', '$password');
        ");

        if(!empty($result)) {

          $id = DB::select("SELECT DISTINCT(id_user) FROM users WHERE telephone_user = '$telephone' AND password_user = '$password'");

          if(!empty($id)) {
            $message = "-> Compte utilisateur créé<br>";
            $status = "1";
            $iduser = $id[0]->id_user;

            $contact = DB::insert("INSERT INTO contacts (id_user) VALUES ('$iduser');");
            $ville = DB::insert("INSERT INTO user_ville (id_ville, id_user, annee_user_ville)VALUES ('$idville', '$iduser', '$date')");

            if(!empty($contact)) {
              $message = $message."-> Contact utilisateur créé\n";              
            }else {
              $status = "0";
              $message = "-> Contact utilisateur non créé\n";
            }

            if(!empty($ville)) {
              $message = $message."-> ville utilisateur créé\n";                
            }else {
              $status = "0";
              $message = "-> Ville utilisateur non créé\n";
            }

          }else {
            $status = "0";
            $message = "-> Compte utilisateur non créé\n";
          }
        }else {
          $status = "0";
          $message = "-> Compte utilisateur non créé\n";
        }

        $solution['status'] = $status;
        $solution['message'] = $message;

      }else{
        $solution['status'] = "0";
        $solution['message'] = "aucune valeur recus";
      }
        //dd($solution);
        return Response()->json(compact('solution'));
  }

  /**
  *  Reset Password
  **/
  public function resetPassword(Request $request) {
    $solution = array();
    if(!empty($request->nom) && !empty($request->telephone) && !empty($request->newPassword)) {

      $nom = $request->nom;
      $telephone = $request->telephone;
      $newPassword = md5($request->newPassword);

      $modifier = DB::update("UPDATE users SET password_user='$newPassword' WHERE nom_user='$nom' and telephone_user='$telephone';");

      if($modifier) {
        $solution['status'] = "1";
        $solution['message'] = "password réinitialisé";
      }else {
        $solution['status'] = "0";
        $solution['message'] = "erreur de réinitialisation";
      }

    }else{
      $solution['status'] = "0";
      $solution['message'] = "aucune valeur recus";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

  /**
  * modifier user
  **/
  public function modifierCompte(Request $request) {
    $solution = array();
    if((!empty($request->iduser) || !empty($request->telephone)) && !empty($request->newstatut)) {

      $iduser = $request->iduser;
      $telephone = $request->telephone;
      $newstatut = $request->newstatut;

      $modifier = DB::update("UPDATE users SET statut_user='$newstatut' WHERE id_user='$iduser' OR telephone_user='$telephone';");

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
      $solution['message'] = "aucune valeur recus";
    }
      //dd($solution);
      return Response()->json(compact('solution'));
  }

}
