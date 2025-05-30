<?php
session_start();
$login_page = '../connexion/login.php';
require('../include/check_session.php');
require('../include/check_timeout.php');
require_once __DIR__ . '/../path.php';
require('../include/database.php');

$search = $_GET['search'] ?? '';
$stmt = $bdd->prepare("
    SELECT id_equipe, nom, niveau 
    FROM equipe 
    WHERE nom LIKE ?
    ORDER BY nom ASC
");
$stmt->execute(['%' . $search . '%']);
$teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<?php
$title = 'Liste des équipes';
$pageCategory = 'equipe';
echo "<script>const pageCategory = '$pageCategory';</script>";
require('../include/head.php');
if (isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])) {
    echo '<script src="../include/check_timeout.js"></script>';
}
?>

<body>
    <?php
    include('../include/header.php');
    include('../profil/navbar.php');
    ?>

    <div class="container my-5">
        <h1 class="mb-4 text-center">Liste des équipes</h1>
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Rechercher une équipe..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>
        <?php if (count($teams) > 0): ?>
            <div class="table-responsive" style="max-height: 80vh; overflow-y: auto;">
                <table class="table table-striped">
                    <thead style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Nom de l'équipe</th>
                            <th>Niveau</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teams as $team): ?>
                            <tr>
                                <td><?= htmlspecialchars($team['nom']) ?></td>
                                <td><?= htmlspecialchars($team['niveau']) ?></td>
                                <td>
                                    <a href="<?= team_details ?>?id_equipe=<?= $team['id_equipe'] ?>" class="btn btn-sm btn-secondary">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">Aucune équipe trouvée.</p>
        <?php endif; ?>
        <div class="text-center mt-4 d-flex flex-row justify-content-center gap-2">
            <a href="<?= create_team ?>" class="btn btn-primary">Créer une nouvelle équipe</a>
            <a href="<?= tournois_main ?>" class="btn btn-primary">Retourner aux tournois</a>
        </div>
    </div>
    <?php
    include('../include/footer.php');
    ?>
</body>

</html>