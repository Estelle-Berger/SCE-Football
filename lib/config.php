<?php 
session_start();
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
                        $requete_profil = $bdd->prepare("SELECT profil_id FROM roles R INNER JOIN users U ON R.user_id = U.user_id");
                        $requete_profil->execute();
                        $profils = $requete_profil->fetchAll();
                        $_SESSION['userid'] = $userid;

                        $_SESSION['profils'] = $profils;
                        $_SESSION['selected_profil'] = $profils[0];
                        if($profils>0){
                            $_SESSION['selected_profil'] = $profils;
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
        header("Location: ./index.php");
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
        $name = $_POST['name'];
        $name_save = secure($name);
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
                    "name" => $name_save,
                    "comment" => $comment_save,
                    "img" => $img_save,
                )
                );
                $_SESSION["message_save"]= "L'équipe est enregistré.";
                header("Location: ./admin_teams.php");
        }
        catch(PDOException $e){
            $_SESSION["message_erreur"] = "L'équipe n'est pas enregistré.";
            header("Location: ./admin_teams.php");
        }
    }
}

//--------------------insertion des matches-----------------------
//--------------------faire les convocations----------------------