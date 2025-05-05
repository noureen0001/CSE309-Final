<?php
$supabase_url = getenv('supabase_url');
$supabase_key = getenv('supabase_key');

function fetchFromSupabase($table, $filter = '') {
    global $supabase_url, $supabase_key;
    $url = "$supabase_url/rest/v1/$table?$filter";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$email = $_GET['email'] ?? '';
$name = $_GET['name'] ?? '';
$platforms = ['ORCID', 'DBLP', 'Semantic Scholar', 'SciProfiles', 'Google Scholar'];

$all_entries = fetchFromSupabase("publications_t", "email=eq." . urlencode($email));
$photo = $all_entries[0]['photo'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Publications of <?= htmlspecialchars($name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded shadow-sm">
            <div><h3><?= htmlspecialchars($name) ?></h3></div>
            <img src="<?= htmlspecialchars($photo) ?>" width="120" height="120" style="object-fit:cover; border-radius:10px;">
        </div>

        <ul class="nav nav-tabs" id="platformTabs">
            <?php foreach ($platforms as $i => $plat): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $i === 0 ? 'active' : '' ?>" data-bs-toggle="tab" href="#<?= strtolower(str_replace(' ', '_', $plat)) ?>"><?= $plat ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content bg-white p-4 border-bottom border-start border-end rounded-bottom shadow-sm">
            <?php foreach ($platforms as $i => $plat): 
                $filtered = array_filter($all_entries, fn($row) => $row['platform'] === $plat);
                $tab_id = strtolower(str_replace(' ', '_', $plat));
            ?>
                <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="<?= $tab_id ?>">
                    <?php if (count($filtered)): ?>
                        <ul class="list-group">
                            <?php foreach ($filtered as $entry): ?>
                                <li class="list-group-item">
                                    <h5><?= htmlspecialchars($entry['title']) ?></h5>
                                    <p><?= htmlspecialchars($entry['description']) ?></p>
                                    <a href="<?= htmlspecialchars($entry['link']) ?>" target="_blank"><?= $entry['link'] ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No entries under <?= $plat ?>.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
