<?php
header('Content-Type: application/json');

// 2. Wrap the REQUIRE in a check so it doesn't fatally crash if missing
if (!file_exists('db_connect.php')) {
    echo json_encode(["error" => "CRITICAL: db_connect.php file is missing!"]);
    exit;
}

require 'db_connect.php';

try {
    $sql = "SELECT s.ShipmentID, s.Status,  s.OriginWarehouseID, CONCAT_WS('', s.DestinationClinicID, s.DestinationWarehouseID) AS Destination, s.DepartureTime, s.VehicleID, COUNT(sl.shipmentID) AS BatchCount, NULL FROM Shipment s LEFT JOIN ShipmentLot sl on s.ShipmentID = sl.ShipmentID GROUP BY s.ShipmentID, s.Status,  s.OriginWarehouseID, s.DestinationClinicID, s.DestinationWarehouseID, s.DepartureTime, s.VehicleID;";
    $stmt = $pdo->query($sql);
    
    $shipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($shipments);

} catch (Exception $e) {
    // Send the exact database error back to the browser
    echo json_encode(["error" => $e->getMessage()]);
}
?>

