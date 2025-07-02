<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
include '../db.php';
include 'header_admin.php';

$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');

$firstDayOfMonth = strtotime("$year-$month-01");
$daysInMonth = date('t', $firstDayOfMonth);
$startDayOfWeek = date('N', $firstDayOfMonth); 

$startDate = date('Y-m-d', $firstDayOfMonth);
$endDate = date('Y-m-t', $firstDayOfMonth);
$stmt = $pdo->prepare("SELECT * FROM chantiers WHERE (date_debut <= ? AND date_fin >= ?)");
$stmt->execute([$endDate, $startDate]);
$chantiers = $stmt->fetchAll();

$daysChantiers = [];
foreach ($chantiers as $c) {
    $debut = max(strtotime($c['date_debut']), $firstDayOfMonth);
    $fin = min(strtotime($c['date_fin']), strtotime($endDate));
    for ($d = $debut; $d <= $fin; $d += 86400) {
        $dayNum = (int)date('j', $d);
        $daysChantiers[$dayNum][] = $c['lieu'];
    }
}

function prevMonth($year, $month) {
    if ($month == 1) return [$year - 1, 12];
    else return [$year, $month - 1];
}
function nextMonth($year, $month) {
    if ($month == 12) return [$year + 1, 1];
    else return [$year, $month + 1];
}
list($prevYear, $prevMonth) = prevMonth($year, $month);
list($nextYear, $nextMonth) = nextMonth($year, $month);

$mois_francais = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];
?>

<h1>Agenda des chantiers — <?= $mois_francais[$month] . ' ' . $year ?></h1>

<div class="d-flex justify-content-between mb-3">
  <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" class="btn btn-outline-primary">&laquo; Mois précédent</a>
  <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" class="btn btn-outline-primary">Mois suivant &raquo;</a>
</div>

<table class="table table-bordered text-center align-middle" style="table-layout: fixed;">
  <thead class="table-light">
    <tr>
      <th>Lundi</th>
      <th>Mardi</th>
      <th>Mercredi</th>
      <th>Jeudi</th>
      <th>Vendredi</th>
      <th>Samedi</th>
      <th>Dimanche</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $currentDay = 1;
    $started = false;

    for ($week = 0; $week < 6; $week++) {
        echo '<tr>';
        for ($dow = 1; $dow <= 7; $dow++) {
            if (!$started && $dow == $startDayOfWeek) $started = true;

            if ($started && $currentDay <= $daysInMonth) {
                $classes = "";
                if (isset($daysChantiers[$currentDay])) {
                    $classes = "bg-success text-white fw-bold";
                }
                echo "<td class='$classes' style='height:100px; vertical-align:top;'>";
                echo "<div>$currentDay</div>";
                if (isset($daysChantiers[$currentDay])) {
                    foreach ($daysChantiers[$currentDay] as $lieu) {
                        echo "<small>- " . htmlspecialchars($lieu) . "</small><br>";
                    }
                }
                echo "</td>";
                $currentDay++;
            } else {
                echo "<td></td>";
            }
        }
        echo '</tr>';
        if ($currentDay > $daysInMonth) break;
    }
    ?>
  </tbody>
</table>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>