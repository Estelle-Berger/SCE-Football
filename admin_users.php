<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');

?>
<div class="p-2 d-flex justify-content-start">
    <?php if ($_SESSION['selected_profil']!=3){?><a href="./admin_user.php" class="btn btn-outline-secondary" type="submit">Création d'utilisateur</a><?php }?>
</div>
<div>
    <h3 class="d-flex justify-content-center">Liste des utilisateurs</h3>
</div>
<div class="p-3">
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Age</th>
                <th>Poste</th>
                <th>Equipe</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $requete_users = $bdd->prepare("SELECT 
            U.user_id,
            U.lastname,
            U.firstname,
            U.date_birth,
            U.statut,
            T.title,
            O.PosteName as job,
            P.profil_name
            FROM users U 
            left join roles R on R.user_id = U.user_id 
            left join profils P on P.profil_id = R.profil_id 
            left join teams_users TU on TU.user_id = U.user_id
            left join teams T on T.team_id = TU.team_id
            left join postes O on O.Poste_id = U.job");
            $requete_users->execute();
            $listeUsers = $requete_users->fetchAll();
            foreach($listeUsers as $users){
                foreach($users as $key => $value)
                $users[$key] = secure($value);

                $userid = $users['user_id'];
                $titleTeam = $users['title'];
            ?>
            <tr class="table-light">
                <td><button type="submit"><a href="./update_user.php?id=<?=$users['user_id'];?>"><img src="./assets/icon/profil.png"  width="24" height="24" alt="profil"></a></button></td>
                <td><button type="submit"><a href="./update_role.php?id=<?=$users['user_id'];?>"><img src="./assets/icon/parametre.png" width="24" height="24" alt="parametre"></a></button></td>
                <td><?=$users['profil_name'];?></td>
                <td><?php
                    if($users['statut'] == '1'){
                        echo "Actif";
                    }else{
                        echo "Désactivé";
                    }
                    ?>
                </td>
                <td><?=$users['lastname'];?></td>
                <td><?=$users['firstname'];?></td>
                    <?php
                    $users['date_birth'] = new DateTime($users['date_birth']);
                    $day = new DateTime();
                    $diff = $day->diff($users['date_birth']);
                    $age = $diff->y;
                    ?>
                <td><?=$age?></td>
                <td><?=$users['job'];?></td>
                <td><?php 
                    if($titleTeam==!null){
                        echo "$titleTeam";
                    }else{
                        echo "-";
                    }?>
                </td>
                <?php }?>
            </tr>
        </tbody>
    </table>
</div>

<?php
    require_once('./lib/footer.php');
?>