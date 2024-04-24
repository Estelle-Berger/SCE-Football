<?php
    require_once('config.php');   
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./lib/style.css">
    <title>Club de football SCE</title>
</head>
<body>

<header class="">
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
            <img src="./assets/icon/test_logo_SCE.png" width="70" height="70" alt="Logo du club">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav nav-underline me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="./matches.php">Matchs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./admin_teams.php">Equipes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contact.php">Contact</a>
                    </li>
                <li>
                <?php 
                
                if(isset($_SESSION['isLogged']) AND $_SESSION['isLogged']==true){
                    if(isset($_SESSION['selected_profil']) AND $_SESSION['selected_profil']== 1){?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Administration</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="./admin_teams.php">Gestion des équipes</a></li>
                            <li><a class="dropdown-item" href="./admin_users.php">Gestion des utilisateurs</a></li>
                            <li><a class="dropdown-item" href="./admin_matches.php">Gestion des matches</a></li>
                        </ul>
                    </li>
                    <?php } 
                    if(isset($_SESSION['selected_profil']) AND $_SESSION['selected_profil'] == 2){?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Entraîneur</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="./admin_player.php">Gestion des joueurs</a></li>
                            <li><a class="dropdown-item" href="./admin_matches.php">Gestion des matches</a></li>
                        </ul>
                    </li>
                    <?php }
                    if(isset($_SESSION['selected_profil']) AND $_SESSION['selected_profil'] == 3){?>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Joueur</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/">Statistiques personnel</a></li>
                            <li><a class="dropdown-item" href="/">Statistiques de l'équipes</a></li>
                        </ul>
                    </li>
                    <?php
                    }else {?>
                    
                <?php }}?>
            </ul>
            <Div>
            <?php if(isset($_SESSION['isLogged']) AND $_SESSION['isLogged']==true){?>
                <a href="logout.php" type="button" class="login btn btn-outline-black me-2" style="vertical-align: inherit;">Déconnexion</a>
                <?php } else {?> 
                    <a href="login.php" type="button" class="login btn btn-outline-black me-2" style="vertical-align: inherit;">Se connecter</a>
                <?php } ?>
            </Div>
        </div>
    </div>
</nav>
</header>