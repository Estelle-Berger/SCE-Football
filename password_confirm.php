<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');

?>

<div class="p-2 container-fluid">
    <form method="post" action="">
        <legend class="d-flex justify-content-center">Création de mot de passe</legend>
            <div class="d-flex justify-content-center">
                <div class="p-2 border border-3 rounded d-flex justify-content-center">
                    <div class="col">
                    <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
                        <div class="p-2">
                            <label for="email"class="form-label">Email*</label>
                            <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                            placeholder="email" id="email" name="email" required>
                        </div>
                        <div class="p-2">
                            <label for="password"class="form-label">Mot de passe*</label>
                            <input type="password"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                            placeholder="******" id="password" name="password" required>
                        </div>
                        <div class="p-2">
                        <label for="password_confirm"class="form-label">Confirmation de mot de passe*</label>
                            <input type="password"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                            placeholder="******" id="password_confirm" name="password_confirm" required>
                        </div>
                        <div class="d-flex justify-content-center">
                            <input class="btn btn-outline-secondary" type="submit" name="save_password" value="Envoyer" >
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>

<?php
    require_once('./lib/footer.php');
?>