<?php
session_start();
require('../include/check_timeout.php');
require('../include/database.php');
require('../include/check_session.php');

$id_utilisateur = $_SESSION['user_id'];

$stmt = $bdd->prepare("
    SELECT j.id_jeu, j.nom, j.prix, j.image, b.date_achat 
    FROM boutique b
    JOIN jeu j ON b.id_jeu = j.id_jeu
    WHERE b.id_utilisateur = ?
    ORDER BY b.date_achat DESC
");
$stmt->execute([$id_utilisateur]);
$achats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<?php
$title = 'Historique des Achats';
$pageCategory = 'profil';
echo "<script>const pageCategory = '$pageCategory';</script>";
include('../include/head.php');
if (isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])) {
    echo '<script src="../include/check_timeout.js"></script>';
}
?>

<body>
    <?php include('../include/header.php');
    include('navbar.php'); ?>

    <div class="container my-5">
        <h1 class="text-center mb-5">Historique de vos achats</h1>

        <?php if (count($achats) > 0): ?>
            <div class="row">
                <?php foreach ($achats as $jeu): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <?php if (!empty($jeu['image'])): ?>
                                <img src="../back-office/uploads/<?= htmlspecialchars($jeu['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($jeu['nom']) ?>" style="height: 250px; object-fit: cover;">
                            <?php else: ?>
                                <img src="/magasin/img/no_image2.png" class="card-img-top" alt="Jeu" style="height: 250px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($jeu['nom']) ?></h5>
                                <p class="card-text mb-1"><strong>Prix :</strong> <?= ($jeu['prix'] != 0 ? htmlspecialchars($jeu['prix']) : 'Gratuit') ?> €</p>
                                <p class="text-muted"><small>Acheté le : <?= date('d/m/Y à H:i:s', strtotime($jeu['date_achat'])) ?></small></p>
                                <div class="mt-auto d-flex flex-column flex-sm-row justify-content-center">
                                    <a href="<?= magasin_game ?>?id=<?= $jeu['id_jeu'] ?>"
                                        class="btn btn-magasin btn-outline-primary flex-fill mt-3 d-flex align-items-center justify-content-center text-center small">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">Vous n'avez pas encore acheté un jeu.</p>
        <?php endif; ?>

        <div class="text-center mt-5">
            <a href="<?= magasin_main ?>" class="btn btn-primary">Parcourir les jeux</a>
            <a href="<?php my_account ?>" class="btn btn-secondary">Retour au profil</a>
        </div>
    </div>

</body>
<?php include('../include/footer.php'); ?>

</html>