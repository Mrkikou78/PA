<?php
session_start();
require('../include/database.php');
$login_page = '../connexion/login.php';
require('../include/check_session.php');
require_once __DIR__ . '/../path.php';

$friendPseudo = htmlspecialchars($_POST['friend_pseudo'] ?? '');
if (empty($friendPseudo)) {
    header('Location: profil.php?error=no_user_specified');
    exit;
}

try {
    $stmt = $bdd->prepare("SELECT id_utilisateurs FROM utilisateurs WHERE pseudo = ?");
    $stmt->execute([$friendPseudo]);
    $friend = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$friend) {
        header('Location: profil.php?error=user_not_found');
        exit;
    }
    $friendId = $friend['id_utilisateurs'];
    $userId = $_SESSION['user_id'];
    $stmt = $bdd->prepare("
        SELECT * FROM relations 
        WHERE user_id1 = ? AND user_id2 = ? AND status = 'pending'
    ");
    $stmt->execute([$friendId, $userId]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        header('Location: profil.php?error=no_pending_request');
        exit;
    }
    $stmt = $bdd->prepare("
        DELETE FROM relations 
        WHERE user_id1 = ? AND user_id2 = ? AND status = 'pending'
    ");
    $stmt->execute([$friendId, $userId]);

    header('Location: profil.php?user=' . urlencode($friendPseudo) . '&success=friend_request_rejected');
    exit;
} catch (PDOException $e) {
    header('Location: profil.php?error=database_error');
    exit;
}
