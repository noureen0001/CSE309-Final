<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marks Table</title>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px;
        }

        h2 {
            color: #2d3436;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 800px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
        }

        th {
            background-color: #6c5ce7;
            color: white;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f1f2f6;
        }

        tr:hover {
            background-color: #dfe6e9;
            cursor: pointer;
        }

        td {
            color: #2d3436;
            font-size: 15px;
        }

        td:last-child {
            color: #0984e3;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2 style="margin-right: 5%;">Marks Table</h2>
    <div id="table"></div>

    <script>
        const data = [
            {mark: 100, justification: "Excellent work", route: "/user_dashboard.php"},
            {mark: 100, justification: "Well done", route: "/publication_details.php"},
            {mark: 100, justification: "Well done", route: "/admin_dashboard.php"},
            {mark: 100, justification: "Excellent work", route: "/info.php"}
        ];

        const table = d3.select("#table").append("table");
        const header = table.append("thead").append("tr");

        header.selectAll("th")
            .data(["Mark", "Justification for this marking", "Internal Route"])
            .enter().append("th")
            .text(d => d);

        const rows = table.append("tbody")
            .selectAll("tr")
            .data(data)
            .enter().append("tr");

        rows.selectAll("td")
            .data(d => [d.mark, d.justification, d.route])
            .enter().append("td")
            .text(d => d);
    </script>
</body>
</html>
