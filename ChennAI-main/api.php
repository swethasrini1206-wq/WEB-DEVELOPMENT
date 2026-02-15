<?php
// api.php
header('Content-Type: application/json; charset=utf-8');
require_once 'config1.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'get_place') {
    $place = isset($_GET['place']) ? $_GET['place'] : '';
    $stmt = $conn->prepare("SELECT * FROM places WHERE name LIKE CONCAT('%',?,'%') LIMIT 1");
    $stmt->bind_param('s', $place);
    $stmt->execute();
    $res = $stmt->get_result();
    $placeRow = $res->fetch_assoc();
    echo json_encode($placeRow ?: (object)[]);
    exit;
}

if ($action === 'get_restaurants') {
    $place_id = isset($_GET['place_id']) ? (int)$_GET['place_id'] : 0;
    $cuisine = isset($_GET['cuisine']) ? $_GET['cuisine'] : '';
    $budget = isset($_GET['budget']) ? $_GET['budget'] : '';

    $sql = "SELECT * FROM restaurants WHERE place_id = ?";
    $params = [$place_id];
    $types = "i";

    if ($cuisine !== '') {
        $sql .= " AND cuisine LIKE CONCAT('%',?,'%')";
        $types .= "s";
        $params[] = $cuisine;
    }
    if ($budget !== '') {
        $sql .= " AND budget = ?";
        $types .= "s";
        $params[] = $budget;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
    exit;
}

if ($action === 'get_hotels') {
    $place_id = isset($_GET['place_id']) ? (int)$_GET['place_id'] : 0;
    $stay_type = isset($_GET['stay_type']) ? $_GET['stay_type'] : '';
    $budget = isset($_GET['budget']) ? $_GET['budget'] : '';

    $sql = "SELECT * FROM hotels WHERE place_id = ?";
    $params = [$place_id];
    $types = "i";

    if ($stay_type !== '') {
        $sql .= " AND stay_type LIKE CONCAT('%',?,'%')";
        $types .= "s";
        $params[] = $stay_type;
    }
    if ($budget !== '') {
        $sql .= " AND budget = ?";
        $types .= "s";
        $params[] = $budget;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
    exit;
}

if ($action === 'get_nearby_best') {
    $place_id = isset($_GET['place_id']) ? (int)$_GET['place_id'] : 0;
    $rstmt = $conn->prepare("SELECT * FROM restaurants WHERE place_id = ? LIMIT 5");
    $rstmt->bind_param('i', $place_id);
    $rstmt->execute();
    $restaurants = $rstmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $hstmt = $conn->prepare("SELECT * FROM hotels WHERE place_id = ? LIMIT 5");
    $hstmt->bind_param('i', $place_id);
    $hstmt->execute();
    $hotels = $hstmt->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['restaurants'=>$restaurants, 'hotels'=>$hotels]);
    exit;
}

echo json_encode(['error' => 'Invalid action']);
exit;
