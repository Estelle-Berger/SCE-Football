<?php
require_once('config.php');
unset($_SESSION["message_save"]);
unset($_SESSION["message_erreur"]);
unset($_SESSION["message_delete"]);
$id = $_GET['id'];
$requete = $bdd->prepare("DELETE FROM teams WHERE teams_id = :id");
$requete->bindParam(':id', $id, PDO::PARAM_INT);
$requete->execute();
$_SESSION["message_delete"] = "L'équipe a été supprimé";
    header("Location: ../admin_teams.php");
exit();
?>