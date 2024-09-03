<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$data = $db->single('properties',[
    'id' => $_GET['id']
]);

$db->query = "SELECT 
    property_items.*, 
    products.code, 
    product_prices.base_price 
FROM property_items 
LEFT JOIN products ON products.id = property_items.product_id 
LEFT JOIN product_prices ON product_prices.product_id = products.id
WHERE property_items.property_id = $_GET[id]";
$data->items = $db->exec('all');

$sold = [];
$available = [];
$booked = [];
$kredit = [];
$all_code = [];
foreach($data->items as $item)
{
    $all_code[] = $item->code;
    if($item->record_status == 'sold')
    {
        $sold[] = $item->code;
    }
    
    if($item->record_status == 'available')
    {
        $available[] = $item->code;
    }
    
    if($item->record_status == 'booked')
    {
        $booked[] = $item->code;
    }
    
    if($item->record_status == 'kredit')
    {
        $kredit[] = $item->code;
    }
}

return compact('data','success_msg', 'sold', 'available', 'booked', 'kredit', 'all_code');