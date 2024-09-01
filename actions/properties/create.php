<?php

$conn = conn();
$db   = new Database($conn);

if(request() == 'POST')
{
    if(isset($_FILES['file']) && !empty($_FILES['file']['name']))
    {
        $pic  = $_FILES['file'];
        $ext  = pathinfo($pic['name'], PATHINFO_EXTENSION);
        $name = strtotime('now').'.'.$ext;
        $file = 'uploads/properties/'.$name;
        copy($pic['tmp_name'],$file);
        $_POST['properties']['map_image'] = $file;
    }

    $_POST['properties']['created_by'] = auth()->user->id;
    $property = $db->insert('properties',$_POST['properties']);
    set_flash_msg(['success'=>'Kavlingan berhasil ditambahkan']);
    header('location:index.php?r=properties/view&id='.$property->id);
}

return [];