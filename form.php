<?php

require_once("inc/formulaire.class.php");

if (isset($_POST) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['tel']) && isset($_POST['sujet']) && isset($_POST['message'])) {
   extract($_POST);

   if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($tel) && !empty($sujet) && !empty($message)) {
      $nom = $Formulaire->verifDataMail($nom);
      $prenom = $Formulaire->verifDataMail($prenom);
      $email = $Formulaire->verifDataMail($email);
      $tel = $Formulaire->verifDataMail($tel);
      $sujet = $Formulaire->verifDataMail($sujet);
      $message = $Formulaire->verifDataMail($message);

      //envoie mail pour le proprio du formulaire
      $monNom = "Guillaume RICHARD";
      $destinataire = "admin@guillaume-richard.fr";

      $sujetMail = "Tutoriel formulaire de contact : $prenom $nom vous a envoyé un message";

      $messageMail = "un nouveau message est arrivé \n De la part de $prenom $nom \n
			Email : $email \n
			Téléphone : $tel \n
			Sujet : $sujet \n
			Message : $message 
		";

      $header = "From : " . $nom . "<" . $email . ">";
      mail($destinataire, $sujetMail, $messageMail, $header);

      $sujet = "Vous avez envoyé un message sur le site de $monNom";

      $messageMail = "Vous avez envoyé le message suivant : $message ";

      $headerVisiteur = "From : " . $monNom . "<" . $destinataire . ">";
      mail($email, $sujet, $messageMail, $headerVisiteur);

      header("Location: index.php?yes");
   } else {
      header("Location: index.php?no");
   }
}