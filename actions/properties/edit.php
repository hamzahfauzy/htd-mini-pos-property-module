<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('properties',[
    'id' => $_GET['id']
]);

if(request() == 'POST')
{
    if(!empty($_FILES['file']))
    {
        $pic  = $_FILES['file'];
        $ext  = pathinfo($pic['name'], PATHINFO_EXTENSION);
        $name = strtotime('now').'.'.$ext;
        $file = 'uploads/properties/'.$name;
        copy($pic['tmp_name'],$file);
        $_POST['properties']['map_image'] = $file;
    }
    else
        $_POST['properties']['map_image'] = $data->map_image;

    $_POST['properties']['updated_by'] = auth()->user->id;
    $_POST['properties']['updated_at'] = date('Y-m-d H:i:s');
    $db->update('properties',$_POST['properties'],[
        'id' => $_GET['id']
    ]);

    set_flash_msg(['success'=>'Kavlingan berhasil diupdate']);
    header('location:index.php?r=properties/view&id='.$_GET['id']);
}

return compact('data');