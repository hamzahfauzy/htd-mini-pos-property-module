<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$db->query = "SELECT properties.*, (SELECT COUNT(id) FROM property_items WHERE property_id = properties.id) as num_of_items FROM properties";
$data = $db->exec('all');

echo json_encode([
    'message' => 'Data berhasil diambil',
    'data'    => $data,
    'status'  => 'success'
]);
die();