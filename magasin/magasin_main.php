<?php
session_start();
require('../include/check_timeout.php');
require('../include/database.php');
require_once __DIR__ . '/../path.php';

$stmt = $bdd->query("SELECT id_jeu, nom, prix, image FROM jeu LIMIT 3");
$carouselGames = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtAllGames = $bdd->query("SELECT id_jeu, nom, prix, image FROM jeu ORDER BY note_jeu DESC LIMIT 3");
$games = $stmtAllGames->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<?php
$title = 'Boutique';
$pageCategory = 'magasin';
echo "<script>const pageCategory = '$pageCategory';</script>";
include('../include/head.php');
if (isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])) {
    echo '<script src="../include/check_timeout.js"></script>';
}
?>

<body>
    <?php include('../include/header.php'); ?>

    <main class="container mb-5">
        <?php if (isset($_GET['message']) && $_GET['message'] == 'bdd') { ?>
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                Erreur de la base de données, veuillez reéssayer plus tard !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } elseif (!empty($_GET['error'])) { ?>
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <h1 class="text-center mt-5 mb-4">Boutique de jeux</h1>

        <?php if (count($carouselGames) > 0): ?>
            <div class="d-flex justify-content-center mb-5">
                <div class="carousel-container mx-auto" style="max-width: 800px;">
                    <div id="gameCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-indicators">
                            <?php foreach ($carouselGames as $index => $game): ?>
                                <button type="button" data-bs-target="#gameCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-inner">
                            <?php foreach ($carouselGames as $index => $game): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <?php if (!empty($game['image'])): ?>
                                        <img src="../back-office/uploads/<?= htmlspecialchars($game['image']) ?>" onclick="window.location.href='<?= magasin_game . '?id=' . $game['id_jeu'] ?>'" class="d-block w-100" alt="<?= htmlspecialchars($game['nom']) ?>" style="height: 400px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="/magasin/img/no_image.png" class="d-block w-100" alt="Aucune image" onclick="window.location.href='<?= magasin_game . '?id=' . $game['id_jeu'] ?>'" style="height: 400px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5><?= htmlspecialchars($game['nom']) ?></h5>
                                        <p><strong>Prix :</strong> <?= htmlspecialchars($game['prix']) ?> €</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#gameCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#gameCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <h2 class="mb-3 text-center">Meilleurs jeux</h2>
        <div class="row">
            <?php foreach ($games as $game): ?>
                <div class="col-md-4 mb-4 d-flex align-items-stretch">
                    <div class="card shadow-sm w-100 d-flex flex-column">
                        <?php if (!empty($game['image'])): ?>
                            <img src="../back-office/uploads/<?= htmlspecialchars($game['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($game['nom']) ?>">
                        <?php else: ?>
                            <img src="../../assets/img/no_image.png" class="card-img-top" alt="Aucune image">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($game['nom']) ?></h5>
                            <p class="card-text"><strong>Prix :</strong> <?= htmlspecialchars($game['prix']) ?> €</p>
                            <div class="mt-auto d-flex justify-content-between gap-2 align-items-center">
                                <a href="<?= magasin_game ?>?id=<?= $game['id_jeu'] ?>" class="btn btn-magasin btn-outline-primary w-50 mt-3 h-50">Voir détails</a>
                                <button class="btn btn-magasin btn-success mt-3 btn-add-to-cart h-50" data-id="<?= $game['id_jeu'] ?>">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include('../include/footer.php'); ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".btn-add-to-cart").forEach(button => {
                button.addEventListener("click", async () => {
                    const gameId = button.getAttribute("data-id");

                    const response = await fetch(`../panier/add_to_cart.php?id=${gameId}`);
                    const data = await response.json();

                    const alertBox = document.createElement("div");
                    alertBox.className = `alert mt-3 text-center alert-${data.status === "success" ? "success" : "danger"}`;
                    alertBox.textContent = data.message;

                    const alertContainer = document.getElementById("alert-container");
                    alertContainer.appendChild(alertBox);

                    setTimeout(() => alertBox.remove(), 5000);

                    const panierCount = data.panierCount;
                    PHP.
                    updatePanierBadge(panierCount);
                });
            });
        });

        function updatePanierBadge(count) {
            const badge = document.querySelector(".panier-badge");
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'inline';
                } else {
                    badge.style.display = 'none';
                }
            }
        }
    </script>
</body>

</html>

<style>
    .card {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        display: flex;
        flex-direction: column;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        padding: 1.2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-text {
        margin-bottom: auto;
    }

    .btn-magasin {
        font-weight: 500;
    }

    .carousel-container {
        padding: 0 !important;
        margin: 0 auto;
        position: relative;
    }

    .carousel-inner {
        text-align: center;
    }

    .carousel-caption {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
    }

    .card-img-top {
        height: 250px;
        object-fit: cover;
    }
</style>