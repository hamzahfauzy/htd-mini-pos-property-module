<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$db->query = "SELECT properties.*, COUNT(property_items.id) as num_of_items FROM properties JOIN property_items ON property_items.property_id = properties.id";
$datas = $db->exec('all');

return compact('datas','success_msg');