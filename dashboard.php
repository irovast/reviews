<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login'); exit;
}
require 'config.php';

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

if ($isAdmin) {
  $bizStmt = db()->query("SELECT id, name FROM businesses");
} else {
  $bizStmt = db()->prepare("SELECT id, name FROM businesses WHERE user_id = (SELECT id FROM users WHERE username=?)");
  $bizStmt->execute([$_SESSION['user']]);
}
$bizList = $bizStmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Stats
$stats = [];
$ids = $bizList ? implode(",", array_keys($bizList)) : '0';
$sStmt = db()->query("SELECT * FROM stats WHERE business_id IN ($ids)");
foreach ($sStmt->fetchAll(PDO::FETCH_ASSOC) as $s) {
  $stats[$s['business_id']] = $s;
}

// Messages
$mStmt = db()->query("SELECT * FROM messages WHERE business_id IN ($ids) ORDER BY created_at DESC");
$messages = $mStmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background: #111; color: #fff; font-family: sans-serif; padding: 20px; }
    h2 { margin-top: 30px; }
    .card {
      background: #1c1c1c;
      padding: 16px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .stats-row { display: flex; gap: 20px; flex-wrap: wrap; }
    .stat-box {
      background: #222;
      padding: 16px;
      border-radius: 8px;
      flex: 1;
     
      text-align: center;
    }
    .stat-box h3 { margin: 0; font-size: 18px; color: #4285f4; }
    .stat-box p { font-size: 22px; margin: 8px 0 0; }

    .message-box {
      overflow-x: auto;
      overflow-y: auto;
      border: 1px solid #333;
      margin-top: 10px;
    }
    .toggle-title {
      cursor: pointer;
      color: #4eaaff;
      display: inline-block;
    }
    .stat-btn {
      
      font-size: 13px;
      padding: 4px 10px;
      background: #4285f4;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    table {
      border-collapse: collapse;
      width: max-content;
      min-width: 100%;
      table-layout: auto;
    }
    th, td {
      white-space: nowrap;
      padding: 8px 12px;
      border: 1px solid #333;
      font-size: 13px;
    }
    th { background: #222; color: #ccc; }
    td { background: #181818; color: #eee; }

    .pagination {
      margin-top: 10px;
      text-align: center;
    }
    .pagination button {
      background: #333;
      color: white;
      border: none;
      padding: 6px 10px;
      margin: 0 2px;
      border-radius: 5px;
      cursor: pointer;
    }
    .pagination button.active {
      background: #4285f4;
      font-weight: bold;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.7);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background: #222;
      padding: 20px;
      border-radius: 10px;
      max-width: 600px;
      width: 90%;
    }
    .modal-content label, input {
      display: block;
      margin-bottom: 10px;
    }
    .modal-content canvas {
      width: 100%;
      height: 300px;
    }
    .close-btn {
      float: right;
      font-size: 20px;
      color: #ccc;
      cursor: pointer;
    }
  </style>
</head>
<body>

<h1>📊 Dashboard</h1>

<?php foreach ($bizList as $bid => $bname): ?>
  <div class="card">
    <h2><?= htmlspecialchars($bname) ?></h2>
    <?php $s = $stats[$bid] ?? ['views'=>0, 'happy_clicks'=>0, 'angry_clicks'=>0]; ?>
    <div class="stats-row">
      <div class="stat-box"><h3>👁️ Views</h3><p><?= $s['views'] ?></p></div>
      <div class="stat-box"><h3>😊 Happy</h3><p><?= $s['happy_clicks'] ?></p></div>
      <div class="stat-box"><h3>😠 Angry</h3><p><?= $s['angry_clicks'] ?></p></div>
    </div>

    <h3 class="toggle-title" onclick="toggleMessages(<?= $bid ?>)">📬 Messages</h3>
    <button class="stat-btn" onclick="openStats(<?= $bid ?>)">📈 Statistics</button>

    <div class="message-box" id="messages-<?= $bid ?>" style="<?= $_SESSION['role']==='admin' ? 'display:none;' : '' ?>">
      <?php
        $filtered = array_values(array_filter($messages, fn($m) => $m['business_id'] == $bid));
        $chunks = array_chunk($filtered, 20);
      ?>
      <?php foreach ($chunks as $pageIndex => $chunk): ?>
        <table class="page" style="<?= $pageIndex > 0 ? 'display:none;' : '' ?>">
          <thead>
          <tr>
            <th>Name</th><th>Email/Phone</th><th>Message</th>
            <th>Device</th><th>IP</th><th>Location</th><th>Date</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($chunk as $m): ?>
            <tr>
              <td><?= htmlspecialchars($m['name']) ?></td>
              <td><?= htmlspecialchars($m['email']) ?></td>
              <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
              <td><?= htmlspecialchars($m['device']) ?></td>
              <td><?= htmlspecialchars($m['ip']) ?></td>
              <td><?= htmlspecialchars($m['location']) ?></td>
              <td><?= htmlspecialchars($m['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endforeach; ?>

      <?php if (count($chunks) > 1): ?>
        <div class="pagination" id="pagination-<?= $bid ?>">
          <?php foreach ($chunks as $i => $_): ?>
            <button class="<?= $i === 0 ? 'active' : '' ?>" onclick="showPage(<?= $bid ?>, <?= $i ?>)"><?= $i + 1 ?></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>

<!-- Chart Modal -->
<div class="modal" id="statModal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeStats()">×</span>
    <h3>📊 Statistics</h3>
    <form onsubmit="return false;">
      <input type="hidden" id="stat-bid">
      <label>From: <input type="date" id="stat-from" required></label>
      <label>To: <input type="date" id="stat-to" required></label>
      <button onclick="loadStats()">Show</button>
    </form>
    <canvas id="statChart"></canvas>
  </div>
</div>

<script>
  function toggleMessages(id) {
    const el = document.getElementById('messages-' + id);
    el.style.display = (el.style.display === 'none') ? 'block' : 'none';
  }

  function showPage(bid, page) {
    document.querySelectorAll(`#messages-${bid} .page`).forEach((el, i) => {
      el.style.display = (i === page) ? 'table' : 'none';
    });
    document.querySelectorAll(`#pagination-${bid} button`).forEach((btn, i) => {
      btn.classList.toggle('active', i === page);
    });
  }

  let statChart = null;
  function openStats(bid) {
    document.getElementById('statModal').style.display = 'flex';
    document.getElementById('stat-bid').value = bid;
  }

  function closeStats() {
    document.getElementById('statModal').style.display = 'none';
    if (statChart) { statChart.destroy(); }
  }

  function loadStats() {
    const bid = document.getElementById('stat-bid').value;
    const from = document.getElementById('stat-from').value;
    const to = document.getElementById('stat-to').value;

    fetch(`get_stats.php?bid=${bid}&from=${from}&to=${to}`)
      .then(res => res.json())
      .then(data => {
        if (statChart) statChart.destroy();
        const ctx = document.getElementById('statChart').getContext('2d');
        statChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [
              {
                type: 'bar',
                label: 'Views',
                data: data.views,
                backgroundColor: 'mediumpurple'
              },
              {
                type: 'line',
                label: 'Happy Clicks',
                data: data.happy_clicks,
                borderColor: 'green',
                backgroundColor: 'green',
                tension: 0.3
              },
              {
                type: 'line',
                label: 'Angry Clicks',
                data: data.angry_clicks,
                borderColor: 'red',
                backgroundColor: 'red',
                tension: 0.3
              }
            ]
          }
        });
      });
  }
</script>

</body>
</html>
