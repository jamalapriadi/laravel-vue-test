<template>
    <div class="row">
        <div class="col-lg-3">
            <div class="card card-accent-primary">
                <div class="card-header">
                    Retur
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="" class="control-label">No. Retur</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">Tanggal</label>
                        <date-picker v-model="state.tanggal" :config="options"></date-picker>
                    </div>

                    <br>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card card-default">
                <div class="card-header">Info Retur</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Full Nota?</label>
                                <div class="col-lg-9">
                                    <toggle-button :value="true" :labels="{checked: 'Yes', unchecked: 'No'}" v-model="state.full_nota" @change="ubahPoPending(state.full_nota)"/>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Customer</label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <vue-bootstrap-typeahead v-model="caritokocustomer" :data="listtokocustomer" placeholder="Nama Toko" @hit="getTokoCustomer($event)" ref="tokocustomer"/>
                                        </div>

                                        <div class="col-lg-7">
                                            <vue-bootstrap-typeahead v-model="carinamacustomer" :data="listcaricustomer" placeholder="Cari Customer..." @hit="getNamaCustomer($event)" ref="namacustomer"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row" v-show="state.tampil_order==true">
                                <label for="" class="control-label col-lg-3">Nomor Order</label>
                                <div class="col-lg-9">
                                    <select name="po" id="po" class="form-control" v-model="state.no_po" @change="changePo">
                                        <option value="" disabled selected>--Pilih PO--</option>
                                        <option v-for="(l,index) in pos" v-bind:key="index" v-bind:value="l.no_po">{{l.no_po}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Kode Transaksi</label>
                                <div class="col-lg-9">
                                    <select name="kd_trans" id="kd_trans" class="form-control" v-model="state.kd_trans">
                                        <option v-for="(l,index) in kodetrans" v-bind:value="l" v-bind:key="index">{{l}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Lokasi</label>
                                <div class="col-lg-9">
                                    <select name="lokasi" id="lokasi" class="form-control" v-model="state.lokasi" @change="changeLokasi">
                                        <option value="" disabled selected>--Pilih Lokasi--</option>
                                        <option v-for="(l, index) in lokasis" v-bind:key="index"  v-bind:value="l.id">{{l.id}} - [{{l.nm}}]</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header">Detail Barang</div>
                <div class="card-body">
                    
                    <div class="row" v-show="state.tampil_order==false">
                        <div class="form-group col-md-4">
                            <label for="" class="control-label">Kode / Nama Barang</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <vue-bootstrap-typeahead v-model="carikodebarang" :data="listkodebarang" placeholder="Cari Kode Barang..." @hit="getKodeBarang($event)" ref="kodebarang"/>
                                </div>
                                <div class="col-lg-6">
                                    <vue-bootstrap-typeahead v-model="carinamabarang" :data="listcaribarang" placeholder="Cari Barang..." @hit="getNamaBarang($event)" ref="namabarang"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="" class="control-label">Dos</label>
                            <input type="text" class="form-control" v-model="barang.dos" @keyup.enter="changeDos" @input="hitungTotal($event)">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="" class="control-label">PCS</label>
                            <input type="text" class="form-control" v-model="barang.pcs" @keyup.enter="changePcs(barang.pcs)" @input="hitungTotalPcs($event)">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="" class="control-label">Total Pcs</label>
                            <input type="text" class="form-control" v-model="barang.total_pcs" readonly>
                        </div>


                        <div class="form-group col-md-1">
                            <div class="btn-group">
                                <button v-on:click="saveBarang()" class="btn btn-primary" style="margin-top:25px;">
                                    <i class="fa fa-plus"></i>
                                    Add
                                </button>

                                <a @click="showModal" class="btn btn-info text-white" style="margin-top:25px;">
                                    <i class="fa fa-list"></i> List Barang
                                </a>
                            </div>
                        </div>
                    </div>


                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode / Nama Barang</th>
                                <th>Dos / PCS  PO</th>
                                <th>Rak</th>
                                <th>Dos</th>
                                <th>PCS</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(l,index) in barang" v-bind:key="index">
                                <!-- <input type="hidden" v-model="state.kodes[index]" :value="l.kd">
                                <input type="hidden" v-model="state.pdoss[index]" :value="l.pivot.dos">
                                <input type="hidden" v-model="state.ppcss[index]" :value="l.pivot.pcs"> -->
                                <td>{{ index + 1 }}</td>
                                <td>{{l.kd}} / {{l.nm}}</td>
                                <td>{{l.dos}} Dos / {{l.pcs}} Pcs</td>
                                <td>
                                    <select name="rak" id="rak" class="form-control" v-model="state.rak[index]" v-on:change="changeRak(index,state.rak[index], l.kd, state.lokasi,l.pivot.pcs)">
                                        <option value="" disabled selected>--Pilih Rak--</option>
                                        <option v-for="k in raks" v-bind:key="k.kd" v-bind:value="k.kd">{{k.kd}} [ {{k.nm}} ]</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" id="dos" placeholder="Dos" v-model.number="state.dos[index]" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="pcs" placeholder="pcs" v-model="state.pcs[index]" readonly>
                                    <small>
                                        {{ stok[index] }}
                                    </small>
                                </td>
                                <td>
                                    <!-- <div v-if="tampilTambah[index]==true">
                                        <a class="btn btn-info text-white" v-on:click="tambahBarang(l)">Pilih Dari Rak Lain</a>
                                    </div> -->
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="alert alert-info" v-show="state.kurang.length>0">
                        <ol>
                            <li v-for="(l,index) in state.kurang" v-bind:key="index">Barang <strong>{{l.nm}}</strong> masih kurang <strong>{{l.kurangnya}}</strong> Pcs</li>
                        </ol>
                    </div>

                    <hr>
            
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div class="form-group">
                        <router-link to="/list-picking" class="btn btn-default">
                            <i class="fa fa-backward"></i> Back
                        </router-link>

                        <button class="btn btn-primary" v-on:click="saveProgram">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
import { VueLoading } from 'vue-loading-template'
// Import required dependencies 
import 'bootstrap/dist/css/bootstrap.css';

// Import this component
import datePicker from 'vue-bootstrap-datetimepicker';

// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

import Multiselect from 'vue-multiselect'
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
import uniq from 'lodash/uniq'

export default {
    components: {
        VueLoading,
        datePicker,
        Multiselect,
        VueBootstrapTypeahead
    },
    data() {
        return {
            state: {
                full_nota:'',
                tampil_order:true,
                kode:'',
                no_po:'',
                customer:'',
                tanggal:new Date(),
                tanggaljt:new Date(),
                perusahaan:'',
                keterangan:'',
                lokasi:'',
                sales:'',
                kd_trans:'Tunai',
                listBarang:[{rak:[]}],
                rak:[],
                kodes:[],
                pdos:[],
                ppcs:[],
                rak:[],
                dos:[],
                pcs:[],
                idstok:[],
                kurang:[],
                status_kurang:'Y',
                tampil:[],
            },
            date: new Date(),
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            }, 
            message:'',
            loading:false,
            errors: [],
            value: null,
            tanggal: ['list', 'of', 'options'],
            kodetrans: ['Tunai','Kredit'],
            selectedBarangs: '',
            barangs: [],
            barang:[],
            listBarang:[],
            list:[],
            listData:{},
            pencarian:'',
            isLoading: false,
            customers:[],
            perusahaans:[],
            saless:[],
            kodes:[],
            lokasis:[],
            pos:[],
            raks:[],
            stok:[],
            tampilTambah:[],
            pcs:[],
            dos:[],
            hasilpcs:[],
            carinamacustomer:'',
            caritokocustomer:'',
            listcaricustomer:[],
            listtokocustomer:[],
            carinamabarang:'',
            carikodebarang:'',
            listkodebarang:[],
            listcaribarang:[],
            
            
        }
    },
    watch: {
        pencarian: function(q) {
            if (q != ''){
                this.cariData();
            }else{
                this.showData();
            }
        },

        carinamacustomer: _.debounce(function(q){
            this.cariNamaCustomerByName(q);
        },500),

        caritokocustomer: _.debounce(function(q){
            this.cariTokoCustomerById(q);
        },500),

        carikodebarang: _.debounce(function(q){
            this.cariBarangByKode(q);
        }, 500),

        carinamabarang: _.debounce(function(addr){
            this.cariBarangByNama(addr);
        }, 500),
    },
    mounted() {
        this.getCode();
        // this.getCustomer();
        // this.getPerusahaan();
        // this.getNoPo("true");
        // this.getSales();
        this.getLokasi();
    },
    methods: {
        getNoPo(status){
            axios.get('/data/list-po-not-in-picking?status='+status+"&customer="+this.state.customer)
                .then(response => {
                    this.pos= response.data
                })
        },
        getCode(){
            axios.get('/data/autonumber-retur')
                .then(response => {
                    this.state.kode = response.data;
                })
        },

        async cariNamaCustomerByName(q){
            this.listcaricustomer=[];
            this.listCCustomer=[];
            axios.get('/data/cari-customer-by-nama?q='+q)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listcaricustomer.push(response.data[i].nm);

                        this.listCCustomer.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm,
                                nm_toko:response.data[i].nm_toko
                            }
                        );
                    }
                })
        },

        async cariTokoCustomerById(q){
            this.listtokocustomer=[];
            this.listCCustomer=[];
            axios.get('/data/cari-customer-by-nama?q='+q)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listtokocustomer.push(response.data[i].nm_toko);

                        this.listCCustomer.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm,
                                nm_toko:response.data[i].nm_toko
                            }
                        );
                    }
                })
        },

        async cariBarangByNama(query){
            this.listcaribarang=[];
            this.listCBarang=[];
            let result=[];
            axios.get('/data/cari-barang-by-nama?q='+query)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listcaribarang.push(response.data[i].nm);

                        this.listCBarang.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm,
                                pcs:response.data[i].pcs
                            }
                        );
                    }
                })
        },

        async cariBarangByKode(q){
            this.listkodebarang=[];
            this.listCBarang=[];
            axios.get('/data/cari-barang-by-nama?q='+q)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listkodebarang.push(response.data[i].kd);

                        this.listCBarang.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm,
                                pcs:response.data[i].pcs
                            }
                        );
                    }
                })
        },

        getNamaCustomer(item){
            let uniqueCus = [...new Set(this.listCCustomer)]; 
            var nama="";
            var toko="";
            
            for(var i=0; i< uniqueCus.length; i++){
                if(uniqueCus[i].nm == item){
                    nama=uniqueCus[i].kd;
                    toko=uniqueCus[i].nm_toko;
                }
            };

            this.$refs.tokocustomer.inputValue = toko
            this.state.customer=nama;
            this.getNoPo(this.state.po_pending);
        },

        getTokoCustomer(item){
            let uniqueCus = [...new Set(this.listCCustomer)]; 
            var nama="";
            var kode="";
            
            for(var i=0; i< uniqueCus.length; i++){
                if(uniqueCus[i].nm_toko == item){
                    nama=uniqueCus[i].nm;
                    kode=uniqueCus[i].kd;
                }
            };
            
            this.$refs.namacustomer.inputValue = nama
            this.state.customer=kode;
            this.getNoPo(this.state.po_pending);
        },

        getNamaBarang(item){
            let unique = [...new Set(this.listCBarang)]; 
            var nama="";
            var pcs=0;
            
            for(var i=0; i< unique.length; i++){
                if(unique[i].nm == item){
                    nama=unique[i].kd;
                    pcs=unique[i].pcs;
                }
            }

            this.barang.kode=nama;
            this.barang.nama=item;
            this.barang.pcs=pcs;
            this.barang.dos=0;
            this.barang.pcs=0;
            this.barang.total_pcs=0;
            this.hasilpcs=pcs;
            // this.carinamabarang=nama;
            this.$refs.kodebarang.inputValue = nama
        },

        getKodeBarang(item){
            let unique = [...new Set(this.listCBarang)]; 
            var nama="";
            var pcs=0;
            
            for(var i=0; i< unique.length; i++){
                if(unique[i].kd == item){
                    nama=unique[i].nm;
                    pcs=unique[i].pcs;
                }
            }

            this.barang.kode=item;
            this.barang.nama=nama;
            this.barang.pcs=pcs;
            this.barang.dos=0;
            this.barang.pcs=0;
            this.barang.total_pcs=0;
            this.hasilpcs=pcs;
            this.$refs.namabarang.inputValue = nama;
        },

        ubahPoPending(ppending){
            this.state.tampil_order=ppending;
        },

        changePo(){
            axios.get('/data/po-by-id/'+this.state.no_po)
                .then(response => {
                    this.state.kodes=[];
                    this.state.pdos=[];
                    this.state.ppc=[];
                    this.state.idstok=[];
                    this.state.customer=response.data.po.customer.nm;
                    this.state.lokasi=response.data.po.lokasi_id;
                    this.changeLokasi();
                    // this.state.listBarang=response.data.list;

                    this.barang=response.data.list;
                    this.state.kurang=response.data.kurang;

                    if(this.state.kurang.length>0){
                        this.state.status_kurang='N';
                    }else{
                        this.state.status_kurang='Y';
                    }

                    for (var i = 0; i < this.barang.length; i++) {
                        this.state.idstok.push(this.barang[i].idstok);
                        this.state.kodes.push(this.barang[i].kd);
                        this.state.pdos.push(this.barang[i].dos);
                        this.state.ppcs.push(this.barang[i].pcs);
                        // if(this.barang[i].realisasi_pcsnya > 0 && this.barang[i].realisasi_dosnya > 0){
                        //     this.state.tampil[i]=true;
                        // }else{
                        //     this.state.tampil[i]=false;
                        // }
                        this.state.pcs[i]=this.barang[i].realisasi_pcsnya;
                        this.state.dos[i]=this.barang[i].realisasi_dosnya;
                        this.state.rak[i]=this.barang[i].rak_id;
                    }

                    
                })
        },

        changeLokasi(){
            axios.get('/data/list-rak?lokasi='+this.state.lokasi)
                .then(response => {
                    this.raks=response.data;
                })
        },

        store(e) {
            this.loading = true;

            axios.post(e.target.action, this.state).then(response => {
                if(response.data.success==true){
                    this.loading=false;
                    this.errors = [];
                    this.state = {
                        nama: ''
                    }
                    this.message = 'Data has been saved.';
                }else{
                    this.loading=false;
                    this.errors.nama=true;
                }
            }).catch(error => {
                if (! _.isEmpty(error.response)) {
                    if (error.response.status = 422) {
                        this.loading=false;
                        this.errors = error.response.data;
                        console.log(this.errors);
                    }
                }
            });
        },

        limitText (count) {
            return `and ${count} other countries`
        },

        getCustomer(){
            axios.get('/data/list-customer')
                .then(response => {
                    this.customers = response.data;
                })
        },

        getPerusahaan(){
            axios.get('/data/list-perusahaan')
                .then(response => {
                    this.perusahaans = response.data;
                })
        },

        getSales(){
            axios.get('/data/list-sales')
                .then(response => {
                    this.saless = response.data;
                })
        },

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then(response => {
                    this.lokasis = response.data;
                })
        },

        changeRak(i, r , b, l,p){
            console.log(p);
            var hasilpcs=0;
            
            axios.get('/data/stok-dirak?rak='+r+"&barang="+b+"&lokasi="+l)
                .then(response => {
                    this.hasilpcs[i]=response.data.pcs;
                    hasilpcs=response.data.pcs;
                    // console.log(response.data.pcs);
                })

            // console.log(parseInt(this.hasilpcs[i]));
            console.log(hasilpcs)
            if(this.hasilpcs[i] > parseInt(p)){
                this.stok[i]="Stok Cukup di rak ada : "+this.hasilpcs[i]+" PCs";
                this.state.dos[i]=0;
                this.state.pcs[i]=p;
                this.tampilTambah[i]=false;
                // this.$refs.nm.inputValue =pcs;
            }else{
                // alert('stok di rak ini tidak mencukupi');
                this.state.dos[i]=0;
                this.stok[i]="Stok Kurang, di rak ada : "+this.hasilpcs[i]+" PCs";
                this.tampilTambah[i]=true;
                this.state.pcs[i]=0;
            }
        },

        saveBarang(){
            if(this.barang.kode==""){
                alert('Barang harus diisi');

                return false;
            }

            if(this.barang.nama==""){
                alert('Nama Barang harus diisi');

                return false;
            }

            if(this.barang.harga==""){
                alert('Harga barang harus diisi');

                return false;
            }

            if(this.barang.dos==""){
                alert('Dos harus diisi');

                return false;
            }

            if(this.barang.pcs==""){
                alert('PCS harus diisi');

                return false;
            }

            if(this.barang.jumlah==""){
                alert('Jumlah harus diisi');

                return false;
            }


            this.state.listBarang.push(
                {
                    // kd_barang:this.barang.kode.kd,
                    kd_barang:this.barang.kode,
                    nm_barang:this.barang.nama,
                    harga:this.barang.harga,
                    dos:this.barang.dos,
                    pcs:this.barang.pcs,
                    diskon:this.barang.diskon,
                    jumlah:this.barang.jumlah
                }
            );

            this.kosongBarang();
        },

        kosongBarang(){
            this.barang.kode='';
            this.barang.nama='';
            this.barang.harga='';
            this.barang.pcs='';
            this.barang.dos='';
            this.barang.diskon='';
            this.barang.jumlah='';
        },

        deleteBarang: function(index) {
            this.state.listBarang.splice(index, 1);
        },

        showModal () {
            this.$refs.myModalRef.show()
            this.showData();
        },

        hideModal () {
            this.$refs.myModalRef.hide()
        },

        paginate(url){
            axios.get(url)
                .then(response => {
                    this.list = response.data;
                })
        },

        showData(page){
            if(typeof page === 'undefined'){
                page = 1;
            }

            axios.get('data/barang?page='+page)
                .then(response => {
                    this.loading=false;
                    this.list = response.data;
                    this.listData = response.data;
                })
        },

        cariData(page){
            if(typeof page === 'undefined'){
                page = 1;
            }

            axios.get('/data/barang?q='+this.pencarian)
                .then(response => {
                    this.list = response.data;
                })
                .catch( errors => {
                    alert('pencarian tidak ditemukan');
                })
        },

        pilihBarang(brg){
            console.log(brg);
            this.barang.kode=brg.kd;
            this.barang.nama=brg.nm;
            this.barang.harga=brg.jual;

            this.$refs.myModalRef.hide()
        },

        cariBarang(){
            axios.get('/data/cari-barang-by-kode/'+this.barang.kode)
                .then(response => {
                    if(response.data.success==true){
                        this.barang.kode = response.data.barang.kd;
                        this.barang.nama = response.data.barang.nm;
                        this.barang.harga = response.data.barang.jual;
                    }else{
                        alert('Barang tidak ditemukan');
                        this.barang.kode='';
                        this.barang.nama='';
                    }
                })
        },

        tambahBarang(br){
            this.barang.push(br);
            this.state.kodes.push(br.kd);
            this.state.pdos.push(br.pivot.dos)
            this.state.ppcs.push(br.pivot.pcs)
        },

        saveProgram(){
            // console.log(this.state);
            // console.log(this.kodes);
            // console.log(this.rak);
            if(this.state.kode==""){
                alert('Kode harus diisi');

                return false;
            }

            if(this.state.customer==""){
                alert('Customer harus diisi');

                return false;
            }

            this.loading = true;

            axios.post('/data/picking', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state.kode='';
                        this.state.no_po='';
                        this.state.customer='';
                        this.state.tanggal=new Date();
                        this.state.tanggaljt=new Date();
                        this.state.perusahaan='';
                        this.state.keterangan='';
                        this.state.lokasi='';
                        this.state.sales='';
                        this.state.kd_trans='Tunai';
                        this.state.rak=[];
                        this.state.kodes=[];
                        this.state.pdos=[];
                        this.state.ppcs=[];
                        this.state.rak=[];
                        this.state.dos=[];
                        this.state.pcs=[];
                        this.state.idstok=[];
                        this.barang=[];
                        this.state.kurang=[];
                        this.state.status_kurang='N';

                        this.getCode();
                        this.getNoPo();

                        this.message = 'Data has been saved.';
                        this.loading = false;
                    }else{
                        alert('Internal server error');
                    }
                })
        }
 
    },
    computed:{
        totalQty: function() {
            return this.listBarang.reduce(function(a, c){
                return a + Number(c.dos || 0)
            }, 0)
        }
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    input,
    select {
        padding: 0.75em 0.5em;
        font-size: 100%;
        border: 1px solid #ccc;
        width: 100%;
    }

    select {
        height: 2.5em;
    }

    .example {
        background: #f2f2f2;
        border: 1px solid #ddd;
        padding: 0em 1em 1em;
        margin-bottom: 2em;
    }

    code,
    pre {
        margin: 1em 0;
        padding: 1em;
        border: 1px solid #bbb;
        display: block;
        background: #ddd;
        border-radius: 3px;
    }

    .settings {
        margin: 2em 0;
        border-top: 1px solid #bbb;
        background: #eee;
    }

    h5 {
        font-size: 100%;
        padding: 0;
    }

    .form-group {
        margin-bottom: 1em;
    }

    .form-group label {
        font-size: 80%;
        display: block;
    }
</style>
