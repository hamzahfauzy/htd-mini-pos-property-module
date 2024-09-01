<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Edit Kavlingan : <?=$product->name?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data kavlingan</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=properties/view&id=<?=$data->property_id?>" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="">Kode</label>
                                    <input type="text" name="products[code]" class="form-control" value="<?=$product->code?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="products[name]" class="form-control" value="<?=$product->name?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Singkat</label>
                                    <input type="text" name="products[shortname]" class="form-control" value="<?=$product->shortname?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Satuan</label>
                                    <select name="products[unit_id]" class="form-control" id="" required>
                                        <option value="">- Pilih -</option>
                                        <?php foreach($units as $unit): ?>
                                        <option value="<?=$unit->id?>" <?=$unit->id==$product->unit_id?'selected=""':''?>><?=$unit->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="category" class="form-control" id="" required>
                                        <option value="">- Pilih -</option>
                                        <?php foreach($categories as $category): ?>
                                        <option value="<?=$category->id?>" <?=in_array($category->id,$cats)?'selected=""':''?>><?=$category->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Lebar</label>
                                    <input type="text" name="property_items[width]" class="form-control" required value="<?=$data->width?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Panjang</label>
                                    <input type="text" name="property_items[height]" class="form-control" required value="<?=$data->height?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Luas</label>
                                    <input type="text" name="property_items[property_size]" class="form-control" required value="<?=$data->property_size?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="property_items[record_status]" class="form-control" id="" required>
                                        <?php foreach(['available','booked','sold'] as $status): ?>
                                        <option value="<?=$status?>" <?=$status == $data->record_status ? 'selected=""' : ''?>><?=ucwords($status)?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Harga</label>
                                    <input type="number" name="product_prices[base_price]" class="form-control" required value="<?=$product->price->base_price?>">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>