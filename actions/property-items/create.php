<?php

$conn = conn();
$db   = new Database($conn);

$property = $db->single('properties', ['id' => $_GET['id']]);

if(request() == 'POST')
{
    $_POST['products']['created_by'] = auth()->user->id;
    $_POST['products']['default_stock'] = 'stock';
    $product = $db->insert('products',$_POST['products']);
    $db->insert('product_categories',[
        'product_id' => $product->id,
        'category_id' => $_POST['category']
    ]);

    $_POST['property_items']['product_id'] = $product->id;
    $_POST['property_items']['property_id'] = $property->id;
    $db->insert('property_items',$_POST['property_items']);

    $_POST['product_prices']['product_id'] = $product->id;
    $_POST['product_prices']['member_price'] = 0;
    $_POST['product_prices']['discount_price'] = 0;
    $db->insert('product_prices', $_POST['product_prices']);

    $db->insert('product_stocks',[
        'product_id' => $product->id,
        'qty' => 1
    ]);


    set_flash_msg(['success'=>'Produk berhasil ditambahkan']);
    header('location:index.php?r=properties/view&id='.$property->id);
}

$units = $db->all('units');
$categories = $db->all('categories');

return compact('property','units','categories');