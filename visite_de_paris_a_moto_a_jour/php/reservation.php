<?php
require_once "fonctions.php";
if(isset($_POST["reserver"])){
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $lieu = $_POST["lieu"];
    $datepc = $_POST["datepc"];
    $societe = $_POST["societe"];
    $facturation = $_POST["facturation"];
    $information = $_POST["information"];

    reserver($nom, $prenom, $email, $tel, $lieu, $datepc, $societe, $facturation, $information);
}