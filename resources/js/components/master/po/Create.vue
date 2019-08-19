<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Add New PO
        </div>
        <div class="card-body">

            <div v-if="message" class="alert alert-success">
                {{ message }}
            </div>

            <div v-if="adahutang" class="alert alert-warning">
                {{ adahutang }}
            </div>

            <form @submit.prevent="store" action="/data/program" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Nomor PO</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Tanggal</label>
                            <div class="col-lg-9">
                                <date-picker v-model="state.tanggal" :config="options"></date-picker>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Customer</label>
                            <div class="col-lg-9">
                                <!-- <select name="customer" id="customer" class="form-control" v-model="state.customer">
                                    <option value="" disabled selected>--Pilih Customer--</option>
                                    <option v-for="(l, index) in customers" v-bind:key="index"  v-bind:value="l.kd">{{l.kd}} - [{{l.nm}}]</option>
                                </select> -->
                                <div class="row">
                                    <div class="col-lg-4">
                                        <vue-bootstrap-typeahead v-model="carikodecustomer" :data="listkodecustomer" placeholder="Kode Customer" @hit="getKodeCustomer($event)" ref="kodecustomer"/>
                                    </div>
                                    <div class="col-lg-8">
                                        <vue-bootstrap-typeahead v-model="carinamacustomer" :data="listcaricustomer" placeholder="Cari Customer..." @hit="getNamaCustomer($event)" ref="namacustomer"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Lokasi</label>
                            <div class="col-lg-9">
                                <select name="lokasi" id="lokasi" class="form-control" v-model="state.lokasi" @change="changeLokasi">
                                    <option value="" disabled selected>--Pilih Lokasi--</option>
                                    <option v-for="(l, index) in lokasis" v-bind:key="index"  v-bind:value="l.id">{{l.id}} - [{{l.nm}}]</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Keterangan</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" v-model="state.keterangan">
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>

            <br><br>
            <div v-show="state.lokasi!=''">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="" class="control-label">Nama Barang</label>
                        <div class="row">
                            <!-- <div class="col-lg-6">
                                <vue-bootstrap-typeahead v-model="carikodebarang" :data="listkodebarang" placeholder="Cari Kode Barang..." @hit="getKodeBarang($event)" ref="kodebarang"/>
                            </div> -->
                            <div class="col-lg-12">
                                <vue-bootstrap-typeahead v-model="carinamabarang" :data="listcaribarang" placeholder="Cari Barang..." @hit="getNamaBarang($event)" ref="namabarang"/>
                                stok <strong>{{barang.stok}}</strong> PCS
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
                        <p>
                            <small>
                                {{hasilpcs}} jumlah per 1 dos
                            </small>
                        </p>
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
            </div>


            <table class="table table-striped" v-show="state.lokasi!=''">
                <thead>
                    <tr>
                        <th>No.</th>
                        <!-- <th>Kode Barang</th> -->
                        <th>Nama Barang</th>
                        <th>Dos</th>
                        <th>Pcs</th>
                        <th>Rak</th>
                        <th>Total Pcs</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                        <td>{{ index+1 }}</td>
                        <!-- <td>{{l.kd_barang}}</td> -->
                        <td>{{l.nm_barang}}</td>
                        <td>{{l.dos}}</td>
                        <td>{{l.pcs}}</td>
                        <td>
                            <ol v-if="l.rak.length > 0">
                                <li v-for="(k,idx) in l.rak" v-bind:key="idx">{{k.rak}}</li>
                            </ol>
                            <label class="label label-info" v-else>Rak tidak ditemukan</label>
                        </td>
                        <td>{{l.total_pcs}}</td>
                        <td>
                            <a @click="deleteBarang(index)" class="btn btn-danger text-white">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Total PCS</th>
                        <th>{{total_barang}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            
            <hr>
            
            <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

            <div class="form-group">
                <router-link to="/po" class="btn btn-default">
                    <i class="fa fa-backward"></i> Back
                </router-link>

                <button class="btn btn-primary" v-on:click="saveProgram">
                    <i class="fa fa-save"></i>
                    Save
                </button>
            </div>


            <b-modal ref="myModalRef" size="lg" hide-footer title="List Barang">
                <form class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control" name="q" id="q" placeholder="Type and Enter" v-model="pencarian">
                    </div>
                </form>

                <br>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kelompok</th>
                            <th>Merk</th>
                            <th>Status</th>
                            <th>Satuan</th>
                            <th>PCS</th>
                            <th width="17%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(l, index) in list.data" v-bind:key="index">
                            <td>{{index+1}}</td>
                            <td>{{l.kd}}</td>
                            <td>{{l.nm}}</td>
                            <td>{{l.kelompok.nm}}</td>
                            <td>{{l.merk.nm}}</td>
                            <td>{{l.status}}</td>
                            <td>{{l.satuan}}</td>
                            <td>{{l.pcs}}</td>
                            <td>
                                <div class="btn-group">
                                    <a @click="pilihBarang(l)" class="btn btn-warning text-white">
                                        <i class="fa fa-check"></i>
                                        Pilih
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    
                <div align="right">
                    <pagination :data="listData" @pagination-change-page="showData" :show-disabled="true"></pagination>
                </div>
                
                
            </b-modal>

        </div>

        <!-- print -->
        <div id="printMe" style="margin-top:10px;display:none;">
            <br>
            <table>
                <tr>
                    <td>No. PO</td>
                    <td> : </td>
                    <td>{{dataprint.no_po}}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td> : </td>
                    <td>{{dataprint.tanggal}}</td>
                </tr>
                <tr>
                    <td>Customer</td>
                    <td> : </td>
                    <td>{{dataprint.customer}}</td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td> : </td>
                    <td>{{dataprint.lokasi}}</td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td> : </td>
                    <td>{{dataprint.keterangan}}</td>
                </tr>
            </table>
            <hr>
            <div v-for="(l,index) in dataprint.detail" v-bind:key="index">
                <table width="45%">
                    <thead>
                        <tr>
                            <th rowspan="3">{{l.nm}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width='50%'> Rak :
                                <ul>
                                    <li v-for="(k,idx) in l.rak" v-bind:key="idx">{{k.rak}} - {{k.diambil}}</li>
                                </ul>
                            </td>
                            <td>{{l.dos}} Dos</td>
                            <td>{{l.pcs}} Pcs</td>
                        </tr>
                    </tbody>
                </table>
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
                kode:'',
                nama: '',
                customer:'',
                lokasi:'',
                tanggal:new Date(),
                // perusahaan:'',
                keterangan:'',
                listBarang:[],
                sisahutang:0,
                bolehhutang:'Y',
                plafon:0,
                totalharga:0,
                statuskonfirmasi:'Accept',
            },
            date: new Date(),
            total_barang:0,
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            }, 
            message:'',
            adahutang:'',
            loading:false,
            errors: [],
            value: null,
            tanggal: ['list', 'of', 'options'],
            selectedBarangs: '',
            barangs: [],
            barang:{
                kode:'',
                nama:'',
                qty:'',
                point:'',
                dos:0,
                pcs:0,
                total_pcs:0,
                harga:0,
                stok:0
            },
            listBarang:[],
            list:[],
            listData:{},
            pencarian:'',
            isLoading: false,
            customers:[],
            perusahaans:[],
            listCBarang:[],
            listCCustomer:[],
            listcaribarang:[],
            listcaricustomer:[],
            listkodecustomer:[],
            listkodebarang:[],
            carinamabarang:'',
            carikodebarang:'',
            carinamacustomer:'',
            carikodecustomer:'',
            hasilpcs:0,
            lokasis:[],
            dataprint:{
                no_po:'',
                tanggal:'',
                customer:'',
                lokasi:'',
                keterangan:'',
                detail:[]
            }
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

        carinamabarang: _.debounce(function(addr){
            this.cariBarangByNama(addr);
        }, 500),

        carinamacustomer: _.debounce(function(q){
            this.cariNamaCustomerByName(q);
        },500),

        carikodebarang: _.debounce(function(q){
            this.cariBarangByKode(q);
        }, 500),

        carikodecustomer: _.debounce(function(q){
            this.cariKodeCustomerById(q);
        },500),
    },
    mounted() {
        this.getCode();
        this.getCustomer();
        this.getLokasi();
        // this.getPerusahaan();
    },
    methods: {
        getCode(){
            axios.get('/data/autonumber-po')
                .then(response => {
                    this.state.kode = response.data;
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

        async cariBarangByNama(query){
            this.listcaribarang=[];
            this.listCBarang=[];
            let result=[];
            axios.get('/data/cari-barang-by-nama?q='+query+"&lokasi="+this.state.lokasi)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listcaribarang.push(response.data[i].nm);

                        this.listCBarang.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm,
                                pcs:response.data[i].pcs,
                                jual:response.data[i].jual,
                                stok:response.data[i].stok
                            }
                        );
                    }
                })
        },

        async cariBarangByKode(q){
            this.listkodebarang=[];
            this.listCBarang=[];
            axios.get('/data/cari-barang-by-nama?q='+q+"&lokasi="+this.state.lokasi)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listkodebarang.push(response.data[i].kd);

                        this.listCBarang.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm,
                                pcs:response.data[i].pcs,
                                jual:response.data[i].jual,
                                stok:response.data[i].stok
                            }
                        );
                    }
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
                                nm:response.data[i].nm
                            }
                        );
                    }
                })
        },

        async cariKodeCustomerById(q){
            this.listkodecustomer=[];
            this.listCCustomer=[];
            axios.get('/data/cari-customer-by-nama?q='+q)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listkodecustomer.push(response.data[i].kd);

                        this.listCCustomer.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm
                            }
                        );
                    }
                })
        },
        
        getNamaBarang(item){
            let unique = [...new Set(this.listCBarang)]; 
            var nama="";
            var pcs=0;
            var jual=0;
            var stok=0;
            
            for(var i=0; i< unique.length; i++){
                if(unique[i].nm == item){
                    nama=unique[i].kd;
                    pcs=unique[i].pcs;
                    jual=unique[i].jual;
                    stok=unique[i].stok
                }
            }

            this.barang.kode=nama;
            this.barang.nama=item;
            this.barang.pcs=pcs;
            this.barang.dos=0;
            this.barang.pcs=0;
            this.barang.total_pcs=0;
            this.barang.harga=jual;
            this.hasilpcs=pcs;
            this.barang.stok=stok;
            // this.carinamabarang=nama;
            this.$refs.kodebarang.inputValue = nama
        },

        getKodeBarang(item){
            let unique = [...new Set(this.listCBarang)]; 
            var nama="";
            var pcs=0;
            var jual=0;
            var stok=0;
            
            for(var i=0; i< unique.length; i++){
                if(unique[i].kd == item){
                    nama=unique[i].nm;
                    pcs=unique[i].pcs;
                    jual=unique[i].jual;
                    stok=unique[i].stok
                }
            }

            this.barang.kode=item;
            this.barang.nama=nama;
            this.barang.pcs=pcs;
            this.barang.dos=0;
            this.barang.pcs=0;
            this.barang.total_pcs=0;
            this.barang.harga=jual;
            this.barang.stok=stok;
            this.hasilpcs=pcs;
            this.$refs.namabarang.inputValue = nama;
        },

        changeDos(){
            if(this.barang.kode==""){
                alert('Barang harus diisi');

                return false;
            }


            this.barang.total_pcs=parseInt(this.barang.dos)*parseInt(this.hasilpcs) + parseInt(this.barang.pcs);
        },

        hitungTotal(event){
            if(this.barang.kode==""){
                alert('Barang harus diisi');

                return false;
            }


            this.barang.total_pcs=parseInt(this.barang.dos)*parseInt(this.hasilpcs) + parseInt(this.barang.pcs);
       },

       hitungTotalPcs(event){
           if(this.barang.kode==""){
                alert('Barang harus diisi');

                return false;
            }


            this.barang.total_pcs=parseInt(this.barang.dos)*parseInt(this.hasilpcs) + parseInt(this.barang.pcs);
       },

        changePcs(nya){
            if(this.barang.kode==""){
                alert('Barang harus diisi');

                return false;
            }


            this.barang.total_pcs=parseInt(this.barang.dos)*parseInt(this.hasilpcs) + parseInt(nya);
        },

        getNamaCustomer(item){
            let uniqueCus = [...new Set(this.listCCustomer)]; 
            var nama="";
            
            for(var i=0; i< uniqueCus.length; i++){
                if(uniqueCus[i].nm == item){
                    nama=uniqueCus[i].kd;
                }
            };

            this.$refs.kodecustomer.inputValue = nama
            this.state.customer=nama;

            this.hutangCustomer(nama);
        },

        getKodeCustomer(item){
            let uniqueCus = [...new Set(this.listCCustomer)]; 
            var nama="";
            
            for(var i=0; i< uniqueCus.length; i++){
                if(uniqueCus[i].kd == item){
                    nama=uniqueCus[i].nm;
                }
            };
            
            this.$refs.namacustomer.inputValue = nama
            this.state.customer=item;

            this.hutangCustomer(item);
        },

        hutangCustomer(cst){
            axios.get('/data/sisa-hutang-customer/'+cst)
                .then(response => {
                    this.state.sisahutang=response.data.sisa;
                    this.state.bolehhutang=response.data.boleh;
                    this.state.plafon=response.data.plafon;
                })
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

        saveBarang(){
            if(this.barang.kode==""){
                alert('Barang harus diisi');

                return false;
            }

            if(this.barang.nama==""){
                alert('Nama Barang harus diisi');

                return false;
            }

            if(this.state.customer==""){
                alert('Pilih Customer lebih dahulu')

                return false;
            }

            if(this.state.lokasi=="" || this.state.lokasi == undefined){
                alert('Lokasi harus diisi');

                return false;
            }

            axios.get('/data/get-rak-by-barang/'+this.barang.kode+'?dos='+this.barang.dos+'&pcs='+this.barang.pcs+'&lokasi='+this.state.lokasi)
                .then(response => {
                    if(response.data.success==true){
                        this.state.listBarang.push(
                            {
                                // kd_barang:this.barang.kode.kd,
                                kd_barang:response.data.kd_barang,
                                nm_barang:response.data.nm_barang,
                                dos:parseInt(response.data.dos),
                                pcs:parseInt(response.data.pcs),
                                total_pcs:parseInt(response.data.total_pcs),
                                harga:parseInt(this.barang.harga) * parseInt(this.barang.total_pcs),
                                rak:response.data.rak
                            }
                        );

                        this.state.totalharga=0;
                        this.total_barang=0;

                        for(var a=0; a < this.state.listBarang.length; a++){
                            this.state.totalharga+=this.state.listBarang[a].harga;
                            this.total_barang+=parseInt(this.state.listBarang[a].total_pcs);
                        }
                        // console.log(this.state.total_barang);
                        this.hitunghutang();

                        this.kosongBarang();
                    }else{
                        alert('silahkan lengkapi data');
                    }
                })
        },

        kosongBarang(){
            this.barang.kode='';
            this.barang.nama='';
            this.barang.dos=0;
            this.barang.pcs=0;
            this.barang.total_pcs=0;
            this.barang.stok=0;
            this.hasilpcs=0;
            this.$refs.kodebarang.inputValue = '';
            this.$refs.namabarang.inputValue = '';
        },

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then(response => {
                    this.lokasis = response.data;
                })
        },

        changeLokasi(){
            axios.get('/data/list-rak?lokasi='+this.state.lokasi)
                .then(response => {
                    this.raks=response.data;
                })
        },

        deleteBarang: function(index) {
            this.state.listBarang.splice(index, 1);

            this.state.totalharga=0;
            for(var a=0; a<this.state.listBarang.length; a++){
                this.state.totalharga+=this.state.listBarang[a].harga;
            }

            this.hitunghutang()
        },

        hitunghutang(){
            var t=parseInt(this.state.sisahutang) + parseInt(this.state.totalharga);
            
            if(t > parseInt(this.state.plafon)){
                this.message="Plafon ="+this.state.plafon+", Jumlah pembayaran kredit melebihi plafon dari hutang yang tersisa";
                this.state.statuskonfirmasi='Please Confirm';
            }

            // this.message="Plafon = "+this.state.plafon+" rencana hutang ="+t;
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
            this.hasilpcs=brg.pcs;
            this.barang.pcs=brg.pcs;
            this.barang.dos=0;
            this.barang.pcs=0;
            this.barang.total_pcs=0;
            this.$refs.kodebarang.inputValue = brg.kd;
            this.$refs.namabarang.inputValue = brg.nm;

            this.$refs.myModalRef.hide()
        },

        cariBarang(){
            axios.get('/data/cari-barang-by-kode/'+this.barang.kode)
                .then(response => {
                    if(response.data.success==true){
                        this.barang.kode = response.data.barang.kd;
                        this.barang.nama = response.data.barang.nm;
                    }else{
                        alert('Barang tidak ditemukan');
                        this.barang.kode='';
                        this.barang.nama='';
                    }
                })
        },

        saveProgram(){
            if(this.state.kode==""){
                alert('Kode harus diisi');

                return false;
            }

            if(this.state.customer==""){
                alert('Customer harus diisi');

                return false;
            }

            if(this.state.perusahaan==""){
                alert('Perusahaan Barang harus diisi');

                return false;
            }

            if(this.state.lokasi==""){
                alert('Lokasi harus diisi');

                return false;
            }

            this.loading = true;

            axios.post('/data/po', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state.kode='';
                        this.state.nama= '';
                        this.state.customer='';
                        this.state.tanggal=new Date();
                        // this.state.perusahaan='';
                        this.state.keterangan='';
                        this.state.total_barang=0;
                        this.total_barang=0;
                        this.$refs.kodecustomer.inputValue = '';
                        this.$refs.namacustomer.inputValue = '';
                        this.state.listBarang=[];

                        if(response.data.adahutang=="Y"){
                            this.adahutang="Customer ini memiliki order yang sudah jatuh tempo";
                        }else if(response.data.adahutang=="X"){
                            this.adahutang="Jumlah tagihan lebih besar daripada plafonnya";
                        }else if(response.data.adahutang=="N"){
                            this.adahutang="";
                        }else{
                            this.adahutang="";
                        }   

                        this.getCode();
                        this.message = 'Data has been saved.';
                        this.loading = false;

                        this.dataprint={
                            perusahaan:response.data.nota.perusahaan.nama,
                            no_po:response.data.nota.no_po,
                            tanggal:response.data.nota.tgl,
                            customer:response.data.nota.customer.nm,
                            lokasi:response.data.nota.lokasi.nm,
                            keterangan:response.data.nota.keterangan,
                            detail:response.data.detail
                        }

                        this.$nextTick(() => {
                            this.$htmlToPaper('printMe');
                        });
                        
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
