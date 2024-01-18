<?php
ob_start();

function checkSecretQuery() {
    $userSecret = isset($_GET['secret']) ? $_GET['secret'] : '';

    $configSecret = json_decode(file_get_contents('config.json'), true)['secret'];

    if (empty($userSecret)) {
        echo '<div class="message error">Enter secret.</div>';
    } elseif ($userSecret === $configSecret) {
        displayData();
        displayAddForm();
    } else {
        echo '<div class="message error">Incorrect secret.</div>';
    }

}

function formatDataIds($data) {

    foreach ($data as $index => &$row) {
        $row['id'] = $index + 1;
    }

    return $data;
}

$jsonData = file_get_contents('data.json');
$data = json_decode($jsonData, true);

$data = formatDataIds($data);

$jsonDataUpdated = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('data.json', $jsonDataUpdated);

function getCurrentDataCount() {
    $jsonData = file_get_contents('data.json');
    $data = json_decode($jsonData, true);
    return count($data);
}

function addDataToFileWithId($newData) {
    $jsonData = file_get_contents('data.json');
    $data = json_decode($jsonData, true);

    $newData['id'] = getCurrentDataCount() + 1;

    $newData['level'] = is_numeric($newData['level']) ? (int)$newData['level'] : $newData['level'];
    $newData['xp'] = is_numeric($newData['xp']) ? (int)$newData['xp'] : $newData['xp'];
    $newData['coins'] = is_numeric($newData['coins']) ? (int)$newData['coins'] : $newData['coins'];
    $newData['beacons'] = is_numeric($newData['beacons']) ? (int)$newData['beacons'] : $newData['beacons'];

    $data[] = $newData;
    $jsonDataUpdated = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('data.json', $jsonDataUpdated);
}

function displayData() {
    $jsonData = file_get_contents('data.json');
    $data = json_decode($jsonData, true);

    echo '<style>#login-form { display: none; }</style>';

    usort($data, function ($a, $b) {
        $calculatedFieldA = ($a['level'] + $a['xp'] + $a['coins']) / $a['beacons'];
        $calculatedFieldB = ($b['level'] + $b['xp'] + $b['coins']) / $b['beacons'];

        return $calculatedFieldB <=> $calculatedFieldA;
    });

    echo '<h1 class="message success" style="color: green;">Leaderboard!</h1>';
    echo '<h2>Data from Leaderboard:</h2>';
    echo '<table border="1">';
    echo '<tr><th>#</th><th>Name</th><th>Total Req</th><th>Peak Req</th><th>Average Req</th><th>Time</th><th>Conc</th><th>Date</th><th>Action</th></tr>';
    foreach ($data as $index => $row) {

        echo '<tr>';
        echo '<td>' . ($index + 1) . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['level'] . '</td>';
        echo '<td>' . $row['xp'] . '</td>';
        echo '<td>' . $row['coins'] . '</td>';
        echo '<td>' . $row['love'] . '</td>';
        echo '<td>' . $row['beacons'] . '</td>';
        echo '<td>' . $row['resources'] . '</td>';
        echo '<td><button onclick="deleteRow(' . $row['id'] . ')">Delete</button></td>';

        echo '</tr>';
    }
    echo '</table>';
}

function displayAddForm() {
    echo '<h2>Add Data:</h2>';
    echo '<form method="post">';
    echo '<label for="name">Name:</label>';
    echo '<input type="text" id="name" name="name" required placeholder="Buffa"><br>';

    echo '<label for="imageUrl">Image URL (enter "0" to use default image):</label>';
    echo '<input type="text" id="imageUrl" name="imageUrl" required placeholder="https://i.imgur.com/YOUR_IMAGE.jpg"><br>';

    echo '<label for="total_req">Total Req:</label>';
    echo '<input type="text" id="total_req" name="total_req" required placeholder="100000"><br>';

    echo '<label for="peak_req">Peak Req:</label>';
    echo '<input type="text" id="peak_req" name="peak_req" required placeholder="100000"><br>';

    echo '<label for="average_req">Average Req:</label>';
    echo '<input type="text" id="average_req" name="average_req" required placeholder="100000"><br>';

    echo '<label for="time">Time:</label>';
    echo '<input type="text" id="time" name="time" required placeholder="60s"><br>';

    echo '<label for="conc">Conc:</label>';
    echo '<input type="text" id="conc" name="conc" required placeholder="1"><br>';

    echo '<label for="data">Date:</label>';
    echo '<input type="date" id="date" name="date" required placeholder="Select date"><br>';

    echo '<button type="submit" name="addData">Add Data</button>';
    echo '</form>';
}

echo '<script>
    function deleteRow(id) {
        if (confirm("Are you sure you want to delete this row?")) {

            window.location.href = window.location.href + "&delete=" + id;
        }
    }
</script>';

function deleteDataById($id) {
    $jsonData = file_get_contents('data.json');
    $data = json_decode($jsonData, true);

    foreach ($data as $index => $row) {
        if ($row['id'] == $id) {
            unset($data[$index]);
            break; 
        }
    }

    $jsonDataUpdated = json_encode(array_values($data), JSON_PRETTY_PRINT);
    file_put_contents('data.json', $jsonDataUpdated);
}

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    deleteDataById($deleteId);

    echo '<script>window.location.href = window.location.href.replace(/[?&]delete=\d+/, "");</script>';
}

if (isset($_POST['addData'])) {
    $newData = [
        'id' => 0,
        'name' => $_POST['name'],
        'image' => $_POST['imageUrl'],
        'level' => $_POST['total_req'],
        'xp' => $_POST['peak_req'],
        'coins' => $_POST['average_req'],
        'love' => $_POST['time'],
        'beacons' => $_POST['conc'],
        'resources' => date('d/m/Y', strtotime($_POST['date'])),
    ];

    addDataToFileWithId($newData);
    echo '<script>window.location.href = window.location.href;</script>';
}

if (isset($_POST['login'])) {

    $userInput = isset($_POST['query']) ? $_POST['query'] : '';
    if (!empty($userInput)) {
        header("Location: ?secret=$userInput");
        exit();
    } else {
        echo '<div class="message error">Enter secret.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <title>Leaderboard Panel</title>
    <style>
        body {
            font-family: "Trebuchet MS", sans-serif;
            margin: 20px;
            background-color: #181724;
            color: white;
        }

        #login-form {
            margin-bottom: 20px;
            background-color: #181724;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            background-color: #181724;
            font-family: "Trebuchet MS", sans-serif;
            color: white;
            border: 2px solid gray;
            font-size: 19px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .message.error {
            background-color: #ffdddd;
            color: #ff0000;
        }

        .message.success {
            background-color: #ddffdd;
            color: #00aa00;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 2px solid gray;
        }

        th {
            background-color: #4caf50;
            color: white;
        }
    </style>
</head>
<body>
    <form method="post" id="login-form">
        <label for="query">Enter Secret:</label>
        <input type="text" id="query" name="query" required>
        <button type="submit" name="login">Login</button>
    </form>

    <?php

    checkSecretQuery();
    ?>
</body>
</html>