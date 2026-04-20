<?php
require_once '../db.php';
header('Content-Type: application/json');

// Suppress warnings from appearing in JSON output
error_reporting(0);

$search   = isset($_GET['search'])    ? '%' . $conn->real_escape_string($_GET['search']) . '%' : '%';
$filter   = isset($_GET['filter'])    ? $_GET['filter']                                        : 'all';
$dateFrom = isset($_GET['date_from']) ? $conn->real_escape_string($_GET['date_from'])           : null;
$dateTo   = isset($_GET['date_to'])   ? $conn->real_escape_string($_GET['date_to'])             : null;

$sql = "
    SELECT
        b.VendorID,
        v.VendorName,
        v.FDARegistration,
        v.Email,
        v.Phone,
        v.Status AS VendorStatus,
        CONCAT(v.StreetAddress, ', ', v.City, ', ', v.State, ' ', v.ZipCode) AS VendorAddress,
        b.BatchNumber,
        b.ManufactureDate,
        b.ExpiryDate,
        b.TotalVolume,
        b.MinStorageTemp,
        b.MaxStorageTemp
    FROM Batch b
    JOIN Vendor v ON b.VendorID = v.VendorID
    WHERE (
        v.VendorName  LIKE ? OR
        b.BatchNumber LIKE ?
    )
";

// Date range filter
if (!empty($dateFrom) && !empty($dateTo)) {
    $sql .= " AND b.ExpiryDate BETWEEN '$dateFrom' AND '$dateTo'";
} elseif (!empty($dateFrom)) {
    $sql .= " AND b.ExpiryDate >= '$dateFrom'";
} elseif (!empty($dateTo)) {
    $sql .= " AND b.ExpiryDate <= '$dateTo'";
}

// Filter chip conditions
if ($filter === 'expiring') {
    $sql .= " AND b.ExpiryDate BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
} elseif ($filter === 'expired') {
    $sql .= " AND b.ExpiryDate < CURDATE()";
} elseif ($filter === 'frozen') {
    $sql .= " AND b.MaxStorageTemp <= -15";
} elseif ($filter === 'refrigerated') {
    $sql .= " AND b.MinStorageTemp >= 2 AND b.MaxStorageTemp <= 8";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);
$conn->close();
?>