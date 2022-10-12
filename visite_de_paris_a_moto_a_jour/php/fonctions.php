<?php

function dbConnexion(){
    $connexion = null;
    try{
        $connexion = new PDO("mysql:host=localhost;dbname=visite_de_paris_a_moto", "root", "");
    }catch(Exception $e){
        $connexion = $e->getMessage();
    }
    return $connexion;
}

echo dbConnexion();

function reserver($nom, $prenom, $email, $tel, $lieu, $datepc, $societe, $facturation, $information){
    ajoutClient($nom, $prenom, $email, $tel, $lieu, $datepc, $societe, $facturation, $information);
}

function ajoutClient($nom, $prenom, $email, $tel, $lieu, $datepc, $societe, $facturation, $information){
    $connexion = dbConnexion();
    // verifier si l'email existe
    $request = $connexion->prepare("SELECT * FROM clients WHERE email = ?");
    $request->execute(array($email));
    $client = $request->fetch();

    if(empty($client)){
        $request = $connexion->prepare("INSERT INTO clients (nom, prenom, email, telephone, societe) VALUES (?, ?, ?, ?, ?)");
        $request->execute(array($nom, $prenom, $email, $tel, $societe));

        $req = $connexion->prepare("SELECT * FROM clients WHERE email = ?");
        $req->execute(array($email));
        $c = $req->fetch();
        valideReservation($lieu, $datepc, $facturation, $information, $c['id_client']);
    }else{
        valideReservation($lieu, $datepc, $facturation, $information, $client['id_client']);
    }
}

function valideReservation($lieu, $datepc, $facturation, $information, $client){
    $connexion = dbConnexion();
    $request = $connexion->prepare("INSERT INTO reservations (lieu_prise_en_charge, date_prise_en_charge, adresse_facturation, info_complementaire, client) VALUES (?, ?, ?, ?, ?)");
    $request->execute(array($lieu, $datepc, $facturation, $information, $client));
}