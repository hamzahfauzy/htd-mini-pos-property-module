<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('property_items',[
    'id' => $_GET['id']
]);

$product = $db->single('products', [
    'id' => $data->product_id
]);

$product->price = $db->single('product_prices',[
    'product_id' => $data->product_id
]);

if(request() == 'POST')
{
    $_POST['products']['updated_by'] = auth()->user->id;
    $_POST['products']['updated_at'] = date('Y-m-d H:i:s');
    $db->update('products',$_POST['products'],[
        'id' => $_GET['id']
    ]);

    $db->update('product_categories',[
        'category_id' => $_POST['category']
    ],[
        'product_id' => $_GET['id'],
    ]);

    $db->update('property_items',$_POST['property_items'],[
        'id' => $data->id
    ]);

    $db->update('product_prices', $_POST['product_prices'], [
        'id' => $product->price->id
    ]);

    set_flash_msg(['success'=>'Kavlingan berhasil diupdate']);
    header('location:index.php?r=properties/view&id='.$data->property_id);
}

$units = $db->all('units');
$categories = $db->all('categories');
$product_categories = $db->all('product_categories',[
    'product_id' => $product->id
]);
$cats = [];
foreach($product_categories as $category)
    $cats[] = $category->category_id;

return compact('data','product','units','categories','cats');