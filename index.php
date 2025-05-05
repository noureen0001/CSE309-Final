<?php
// --- Supabase Settings ---
$supabase_url = getenv('supabase_url');
$supabase_key = getenv('supabase_key');

function sendToSupabase($table, $data) {
    global $supabase_url, $supabase_key;

    $ch = curl_init("$supabase_url/rest/v1/$table");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// --- Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table'])) {
    $table = $_POST['table'];
    $entries = [];
    $keys = array_keys($_POST);
    $num_rows = max(array_map(function($key) { return count((array)$_POST[$key]); }, $keys));

    for ($i = 0; $i < $num_rows; $i++) {
        $entry = [];
        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                $entry[$key] = $value[$i] ?? null;
            } else {
                $entry[$key] = $value;
            }
        }
        unset($entry['table']);
        unset($entry['submit']);
        unset($entry['next']);
        $entries[] = $entry;
    }

    $response = sendToSupabase($table, $entries);
    echo "<script>alert('Data submitted for table $table');</script>";
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$tables = [
    'profile_details_t' => ['email','name','designation1','designation2','current_working','education1','education2','education3','education4','interests','responsiblities'],
    'awards_t' => ['email','name','description','year'],
    'teaching_t' => ['email','name','course_description','year'],
    'publications_t' => ['email','title','description','link'],
    'conferences_t' => ['email','name','title','description','year'],
    'supervisor_t' => ['email','name','description'],
    'research_interests_t' => ['email','name','interest'],
    'postgraduate_courses_t' => ['email','name','description','date'],
    'homepage_t' => ['email','name','homepage_link'],
    'research_projects_t' => ['email','name','description'],
    'activities_t' => ['email','name','activities'],
    'advance_training_t' => ['email','name','training','year'],
    'interview_t' => ['email','name','description']
];
$table_keys = array_keys($tables);
$current_table = $table_keys[$page - 1] ?? $table_keys[0];
$fields = $tables[$current_table];
$is_repeatable = !in_array($current_table, ['profile_details_t', 'homepage_t']);
$next_page = $page < count($tables) ? $page + 1 : 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Form <?= $current_table ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; padding: 20px; }
    .form-section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    .form-row { margin-bottom: 15px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-section">
      <h3 class="text-primary text-center mb-4"><?= strtoupper($current_table) ?> Form</h3>
      <form method="POST">
        <input type="hidden" name="table" value="<?= $current_table ?>">
        <div id="form-rows">
          <div class="form-group row">
            <?php foreach ($fields as $field): ?>
              <div class="form-row col-md-6">
                <label><?= ucfirst(str_replace('_',' ',$field)) ?>:</label>
                <input type="text" name="<?= $field ?>[]" class="form-control" required>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php if ($is_repeatable): ?>
          <button type="button" class="btn btn-warning mt-3" onclick="addRow()">Add Extra Row</button>
        <?php endif; ?>
        <div class="mt-4">
          <button type="submit" name="submit" class="btn btn-success">Submit</button>
          <a href="?page=<?= $next_page ?>" class="btn btn-primary">Next Page</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    function addRow() {
      const formRows = document.getElementById('form-rows');
      const firstRow = formRows.firstElementChild.cloneNode(true);
      [...firstRow.querySelectorAll('input')].forEach(input => input.value = '');
      formRows.appendChild(firstRow);
    }
  </script>
</body>
</html>
