<?php load_templates('layouts/top') ?>
    <link rel="stylesheet" href="css/mode2.css">
    <style>
    .image_map {
        width: 650px;
    }
    .image_map svg {
        width: 100%;
        height: auto;
    }
    .cursor-pointer {
        cursor:pointer;
    }
    .sold {
        fill: <?=config('status_color')['sold']?> !important;
    }
    .available {
        fill: <?=config('status_color')['available']?> !important;
    }
    .booked {
        fill: <?=config('status_color')['booked']?> !important;
    }
    .kredit {
        fill: <?=config('status_color')['kredit']?> !important;
    }
    .form-group p {
        margin-bottom: 0px !important;
    }
    </style>
    <div class="content py-5">
        <div id="app" style="display:none">
            <div class="page-inner">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h2>Kasir</h2>
                                <select name="property_id" id="" class="form-control" @change="setSelectedProperty($event)">
                                    <option value="">Pilih Kavlingan</option>
                                    <option v-for="property in properties" :key="property.id" :value="property.id">{{property.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="card" v-if="selectedProperty">
                            <div class="card-body">
                                <div class="image_map m-auto" v-html="selectedProperty.svg"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php require 'property-sidebar.php' ?>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script>
    window.pos_sess_id = '<?=$pos_sess_id?>';
    window.transaction_id = '<?=$transaction_id?>';
    window.app = <?=json_encode(app())?>;
    </script>
    <script src="js/pos.js"></script>
<?php load_templates('layouts/bottom') ?>