<?php
$data = [
    'name' => 'Nahian Noureen',
    'id' => '2320608',
    'personal_notion_page' => 'https://www.notion.so/yourpage',
    'personal_group_page_notion' => 'https://www.notion.so/Lab-Inside-University-1b455c93dfd980ff89cce2ef14ee72b0?pvs=4',
    'github_id' => 'noureen0001',
    'project_github_link' => 'https://github.com/noureen0001/CSE309-Final.git'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Info Table</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 700px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
        }

        th {
            background-color: #6c5ce7;
            color: white;
            font-size: 18px;
        }

        tr:nth-child(even) {
            background-color: #f1f2f6;
        }

        tr:hover {
            background-color: #dfe6e9;
        }

        a {
            color: #0984e3;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        caption {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2d3436;
        }
    </style>
</head>
<body>

    <table>
        <caption>Team Member Profile</caption>
        <tr>
            <th>Name</th>
            <td><?= htmlspecialchars($data['name']) ?></td>
        </tr>
        <tr>
            <th>ID</th>
            <td><?= htmlspecialchars($data['id']) ?></td>
        </tr>
        <tr>
            <th>Personal Notion Page</th>
            <td><a href="<?= $data['personal_notion_page'] ?>" target="_blank">View Notion <br> <?= $data['personal_notion_page'] ?></a></td>
        </tr>
        <tr>
            <th>Group Notion Page</th>
            <td><a href="<?= $data['personal_group_page_notion'] ?>" target="_blank">View Group Page <br> <?= $data['personal_group_page_notion'] ?></a></td>
        </tr>
        <tr>
            <th>GitHub ID</th>
            <td><a href="https://github.com/<?= $data['github_id'] ?>" target="_blank"><?= $data['github_id'] ?></a></td>
        </tr>
        <tr>
            <th>Project Repository</th>
            <td><a href="<?= $data['project_github_link'] ?>" target="_blank"><?= $data['project_github_link'] ?></a></td>
        </tr>
    </table>

</body>
</html>
