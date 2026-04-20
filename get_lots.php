<?php
require_once '../db.php';
header('Content-Type: application/json');

$vendorID = $conn->real_escape_string($_GET['vendor'] ?? '');
$batchNum = $conn->real_escape_string($_GET['batch']  ?? '');

if (!$vendorID || !$batchNum) {
    echo json_encode(["error" => "Missing parameters"]);
    exit;
}

$sql = "
    SELECT
        bl.LotSeq,
        bl.LotVolume,
        bl.CreatedTime,
        -- current zone (EndTime is NULL = still stored there)
        si.WarehouseID   AS ZoneWarehouse,
        si.ZoneCode      AS ZoneCode,
        -- current vehicle via latest custody event to a vehicle
        lce.ToVehicleID  AS VehicleID,
        lce.ToClinicID   AS ClinicID,
        c.ClinicName     AS ClinicName,
        lce.ToLocation   AS CurrentLocationType
    FROM BatchLot bl
    -- most recent custody event for this lot
    LEFT JOIN LotCustodyEvent lce ON (
        lce.VendorID    = bl.VendorID AND
        lce.BatchNumber = bl.BatchNumber AND
        lce.LotSeq      = bl.LotSeq AND
        lce.CustodyEventID = (
            SELECT MAX(lce2.CustodyEventID)
            FROM LotCustodyEvent lce2
            WHERE lce2.VendorID = bl.VendorID
              AND lce2.BatchNumber = bl.BatchNumber
              AND lce2.LotSeq = bl.LotSeq
        )
    )
    LEFT JOIN StoredIn si ON (
        si.VendorID    = bl.VendorID AND
        si.BatchNumber = bl.BatchNumber AND
        si.LotSeq      = bl.LotSeq AND
        si.EndTime IS NULL
    )
    LEFT JOIN Clinic c ON c.ClinicID = lce.ToClinicID
    WHERE bl.VendorID = ? AND bl.BatchNumber = ?
    ORDER BY bl.LotSeq
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $vendorID, $batchNum);
$stmt->execute();
$result = $stmt->get_result();

$lots = [];
while ($row = $result->fetch_assoc()) {
    $lots[] = $row;
}

echo json_encode($lots);
$conn->close();
?>