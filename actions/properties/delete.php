<?php

$conn = conn();
$db   = new Database($conn);

$db->delete('properties',[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Kavlingan berhasil dihapus']);
header('location:index.php?r=properties/index');
die();