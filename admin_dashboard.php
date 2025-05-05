<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit;
}

// Supabase config
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

// Fetch admin name
$admins = fetchFromSupabase("admin");
$admin_name = $admins[0]['name'] ?? 'Admin';

// Fetch all publications
$publications = fetchFromSupabase('publications_t');
$unique_authors = [];

if (is_array($publications)) {
    foreach ($publications as $pub) {
        if (isset($pub['email'], $pub['name'])) {
            $key = $pub['email'] . '_' . $pub['name'];
            if (!isset($unique_authors[$key])) {
                $unique_authors[$key] = $pub;
            }
        }
    }
}

$tables = [
    'profile_details_t', 'awards_t', 'teaching_t', 'publications_t',
    'conferences_t', 'supervisor_t', 'research_interests_t',
    'postgraduate_courses_t', 'homepage_t', 'research_projects_t',
    'activities_t', 'advance_training_t', 'interview_t'
];

$current_table = $_GET['table'] ?? '';
$selected_email = $_POST['selected_email'] ?? '';

$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_email'])) {
    $results = fetchFromSupabase($current_table, "email=eq." . urlencode($selected_email));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_entry'])) {
    deleteFromSupabase($current_table, "email=eq." . urlencode($_POST['delete_email']));
    $results = fetchFromSupabase($current_table, "email=eq." . urlencode($_POST['delete_email']));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .card-img-top { object-fit: cover; height: 200px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="section text-center mb-4">
            <h2 class="text-primary">Welcome, <?= htmlspecialchars($admin_name) ?></h2>
            <a href="index.php?page=1" class="btn btn-success m-2">Add Data</a>
            <a href="admin_dashboard.php?mode=delete" class="btn btn-danger m-2">Delete Data</a>
            <a href="admin_dashboard.php?logout=true" class="btn btn-outline-dark m-2">Logout</a>
        </div>

        <!-- Display unique authors -->
        <div class="section mb-4">
            <h4 class="mb-3 text-success">Publications Summary</h4>
            <div class="row">
                <?php foreach ($unique_authors as $author): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <img src="<?= htmlspecialchars($author['photo']) ?>" class="card-img-top" alt="Photo">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($author['name']) ?></h5>
                                <a href="publication_details.php?email=<?= urlencode($author['email']) ?>&name=<?= urlencode($author['name']) ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($_GET['mode'] ?? '' === 'delete'): ?>
        <div class="section">
            <h4>Select Table to Delete From:</h4>
            <ul class="list-group mb-3">
                <?php foreach ($tables as $table): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $table ?>
                    <a href="?mode=delete&table=<?= $table ?>" class="btn btn-outline-primary btn-sm">Select</a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($current_table): ?>
        <div class="section">
            <h4 class="mb-3 text-info">Table: <?= $current_table ?></h4>
            <form method="POST" class="mb-3">
                <input type="hidden" name="table" value="<?= $current_table ?>">
                <div class="input-group mb-3">
                    <input type="email" name="selected_email" value="<?= htmlspecialchars($selected_email) ?>" required placeholder="Enter Email" class="form-control">
                    <button type="submit" name="search_email" class="btn btn-primary">Search</button>
                </div>
            </form>

            <?php if (!empty($results)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <?php foreach (array_keys($results[0]) as $col): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                        <tr>
                            <?php foreach ($row as $col): ?>
                                <td><?= htmlspecialchars($col) ?></td>
                            <?php endforeach; ?>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_email" value="<?= htmlspecialchars($row['email']) ?>">
                                    <input type="hidden" name="table" value="<?= $current_table ?>">
                                    <button type="submit" name="delete_entry" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <p class="text-warning">No matching results found.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
