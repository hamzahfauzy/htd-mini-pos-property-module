<div class="overlay-transactions" :class="{show:ringkasan_panel}" @click="ringkasan_panel = !ringkasan_panel"></div>
<div class="fly-transactions" :class="{show:ringkasan_panel}">
    <div class="card" v-if="selectedItem">
        <div class="card-body">
            <div class="transactions" :style="{'height': selectedItem.record_status == 'available' ? 'calc(100vh - 280px)' : 'calc(100vh - 150px)','overflow': 'auto'}">
                <div class="form-group">
                    <label for="">Kode</label>
                    <p>{{selectedItem.code}}</p>
                </div>
                <div class="form-group">
                    <label for="">Spesifikasi</label>
                    <p>
                        Panjang : {{selectedItem.height}}<br>
                        Lebar : {{selectedItem.width}}<br>
                        Luas : {{selectedItem.property_size}}
                    </p>
                </div>
                <div class="form-group">
                    <label for="">Harga</label>
                    <p>Rp. {{numberFormat(selectedItem.base_price)}}</p>
                </div>
                <div class="form-group">
                    <label for="">Jenis Pembelian</label>
                    <select v-model="form.transaction_type" class="form-control">
                        <option value="">Pilih</option>
                        <option>Booking</option>
                        <option>Cash</option>
                        <option>Kredit</option>
                    </select>
                </div>
                <template v-if="form.transaction_type == 'Kredit'">
                <div class="form-group">
                    <label for="">Jumlah Cicilan</label>
                    <input type="number" class="form-control" v-model="form.instalment">
                </div>
                <div class="form-group">
                    <label for="">Nominal Cicilan</label>
                    <input type="number" class="form-control" v-model="form.amount_of_instalment">
                </div>
                </template>
                <div class="form-group" v-if="form.transaction_type && form.transaction_type != 'Cash'">
                    <label for="">Tanggal Jatuh Tempo</label>
                    <input type="date" class="form-control" v-model="form.due_date">
                </div>
                <div class="form-group">
                    <label for="">Pembeli</label>
                    <select class="form-control" v-model="form.customer">
                        <option value="">Pilih</option>
                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{customer.name}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Sales</label>
                    <select class="form-control" v-model="form.sales">
                        <option value="">Pilih</option>
                        <option v-for="sale in sales" :key="sale.id" :value="sale.id">{{sale.name}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control" v-model="form.issue_date">
                </div>
                
            </div>
            <div v-if="selectedItem.record_status == 'available'">
                <div class="form-group">
                    <label for="">Jumlah Pembayaran</label>
                    <input type="number" class="form-control mb-2" placeholder="Nominal Bayar" v-model="form.paytotal">
                    <button id="btn-order" class="btn btn-success btn-block" @click="doSubmit('order')">ORDER</button>
                </div>
            </div>
        </div>
    </div>
</div>