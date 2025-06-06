<?php
require_once __DIR__ . '/../path.php';
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$title = '404 NOT FOUND';
$pageCategory = 'error';
echo "<script>const pageCategory = '$pageCategory';</script>";
include('../include/head.php');
?>

<body>
    <?php
    include('../include/header.php');
    ?>
    <main class="container my-5">
        <div class="d-flex justify-content-center">
            <div class="col-10 col-sm-10 col-md-10 col-lg-8 col-xl-6 justify-content-center text-center p-5 my-5 connexion_box">
                <h2>Erreur 404</h2>
                <div class="line-with-letters montserrat-titre32 pt-2 pb-3">
                    <span class="line my-2"></span>
                </div>
                <h5 class="mb-2">Uh oh, vous vous êtes perdu ? Que cherchez vous ?</h5>
                <!-- Pssst... Essayez de cliquer sur la phrase ci dessous, une surprise vous attend ;) -->
                <p id="easter" class="mb-5">Retournons à l'accueil</p>
                <a href="<?= index_front ?>" class="btn btn-primary">Retour à l'acueil</a>
            </div>
        </div>
    </main>
    <?php
    include('../include/footer.php');
    ?>
    <script>
        document.getElementById('easter').addEventListener('click', function() {
            window.location.href = '/error_pages/easter.php';
        });
    </script>
</body>

</html>