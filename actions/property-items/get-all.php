<?php

$conn = conn();
$db   = new Database($conn);
$propertyId = $_GET['property_id'];

$db->query = "SELECT 
    products.*,
    product_prices.base_price,
    property_items.width,
    property_items.height,
    property_items.property_size,
    property_items.record_status
FROM products 
LEFT JOIN property_items ON property_items.product_id = products.id 
LEFT JOIN product_prices ON product_prices.product_id = products.id 
WHERE property_items.property_id = $propertyId";
$data = $db->exec('all');

echo json_encode([
    'message' => 'Data berhasil diambil',
    'data'    => $data,
    'status'  => 'success'
]);
die();