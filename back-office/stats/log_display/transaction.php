<?php
session_start();
$login_page = '../../../connexion/login.php';
require('../../check_session.php');
require('../../../include/database.php');
require('../../../include/check_timeout.php');
require_once('../../../path.php');
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$title = 'Log des transactions';
require('../../head.php');
$lines = array_reverse(file('../../../log/log_transaction.txt'));
?>

<body>
    <?php
    $page = stats_main;
    include('../../navbar.php'); ?>
    <main class="container my-5">
        <h1 class="text-center my-5">Transactions</h1>
        <div class="form-group mb-2 pt-3 pb-2">
            <div class="d-flex gap-2">
                <input type="text" id="search_transaction" class="form-control searchBoxBack" placeholder="Rechercher par email">
                <div class="d-flex ms-2" style="gap: 0.5rem;">
                    <select id="statusFilter" class="form-select searchBoxBack">
                        <option value="">Statut</option>
                        <option value="Réussie">Réussi</option>
                        <option value="échouée">Echoué</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-striped table-bordered">
                <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th>Date/heure</th>
                        <th>Action</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody id="log_transaction">
                    <?php foreach ($lines as $line) {
                        preg_match(
                            '/^(\d{4}\/\d{2}\/\d{2}) - (\d{2}:\d{2}:\d{2}) - (.+?) (réussi|échoué|annulé) de (.+?)(?: - (?:en raison de : )?(.+))?$/',
                            trim($line),
                            $match
                        );
                        $dateTime = $match[1] . ' - ' . $match[2];
                        $action = $match[3];
                        $status = ucfirst($match[4]);
                        $email = strtolower($match[5]);
                        $note = '';
                        if (isset($match[6]))
                            $note = ucfirst($match[6]);
                    ?>
                        <tr>
                            <td class="align-middle"><?= $dateTime ?></td>
                            <td class="align-middle"><?= $action ?></td>
                            <td class="align-middle"><?= $email ?></td>
                            <td class="align-middle"><?= $status ?></td>
                            <td class="align-middle"><?= $note ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <h1 class="text-center my-5">Statistiques</h1>

        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-4 gap-2">
                <select id="monthStats" class="form-select searchBoxBack">
                    <option value="">Tout</option>
                    <option value="01">Janvier</option>
                    <option value="02">Février</option>
                    <option value="03">Mars</option>
                    <option value="04">Avril</option>
                    <option value="05">Mai</option>
                    <option value="06">Juin</option>
                    <option value="07">Juillet</option>
                    <option value="08">Août</option>
                    <option value="09">Septembre</option>
                    <option value="10">Octobre</option>
                    <option value="11">Novembre</option>
                    <option value="12">Décembre</option>
                </select>
                <select id="yearStats" class="form-select searchBoxBack">
                    <option value="">Tout</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
            <div class="d-flex align-items-center mb-3 gap-2">
                <h5>Nombre de transactions éffectuées : </h5>
                <h5 id="nb"></h5>
            </div>
            <div class="d-flex align-items-center mb-3 gap-2">
                <h5>Nombre de transactions annulées : </h5>
                <h5 id="cancel"></h5>
            </div>
            <div class="d-flex align-items-center mb-3 gap-2">
                <h5>Taux de transaction réussie : </h5>
                <h5 id="rate"></h5>
            </div>
            <div class="d-flex align-items-center mb-3 gap-2">
                <h5>Revenue totale : </h5>
                <h5 id="revenue"></h5>
            </div>
        </div>
    </main>

    <script>
        function fetchLogTransaction() {
            const query = document.getElementById('search_transaction').value;
            const status = document.getElementById('statusFilter').value;

            fetch(`/back-office/stats/log_display/search_transaction.php?search=${encodeURIComponent(query)}&status=${encodeURIComponent(status)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(data => {
                    document.getElementById('log_transaction').innerHTML = data;
                });
        }

        document.getElementById('search_transaction').addEventListener('input', fetchLogTransaction);
        document.getElementById('statusFilter').addEventListener('change', fetchLogTransaction);
    </script>
</body>
<script>
    function fetchStats() {
        const month = document.getElementById('monthStats').value;
        const year = document.getElementById('yearStats').value;

        fetch(`/back-office/stats/log_display/stat_transaction.php?month=${month}&year=${year}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('nb').textContent = data.nb ?? '0';
                document.getElementById('rate').textContent = data.rate ?? '0';
                document.getElementById('revenue').textContent = data.revenue ?? '0';
                document.getElementById('cancel').textContent = data.cancel ?? '0';
            })
            .catch(error => {
                console.error('Erreur fetch:', error);
                document.getElementById('nb').textContent = 'Erreur lors de la récuperation de statistiques';
                document.getElementById('rate').textContent = 'Erreur lors de la récuperation de statistiques';
                document.getElementById('revenue').textContent = 'Erreur lors de la récuperation de statistiques';
                document.getElementById('cancel').textContent = 'Erreur lors de la récuperation de statistiques';
            });
    }

    document.getElementById('monthStats').addEventListener('change', fetchStats);
    document.getElementById('yearStats').addEventListener('change', fetchStats);
    document.addEventListener('DOMContentLoaded', fetchStats);
</script>

</html>