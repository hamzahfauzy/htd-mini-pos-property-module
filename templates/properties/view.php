<?php load_templates('layouts/top') ?>
    <style>
    .image_map {
        width: 350px;
    }
    .image_map svg {
        width: 100%;
        height: auto;
    }
    #<?=implode(',#',$all_code)?> {
        cursor:pointer;
    }
    #<?=implode(',#',$sold)?> {
        fill: <?=config('status_color')['sold']?>;
    }
    #<?=implode(',#',$available)?> {
        fill: <?=config('status_color')['available']?>;
    }
    #<?=implode(',#',$booked)?> {
        fill: <?=config('status_color')['booked']?>;
    }
    #<?=implode(',#',$kredit)?> {
        fill: <?=config('status_color')['kredit']?>;
    }
    </style>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Properti : <?=$data->name?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data properti</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=properties/edit&id=<?=$data->id?>" class="btn btn-secondary btn-round">Edit</a>
                        <a href="index.php?r=properties/index" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-center"><?=$data->name?></h3>
                            <p><?=nl2br($data->address)?></p>
                            <div class="image_map m-auto">
                                <?= file_get_contents($data->map_image) ?>
                            </div>
                            <?php if($success_msg): ?>
                                <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>

                            <a href="index.php?r=property-items/create&id=<?=$data->id?>" class="btn btn-primary">Buat Item</a>
                            <div class="table-responsive table-hover table-sales mt-3">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Kode</th>
                                            <th>Panjang</th>
                                            <th>Lebar</th>
                                            <th>Luas</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th class="text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($data->items)): ?>
                                        <tr>
                                            <td colspan="8" style="text-align:center"><i>Tidak ada data</i></td>
                                        </tr>
                                        <?php endif ?>
                                        <?php foreach($data->items as $index => $item): ?>
                                        <tr>
                                            <td>
                                                <?=$index+1?>
                                            </td>
                                            <td><?=$item->code?></td>
                                            <td><?=number_format($item->height)?></td>
                                            <td><?=number_format($item->width)?></td>
                                            <td><?=$item->property_size?></td>
                                            <td><?=number_format($item->base_price)?></td>
                                            <td>
                                                <span class="badge" style="background-color: <?=config('status_color')[$item->record_status]?>;<?=$item->record_status == 'sold' ? 'color:#FFF;' : ''?>"><?=ucwords($item->record_status)?></span>
                                            </td>
                                            <td>
                                                <a href="index.php?r=property-items/edit&id=<?=$item->id?>" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>
                                                <a href="index.php?r=property-items/delete&id=<?=$item->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        <?php /* foreach($all_code as $code): ?>
        document.querySelector('#<?=$code?>').onclick = function(){
            alert('<?=$code?>')
        }
        <?php endforeach; */ ?>
    </script>
<?php load_templates('layouts/bottom') ?>