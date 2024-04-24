<?php 
session_start();
//------------fonction diverse-----------
function dates($date){
    setlocale(LC_TIME, 'fr_FR.utf8');
    $timestamp = strtotime($date);
    $date_formattee = date('d/m/Y',$timestamp);
    echo $date_formattee;
}
$hour = "";
function hour($hour){
    $timestamp = strtotime('today'.$hour);
    $hour_formattee = date('H:i',$timestamp);
    echo $hour_formattee;
}
//---------------sécurité----------------
function generate_token(){
    return bin2hex((random_bytes(32)));
};
function is_valid_token($token){
    return isset($_SESSION['token']) && hash_equals($_SESSION['token'],$token);
};
if (!isset($_SESSION['token'])){
    $_SESSION['token'] = generate_token();
};
function secure ($value){
    $secure = htmlspecialchars($value, ENT_QUOTES,'UTF-8');
    return $secure;
};
//---------------mot de passe system aléatoire--------------
function RandomString($nbchar)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $nbchar; $i++) {
            $randstring .= $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    };
//---------------connexion à la bdd------------
$dsn = "mysql:host=localhost;dbname=sce_football";
$username = "root";
$password1 = "";

try{
    $bdd = new PDO($dsn, $username, $password1);
    $bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Erreur de connexion : ".$e->getMessage();
}
//-----------------validation login-----------
if($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST['valid_login'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }else{
    $email = $_POST['email'];
    $email_save = secure($email);
    $password = $_POST['password'];
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    $password_save = $hashPassword;

    if($email != ""){
        $requete_login = $bdd->prepare("SELECT*FROM users WHERE email = '$email_save'");
        $requete_login->execute();
        if($requete_login->rowCount()==1){
            $user = $requete_login->fetch();
            if(password_verify($password, $user['password'])){
                if($user['password_system']==1){
                    header("Location: ./password_confirm.php");
                    exit();
                }
                else{
                    if ($user["statut"]=="1")
                    {
                        session_destroy();
                        session_start();
                        $_SESSION['isLogged']=true;
                        $_SESSION['firstname']=$user['firstname'];
                        $_SESSION['lastname']=$user['lastname'];
                        $_SESSION['statut']=$user['statut'];
                        $_SESSION['id']=$user['user_id'];
                        $userid = $user['user_id'];
                        $requete_profil = $bdd->prepare("SELECT profil_id FROM roles R INNER JOIN users U ON R.user_id = U.user_id WHERE u.user_id = :userid");
                        $requete_profil->execute(array(
                            "userid" => $userid
                        ));
                        $profils = $requete_profil->fetchAll();
                        $_SESSION['userid'] = $userid;

                        if($profils>0){
                        $_SESSION['selected_profil'] = $profils[0]["profil_id"];
                            header("Location: ./index.php");
                        }
                        else {
                            $_SESSION['error_msg'] = "Aucun profil trouvé pour cet utilisateur.";
                        }
                        exit(); 
                    }
                    else 
                    {
                        $_SESSION['error_msg'] = "Votre compte est désactivé.";
                    }
                }
            }
            else
            {
                $_SESSION['error_msg'] = "Email ou mot de passe incorrect !";
            }
        }   
        else
        {
            $_SESSION['error_msg'] = "Email ou mot de passe incorrect !";
        }
    }
}
}
//----------------confirmation du password------------------------
if(isset($_POST['save_password'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }
    else{
    if((isset($_POST['password'])) AND (isset($_POST['password_confirm']))){
        $email_password = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password_employe = filter_var($_POST['password'],FILTER_SANITIZE_SPECIAL_CHARS);
        $password_confirm = filter_var($_POST['password_confirm'],FILTER_SANITIZE_SPECIAL_CHARS);
        
        if($password_employe === $password_confirm){
            $hashPassword = password_hash($password_confirm, PASSWORD_DEFAULT);
            $requete_email = $bdd->prepare("SELECT email FROM users WHERE email = '$email_password'");
            $requete_email->execute();
            if($requete_email-> rowCount() == 1){
                $requete_password = $bdd->prepare("UPDATE users SET password = :pass, password_system = 0 WHERE email = '$email_password'");
                $requete_password->execute(
                    array(
                        "pass" => $hashPassword
                    )
                );
                header("Location:./login.php");
                exit();
            }
            else{
                $error_msg = 'Votre email est inconnu.';
            }
        }
        else {
            $error_msg = 'Vos mots de passe ne correspondent pas';
        }
    }
    else{
        $error_msg = 'Veuillez saisir et confirmer votre mot de passe.';
    };}
}
//--------------------insertion des utilisateurs-------------------
if(isset($_POST['save_user'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }
    else{
        $lastname = $_POST['lastname'];
        $lastname_save = secure($lastname);
        $firstname = $_POST['firstname'];
        $firstname_save = secure($firstname);
        $date_birth = $_POST['date_birth'];
        $date_birth_save = secure($date_birth);
        $job = $_POST['job'];
        $job_save = secure($job);
        $email = $_POST['email'];
        $email_save = secure($email);
        $password = RandomString(10);
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $password_save = $hashPassword;

        $requete_user = $bdd->prepare("INSERT INTO users VALUES(0, :lastname, :firstname, :birthday, :job, :email, :password, 1, 1)");
        $requete_user->execute(
            array(
                "lastname" => $lastname_save,
                "firstname" => $firstname_save,
                "birthday" => $date_birth_save,
                "job" => $job_save,
                "email" => $email_save,
                "password" => $password_save,
            )
            );
            
    $header= "MIME-Version: 1.0\r\n";
    $header.='From:"Florian"<estelleberger13@gmail.com>'."\n";
    $header.='Content-Type: text/html; charset="utf-8"'."\n";
    $header.='Content-Transfer-Encoding: 8bit';

    $message='
    <html>
        <body>
            <div align="center">
                Bonjour,<br> voici votre mot de passe temporaire :'.$password.'.<br>
                Il vous sera demandé de le modifier à la première connexion.
                <br>
            </div>
        </body>
    </html>
    ';
    $success = mail($email_save,"Création de votre compte", $message, $header);
    if (!$success) {
        echo "Compte créé ! Mais email pas envoyé, voici votre mot de passe temporaire : ".$password;
    }
}
}
//-------------------modification des utilisateurs--------------
if(isset($_POST['update_user'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }
    else{
        $id=$_GET["id"];
        $lastname = $_POST['lastname'];
        $lastname_save = secure($lastname);
        $firstname = $_POST['firstname'];
        $firstname_save = secure($firstname);
        $job = $_POST['job'];
        $job_save = secure($job);
        $email = $_POST['email'];
        $email_save = secure($email);
        $statut = $_POST['statut'];
        $statut_save = secure($statut);

        $requete_user = $bdd->prepare("UPDATE users SET lastname ='$lastname_save', firstname = '$firstname_save',
        job = '$job_save', email = '$email_save', statut = '$statut_save' WHERE user_id = '$id'" );
        $requete_user->execute();
        }}
//--------------------gestion des statuts-------------------
if(isset($_POST['save_statut'])){
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $statut = $_POST['statut'];
    $user_id = $_POST['statut_user_id'];
    echo "ttt".$statut.'-'.$user_id;
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }else{
        $requete_update = $bdd->prepare("UPDATE users SET statut = '$statut' WHERE user_id = '$user_id'");
        $requete_update->execute();
        header("Location: index.php");
        exit();
        }
    }   
}
//--------------------gestion des rôles-----------------------

if(isset($_POST['save_role'])){
        if(!is_valid_token($_POST['token'])){
            die("erreur CSRF détectée");
        }
        else{
            $old_profile = $_POST['old_profile'];
            $role = $_POST['role'];
            $id = $_GET['id'];

            if($old_profile!==null and $old_profile!=""){
                $requete = $bdd->prepare("UPDATE roles SET profil_id = :role_id WHERE user_id = :id");
                $requete->execute(
                    array(
                        "role_id" => $role,
                        "id"=> $id
                    )
                    );
            }else{
                $requete = $bdd->prepare("INSERT INTO roles VALUES (:role_id, :id)");
                $requete->execute(
                    array(
                        "role_id" => $role,
                        "id"=> $id
                    )
                    );
            }
            header("Location: ./admin_users.php");
            exit();
        }
}
//-------------------gestion des entraîneurs--------------------
if(isset($_POST['save_team'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }
    else{
        $old_teams = $_POST['old_team'];
        $team = $_POST['team'];
        $id = $_GET['id'];
        if($old_teams!==null and $old_teams!=""){
            $requete = $bdd->prepare("UPDATE teams_users SET team_id = :team_id WHERE user_id = :id");
            $requete->execute(
                array(
                    "team_id" => $team,
                    "id"=> $id
                )
                );
        }else{
            $requete = $bdd->prepare("INSERT INTO teams_users VALUES (:team_id, :id)");
            $requete->execute(
                array(
                    "team_id" => $team,
                    "id"=> $id
                )
                );
        }
        if ($_SESSION['selected_profil'] == 1) {
            header("Location: ./admin_users.php");
        } else {
            header("Location: ./admin_player.php");
        }
        exit();
    }
}

//--------------------insertion des équipes-----------------------
if(isset($_POST['save_teams'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }
    else{
        unset($_SESSION["message_save"]);
        unset($_SESSION["message_erreur"]);
        $title = $_POST['title'];
        $title_save = secure($title);
        $name = isset($_POST['name'.$user['user_id']]) ? secure($_POST['name'.$user['user_id']]) : 0;
        $comment = $_POST['comment'];
        $comment_save = secure($comment);
        // taille max de 5Mo
        $maxFileSize = 5*1024*1024;
        if(isset($_FILES['file']) AND $_FILES['file']['error'] ===UPLOAD_ERR_OK){
            $uploadedFile= $_FILES['file'];
            $typeImg = ['image/png', 'image/jpeg'];
            if(!in_array($uploadedFile['type'], $typeImg)){
                die("Erreur: Le type de fichier n'est pas autorisé.");
            }
            if($uploadedFile['size'] > $maxFileSize){
                die("Erreur: La taille du fichier dépasse la limite autorisée.");
            }
            $tmpName = $_FILES['file']['tmp_name'];
            $img = './uploads/logo/'.$title_save;
            $img_save = secure($img);
            move_uploaded_file($tmpName, $img_save);
        }
        else{
            $img_save = './assets/icon/test_logo_SCE.png';
        }
        try{
            $requete_team = $bdd->prepare("INSERT INTO teams VALUES(0, :title, :name, :comment, :img)");
            $requete_team->execute(
                array(
                    "title" => $title_save,
                    "name" => $name,
                    "comment" => $comment_save,
                    "img" => $img_save,
                )
                );
                $_SESSION["message_save"]= "L'équipe est enregistrée.";
                header("Location: ./admin_teams.php");
        }
        catch(PDOException $e){
            $_SESSION["message_erreur"] = "L'équipe n'est pas enregistrée.";
            header("Location: ./admin_teams.php");
        }
    }
};

//--------------------insertion des matches-----------------------
if(isset($_POST['save_matches'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }
    else{
        $date = $_POST['date'];
        $date_save = secure($date);
        $hour = $_POST['hour'];
        $hour_save = secure($hour);
        $address = $_POST['address'];
        $address_save = secure($address);
        $ground = $_POST['ground'];
        $ground_save = secure($ground);
        
        $opponent = $_POST['opponent'];
        $opponent_save = secure($opponent);
        // taille max de 5Mo
        $maxFileSize = 5*1024*1024;
        if(isset($_FILES['file']) AND $_FILES['file']['error'] ===UPLOAD_ERR_OK){
            $uploadedFile= $_FILES['file'];
            $typeImg = ['image/png', 'image/jpeg'];
            if(!in_array($uploadedFile['type'], $typeImg)){
                die("Erreur: Le type de fichier n'est pas autorisé.");
            }
            if($uploadedFile['size'] > $maxFileSize){
                die("Erreur: La taille du fichier dépasse la limite autorisée.");
            }
            $tmpName = $_FILES['file']['tmp_name'];
            $img_opponent = './uploads/logo/'.$opponent_save;
            $img_opponent_save = secure($img_opponent);
            move_uploaded_file($tmpName, $img_opponent_save);
        }
        else{
            $img_opponent_save = './assets/icon/ballon.png';
        }
        if(isset($_POST['team'])){
            $team = $_POST['team'];
            $team_save = secure($team);
        }

        $requete_matches = $bdd->prepare("INSERT INTO matches VALUES(0, :dateTT, :hourTT, :addressTT, :ground, 0, 0, '','', :opponent, :img_opponent, :team)");
        $requete_matches->execute(
            array(
                "dateTT" => $date_save,
                "hourTT" => $hour_save,
                "addressTT" => $address_save,
                "ground" => $ground_save,
                "opponent" => $opponent_save,
                "img_opponent" => $img_opponent_save,
                "team" => $team_save
            )
        );
    }
    }else{
        $error_msg = 'Erreur de saisie.';
    }
//--------------------faire la sélection des joueurs----------------------
if(isset($_POST['save_selection'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }
    else{
        if(isset($_POST['match_id'])){
            $match_id = $_POST['match_id'];
            
        }
        $requete = $bdd->prepare("SELECT * FROM users");
        $requete->execute();
        $users = $requete->fetchAll();
        foreach($users as $user){
            foreach($user as $key => $value)
                $user[$key] = secure($value);

            if(isset($_POST['present'.$user['user_id']])){
                $player = $user['user_id'];
                
                $requete_convocation = $bdd->prepare("INSERT INTO convocations VALUES (0, :player, :match_id, 1)");
                $requete_convocation->execute(
                    array(
                        "match_id" => $match_id,
                        "player" => $player
                    )
                );
            }
        }
        header("Location: ./admin_match_sheet.php?id=".$match_id);
    exit();
    }
}
//------------------------insertion du compte rendu du match -------------------
if(isset($_POST['save_report'])){
    if(!is_valid_token($_POST['token'])){
        die("erreur CSRF détectée");
    }
    else{
    $score_local = $_POST['score_local'];
    $score_local_save = secure($score_local);
    $score_opponent = $_POST['score_opponent'];
    $score_opponent_save = secure($score_opponent);
    $comment = $_POST['comment'];
    $comment_save = secure($comment);
    $maxFileSize = 20*1024*1024;
        if(isset($_FILES['file']) AND $_FILES['file']['error'] ===UPLOAD_ERR_OK){
            $uploadedFile= $_FILES['file'];
            $typeImg = ['image/png', 'image/jpeg'];
            if(!in_array($uploadedFile['type'], $typeImg)){
                die("Erreur: Le type de fichier n'est pas autorisé.");
            }
            if($uploadedFile['size'] > $maxFileSize){
                die("Erreur: La taille du fichier dépasse la limite autorisée.");
            }
            $tmpName = $_FILES['file']['tmp_name'];
            $img_match = './uploads/logo/'.$_FILES['file'];
            $img_match_save = secure($img_match);
            move_uploaded_file($tmpName, $img_match_save);
        }else{
            $img_match_save = '';
        }
        if(isset($_GET['id'])){
            $match_id = $_GET['id'];
        }
        $requete_convocation = $bdd->prepare("UPDATE matches SET score_local = :score_local, score_opponent = :score_opponent, comment = :comment, img_match = :img_match WHERE match_id = :id");
        $requete_convocation->execute(
        array(
            "score_local" => $score_local_save,
            "score_opponent" => $score_opponent_save,
            "comment" => $comment_save,
            "img_match" => $img_match,
            "id" => $match_id
            )
    );
    $requete = $bdd->prepare("SELECT user_id FROM users U inner join convocations C on U.user_id = C.player WHERE C.present = 1 and match_id = :id");
    $requete->execute(
        array(
            "id" => $match_id
        )
    );
    $users = $requete->fetchAll();
    foreach($users as $user){
        $timeplay = isset($_POST['timeplay'.$user['user_id']]) ? secure($_POST['timeplay'.$user['user_id']]) : 0;
        $goal = isset($_POST['goal'.$user['user_id']]) ? secure($_POST['goal'.$user['user_id']]) : 0;
        $cards = isset($_POST['cards'.$user['user_id']]) ? secure($_POST['cards'.$user['user_id']]) : 0;
        $pass = isset($_POST['pass'.$user['user_id']]) ? secure($_POST['pass'.$user['user_id']]) : 0;

        $player = $user['user_id'];
        $requete_convocation = $bdd->prepare("INSERT INTO reports VALUES (:player, :match_id, :timeplay, :goal, :cards, :pass)");
        $requete_convocation->execute(
            array(
                "player" => $player,
                "match_id" => $match_id,
                "timeplay" => $timeplay,
                "goal" => $goal,
                "cards" => $cards,
                "pass" => $pass
            )
        );
    }
    header("Location: ./report.php?id=".$match_id);
    exit();
}
}
    

