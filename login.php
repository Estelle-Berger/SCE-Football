<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
?>
<section>
<div class="p-2 container-fluid">
    <form method="post" action="">
    <?php if(isset($_SESSION['error_msg'])){?>
        <div>
            <?= $_SESSION["error_msg"];
        unset($_SESSION["error_msg"]);
        ?>

        </div>
        <?php unset($_SESSION["message_delete"]);} ?>   
        <h3 class="d-flex justify-content-center">Connexion</h3>
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
                    </div>
                </div>
            </div>
        <div class="p-2 d-flex justify-content-center">
            <input class="btn btn-outline-black" type="submit" name="valid_login" value="Envoyer">
        </div>
    </form>
</div>
</section>
<?php
    require_once('./lib/footer.php');
?>