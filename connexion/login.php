<?php
session_start();
require_once __DIR__ . '/../path.php';
if (!empty($_SESSION['user_email'])) {
    header('Location: ../' . index_front);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$title = 'Se connecter';
include('../include/head.php')
?>

<body>
    <?php include('../include/header.php'); ?>
    <main>
        <div class="d-flex justify-content-center my-5">
            <div class="col-10 col-sm-10 col-md-10 col-lg-8 col-xl-6 justify-content-center text-center p-5 my-5 connexion_box">

                <h1 class="my-3">Se connecter</h1>

                <?php if (isset($_GET['message']) && !empty($_GET['message'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
                        <?= htmlspecialchars($_GET['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif ?>

                <form method="post" action="login_verification.php">
                    <div class="d-flex flex-column pt-2 py-3 row-gap-1">
                        <label for="email" class="form-label d-block text-start mb-1">Identifiant/Email *</label>
                        <input type="email" name="email" id="email" placeholder="exemple@gmail.com" required aria-describedby="emailHelp" class="form-control input_field" value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>">
                        <label for="email" class="form-label d-block text-start mt-2 mb-1">Mot de passe *</label>
                        <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" class="form-control input_field" required>

                        <div class="d-flex flex-column pt-3 mt-3">
                            <input type="Submit" class="btn btn-primary" value="Me connecter">
                        </div>
                    </div>
                </form>

                <div class="line-with-letters montserrat-titre32 my-3">
                    <span class="line"></span>OU<span class="line"></span>
                </div>


                <div class="d-flex flex-column pt-2 py-2 row-gap-1 lato16">
                    <a href="https://accounts.google.com/" target="_blank" class="btn btn-secondary google_login">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0 0 48 48">
                            <path fill="#fbc02d" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12	s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20	s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                            <path fill="#e53935" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039	l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                            <path fill="#4caf50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36	c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                            <path fill="#1565c0" d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571	c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                        </svg>
                    </a>

                    <a href="https://www.facebook.com/?locale=fr_FR" target="_blank" class="btn btn-secondary facebook_login my-2">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a href="https://support.apple.com/en-us/111001" target="_blank" class="btn btn-secondary apple_login">
                        <i class="bi bi-apple"></i>
                    </a>

                </div>


                <p class="lato16 my-2" style="margin:0.5rem;">
                    <a href="<?= forgot_mdp ?>" id="creer_compte">Mot de passe oublié ?</a>
                </p>

                <div class="line-with-letters montserrat-titre32 pt-2 pb-3">
                    <span class="line my-2"></span>
                </div>
                <div>
                    <h6 class="mb-1">
                        Vous êtes nouveau ?
                    </h6>
                    <a class="lato16" href="<?= inscription ?>" id="creer_compte">Créer un compte</a>
                </div>
            </div>
        </div>
    </main>
    <?php
    include("../include/footer.php");
    ?>

    <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal">Vérification de l'email requis</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    Veuillez vérifier votre email avant de vous connectez !
                </div>

                <div class="modal-footer">
                    <a href="<?= resend_verify_inscrire ?>" class="btn btn-primary">Vérifier mon email</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const returnParam = urlParams.get('error');

        if (returnParam == 'email_verification') {
            var modal = new bootstrap.Modal(document.getElementById('modal'), {
                keyboard: false
            });
            modal.show();
        }
    </script>
</body>

</html>