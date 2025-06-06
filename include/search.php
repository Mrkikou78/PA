<?php
session_start();
require_once('../include/database.php');
require('../include/check_timeout.php');
require_once __DIR__ . '/../path.php';

$query = $_POST['query'] ?? '';
$category = $_POST['category'] ?? '';


$users = $articles = $games = $tournois = $teams = [];
$response = [];


try {

    if ($category == 'users' || empty($category)) {
        $stmtUsers = $bdd->prepare("
            SELECT pseudo, nom, prenom 
            FROM utilisateurs 
            WHERE pseudo LIKE ? OR nom LIKE ? OR prenom LIKE ?");
        $stmtUsers->execute(['%' . $query . '%', '%' . $query . '%', '%' . $query . '%']);
        $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($category == 'articles' || empty($category)) {
        $stmtArticles = $bdd->prepare("SELECT titre FROM news WHERE titre LIKE ? OR contenue LIKE ?");
        $stmtArticles->execute(['%' . $query . '%', '%' . $query . '%']);
        $articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($category == 'games' || empty($category)) {
        $stmtGames = $bdd->prepare("SELECT nom FROM jeu WHERE nom LIKE ?");
        $stmtGames->execute(['%' . $query . '%']);
        $games = $stmtGames->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($category == 'tournois' || empty($category)) {
        $stmtTournois = $bdd->prepare("SELECT id_tournoi, nom_tournoi FROM tournoi WHERE nom_tournoi LIKE ?");
        $stmtTournois->execute(['%' . $query . '%']);
        $tournois = $stmtTournois->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($category == 'teams' || empty($category)) {
        $stmtTeams = $bdd->prepare("
        SELECT e.id_equipe AS id, e.nom 
        FROM equipe e
        LEFT JOIN membres_equipe me ON e.id_equipe = me.id_equipe
        LEFT JOIN utilisateurs u ON me.id_utilisateur = u.id_utilisateurs
        WHERE e.nom LIKE ? OR u.pseudo LIKE ?
        GROUP BY e.id_equipe
    ");
        $stmtTeams->execute(['%' . $query . '%', '%' . $query . '%']);
        $teams = $stmtTeams->fetchAll(PDO::FETCH_ASSOC);
    }
    $response = [
        'users' => $users,
        'articles' => $articles,
        'games' => $games,
        'tournois' => $tournois,
        'teams' => $teams,
    ];
} catch (PDOException $e) {

    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php $title = 'Recherche';
require('head.php');
if (isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])) {
    echo '<script src="check_timeout.js"></script>';
}

?>

<body>
    <?php
    include('header.php')
    ?>

    <main class="container my-5">
        <h1 class="text-center mb-4">Résultats de recherche pour: <?php echo htmlspecialchars($query); ?></h1>

        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                Une erreur est survenue : <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>


        <?php if ($users): ?>
            <div class="section mb-4">
                <h3 class="search-title">Utilisateurs</h3>
                <ul class="list-group">
                    <?php foreach ($users as $user): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($user['pseudo']); ?>
                            <a href="<?= profil ?>?user=<?php echo urlencode($user['pseudo']); ?>" class="btn btn-primary btn-sm">Voir le profil</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>


        <?php if ($articles): ?>
            <div class="section mb-4">
                <h3 class="search-title">Articles</h3>
                <ul class="list-group">
                    <?php foreach ($articles as $article): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($article['titre']); ?>
                            <a href="<?= actualite_article ?>?title=<?php echo urlencode($article['titre']); ?>" class="btn btn-primary btn-sm">Lire l'article</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($tournois): ?>
            <div class="section mb-4">
                <h3 class="search-title">Tournois</h3>
                <ul class="list-group">
                    <?php foreach ($tournois as $tournoi): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($tournoi['nom_tournoi']); ?>
                            <a href="<?= tournois_details ?>?id_tournoi=<?php echo urlencode($tournoi['id_tournoi']); ?>" class="btn btn-primary btn-sm">Voir le tournoi</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($games): ?>
            <div class="section mb-4">
                <h3 class="search-title">Jeux</h3>
                <ul class="list-group">
                    <?php foreach ($games as $game): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($game['nom']); ?>
                            <a href="<?= magasin_game ?>?name=<?php echo urlencode($game['nom']); ?>" class="btn btn-primary btn-sm">Voir le jeu</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($teams): ?>
            <div class="section mb-4">
                <h3 class="search-title">Equipes</h3>
                <ul class="list-group">
                    <?php foreach ($teams as $team): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($team['nom']); ?>
                            <a href="<?= team_details ?>?id_equipe=<?php echo urlencode($team['id']); ?>" class="btn btn-primary btn-sm">Voir l'équipe</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (empty($users) && empty($articles) && empty($games)  && empty($tournois) && empty($teams)): ?>
            <div class="alert alert-info" role="alert">
                Aucun résultat trouvé pour votre recherche.
            </div>
        <?php endif; ?>
    </main>
    <?php
    include('footer.php')
    ?>
</body>

</html>