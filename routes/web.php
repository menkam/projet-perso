<?php


Route::singularResourceParameters();

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['guest']], function() {
    Route::get('/', function () {
        return view('welcome');
        //return view('auth.login');
    });
});

 Route::get('/hepic', function () {
        return view('hepic');
        //return view('auth.login');
    });

Auth::routes();

Route::group(['middleware' => ['auth']], function(){
	Route::get('/home', function () {
        return view('welcome');
    });
});

// Utilisateur
Route::get('androidVerifierTelephone', ['as'=>'androidVerifierTelephone', 'uses'=>'AndroidApi\AuthController@verifierTelephone']); // ok
Route::get('androidVerifierPassword', ['as'=>'androidVerifierPassword', 'uses'=>'AndroidApi\AuthController@verifierPassword']); // ok
Route::get('androidLogin', ['as'=>'androidLogin', 'uses'=>'AndroidApi\AuthController@login']); // ok
Route::get('androidRegister', ['as'=>'androidRegister', 'uses'=>'AndroidApi\AuthController@register']); // ok
Route::get('androidResetPassword', ['as'=>'androidResetPassword', 'uses'=>'AndroidApi\AuthController@resetPassword']); // ok
Route::get('androidModifierCompte', ['as'=>'androidModifierCompte', 'uses'=>'AndroidApi\AuthController@modifierCompte']); // ok

// Contact
Route::get('androidAjouterMembreContact', ['as'=>'androidAjouterMembreContact', 'uses'=>'AndroidApi\ContactController@ajouterMembre']); // ok
Route::get('androidModifierMembreContact', ['as'=>'androidModifierMembreContact', 'uses'=>'AndroidApi\ContactController@modifierMembre']); // ok
Route::get('androidListerMembreContact', ['as'=>'androidListerMembreContact', 'uses'=>'AndroidApi\ContactController@listerMembre']); // ok

// Groupe
Route::get('androidCreerGroupe', ['as'=>'androidCreerGroupe', 'uses'=>'AndroidApi\GroupeController@creerGroupe']); // ok
Route::get('androidRenommerGroupe', ['as'=>'androidRenommerGroupe', 'uses'=>'AndroidApi\GroupeController@renommerGroupe']); // ok
Route::get('androidModifierGroupe', ['as'=>'androidModifierGroupe', 'uses'=>'AndroidApi\GroupeController@modifierGroupe']); // ok
Route::get('androidListerGroupe', ['as'=>'androidListerGroupe', 'uses'=>'AndroidApi\GroupeController@listerGroupe']); // ok
Route::get('androidAjouterMembreGroupe', ['as'=>'androidAjouterMembreGroupe', 'uses'=>'AndroidApi\GroupeController@ajouterMembre']); // ok
Route::get('androidAModifierMembreGroupe', ['as'=>'androidAModifierMembreGroupe', 'uses'=>'AndroidApi\GroupeController@modifierMembre']); // ok
Route::get('androidListerMembreGroupe', ['as'=>'androidListerMembreGroupe', 'uses'=>'AndroidApi\GroupeController@listerMembre']); // ok

// Tchat
Route::get('androidCreerTchat', ['as'=>'androidCreerTchat', 'uses'=>'AndroidApi\TchatController@creer']); // enCours
Route::get('androidEffacerTchat', ['as'=>'androidEffacerTchat', 'uses'=>'AndroidApi\TchatController@effacer']); // enCours
Route::get('androidListerTchatMsgParContact', ['as'=>'androidListerTchatMsgParContact', 'uses'=>'AndroidApi\TchatController@liseterMsgParContact']); // enCours
Route::get('androidListerTchatMsg', ['as'=>'androidListerTchatMsg', 'uses'=>'AndroidApi\TchatController@liseterMsg']); // enCours

// Forum
Route::get('androidCreerForum', ['as'=>'androidCreerForum', 'uses'=>'AndroidApi\ForumController@creer']); // enCours
Route::get('androidModifierForum', ['as'=>'androidModifierForum', 'uses'=>'AndroidApi\ForumController@modifier']); // enCours
Route::get('androidListerForum', ['as'=>'androidListerForum', 'uses'=>'AndroidApi\ForumController@lister']); // enCours


// Continent
Route::get('androidGetAjouterContinent', ['as'=>'androidGetAjouterContinent', 'uses'=>'AndroidApi\ContinentController@ajouter']); // enCours
Route::get('androidGetListerContinent', ['as'=>'androidGetListerContinent', 'uses'=>'AndroidApi\ContinentController@lister']); // ok
Route::get('androidGetSupprimerContinent', ['as'=>'androidGetSupprimerContinent', 'uses'=>'AndroidApi\ContinentController@suprimer']); // enCours

// Pays
Route::get('androidGetAjouterPays', ['as'=>'androidGetAjouterPays', 'uses'=>'AndroidApi\RegionController@ajouter']); // enCours
Route::get('androidGetListerPays', ['as'=>'androidGetListerPays', 'uses'=>'AndroidApi\RegionController@lister']); // ok
Route::get('androidSupprimerPays', ['as'=>'androidSupprimerPays', 'uses'=>'AndroidApi\RegionController@suprimer']); // enCours

// Region
Route::get('androidGetAjouterRegion', ['as'=>'androidGetAjouterRegion', 'uses'=>'AndroidApi\RegionController@ajouter']); // enCours
Route::get('androidGetListerRegion', ['as'=>'androidGetListerRegion', 'uses'=>'AndroidApi\RegionController@lister']); // ok
Route::get('androidGetSupprimerRegion', ['as'=>'androidGetSupprimerRegion', 'uses'=>'AndroidApi\RegionController@suprimer']);  // enCours

// Departement
Route::get('androidGetAjouterDepartement', ['as'=>'androidGetAjouterDepartement', 'uses'=>'AndroidApi\DepartementController@ajouter']); // enCours
Route::get('androidGetListerDepartement', ['as'=>'androidGetListerDepartement', 'uses'=>'AndroidApi\DepartementController@lister']); // ok
Route::get('androidGetSupprimerDepartement', ['as'=>'androidGetSupprimerDepartement', 'uses'=>'AndroidApi\DepartementController@suprimer']); // enCours

// Ville
Route::get('androidAjouterVille', ['as'=>'androidAjouterVille', 'uses'=>'AndroidApi\VilleController@ajouter']); // enCours
Route::get('androidGetListerVille', ['as'=>'androidGetListerVille', 'uses'=>'AndroidApi\VilleController@lister']); // ok
Route::get('androidSupprimerVille', ['as'=>'androidSupprimerVille', 'uses'=>'AndroidApi\VilleController@suprimer']); // enCours