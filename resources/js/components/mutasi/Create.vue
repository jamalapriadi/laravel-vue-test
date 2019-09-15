<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Add New Mutasi
        </div>
        <div class="card-body">

            <div v-if="message" class="alert alert-success">
                {{ message }}
            </div>

            <form @submit.prevent="store" action="/data/program" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Nomor Mutasi</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Keterangan</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" v-model="state.keterangan">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Tanggal</label>
                            <div class="col-lg-9">
                                <date-picker v-model="state.tanggal" :config="options"></date-picker>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>

            <br><br>
            <div class="card card-default">
                <div class="card-header">List Barang</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="control-label">Pilih Lokasi Awal</label>
                                <select name="" id="" class="form-control" v-model="state.gudang_lama" @change="changeLokasi(state.gudang_lama.id,'lama')">
                                    <option value="" disabled selected>--Pilih Lokasi--</option>
                                    <option v-for="(l,index) in lokasis" v-bind:key="index" v-bind:value="l">{{l.nm}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="control-label">Pilih Lokasi Tujuan</label>
                                <select name="" id="" class="form-control" v-model="state.gudang_baru" @change="changeLokasi(state.gudang_baru.id,'baru')">
                                    <option value="" disabled selected>--Pilih Lokasi--</option>
                                    <option v-for="(l,index) in lokasis2" v-bind:key="index" v-bind:value="l">{{l.nm}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <fieldset style="margin-bottom:30px;">
                        <legend>Barang</legend>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="" class="control-label">Pilih Rak Awal</label>
                                    <select name="rak" id="rak" class="form-control" v-model="state.rak_lama">
                                        <option value="" disabled selected>--Pilih Rak--</option>
                                        <option v-for="(l,index) in raks" v-bind:key="index" v-bind:value="l">{{l.nm}}</option>
                                    </select>
                                </div>      
                            </div>

                            <div class="form-group col-md-4">
                                <label for="" class="control-label">Kode / Nama barang</label>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <vue-bootstrap-typeahead v-model="carikodebarang" :data="listkodebarang" placeholder="Cari Kode Barang..." @hit="getKodeBarang($event)" ref="kodebarang"/>
                                    </div>
                                    <div class="col-lg-7">
                                        <vue-bootstrap-typeahead v-model="carinamabarang" :data="listcaribarang" placeholder="Cari Barang..." @hit="getNamaBarang($event)" ref="namabarang"/>
                                    </div>
                                </div>
                                <small>*stok {{barang.stok}}</small>
                                
                            </div>

                            <div class="form-group col-md-1">
                                <label for="" class="control-label">Dos</label>
                                <input type="text" class="form-control" v-model="barang.dos" @input="validasiDetailDos($event)">
                            </div>

                            <div class="form-group col-md-1">
                                <label for="" class="control-label">PCS</label>
                                <input type="text" class="form-control" v-model="barang.pcs" @input="validasiDetailPcs($event)">
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="" class="control-label">Pilih Rak Tujuan</label>
                                    <select name="rak" id="rak" class="form-control" v-model="barang.rak_baru">
                                        <option value="" disabled selected>--Pilih Rak--</option>
                                        <option v-for="(l,index) in rakslama" v-bind:key="index" v-bind:value="l">{{l.nm}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-1">
                                <div class="btn-group">
                                    <button v-on:click="saveBarang()" class="btn btn-primary" style="margin-top:25px;">
                                        <i class="fa fa-plus"></i>
                                        Add
                                    </button>

                                    <!-- <a @click="showModal" class="btn btn-info text-white" style="margin-top:25px;">
                                        <i class="fa fa-list"></i> List Barang
                                    </a> -->
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Gudang / Rak Lama</th>
                                <th>Gudang / Rak Baru</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Dos</th>
                                <th>Pcs</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                                <td>{{ index+1 }}</td>
                                <td>{{l.gudang_lama}} / {{l.rak_lama}}</td>
                                <td>{{l.gudang_baru}} / {{l.rak_baru}}</td>
                                <td>{{l.kd_barang}}</td>
                                <td>{{l.nm_barang}}</td>
                                <td>{{l.dos}}</td>
                                <td>{{l.pcs}}</td>
                                <td>
                                    <a @click="deleteBarang(index)" class="btn btn-danger text-white">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="3"> Total</td>
                                <td>{{totalQty}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot> -->
                    </table>

                    
                    <hr>
                    
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div class="form-group">
                        <router-link to="/list-mutasi" class="btn btn-default">
                            <i class="fa fa-backward"></i> Back
                        </router-link>

                        <button class="btn btn-primary" v-on:click="saveProgram">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                    </div>


                </div>
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
                tanggal:new Date(),
                keterangan:'',
                gudang_lama:{},
                gudang_baru:{},
                rak_lama:'',
                // perusahaan:'',
                listBarang:[]
            },
            date: new Date(),
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            }, 
            message:'',
            loading:false,
            errors: [],
            selectedBarangs: '',
            barangs: [],
            barang:{
                gudang_baru:'',
                rak_baru:'',
                kode:'',
                nama:'',
                dos:0,
                pcs:0,
                stok:0,
                ppcs:0
            },
            listBarang:[],
            list:[],
            listData:{},
            pencarian:'',
            isLoading: false,
            listCBarang:[],
            listcaribarang:[],
            listkodecustomer:[],
            listkodebarang:[],
            carinamabarang:'',
            carikodebarang:'',
            carinamacustomer:'',
            carikodecustomer:'',
            lokasis:[],
            lokasis2:[],
            raks:[],
            rakslama:[]
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

        carikodebarang: _.debounce(function(q){
            this.cariBarangByKode(q);
        }, 500),

    },
    mounted() {
        this.getCode();
        this.getLokasi();
        // this.getPerusahaan();
    },
    methods: {
        getCode(){
            axios.get('/data/autonumber-mutasi')
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
            axios.get('/data/cari-barang-by-nama-2?q='+query+"&rak="+this.state.rak_lama.kd)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listcaribarang.push(response.data[i].nm);

                        this.listCBarang.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm,
                                stok:response.data[i].stok,
                                pcs:parseInt(response.data[i].pcs)
                            }
                        );
                    }
                })
        },

        async cariBarangByKode(q){
            this.listkodebarang=[];
            this.listCBarang=[];
            axios.get('/data/cari-barang-by-nama-2?q='+q+"&rak="+this.state.rak_lama.kd)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listkodebarang.push(response.data[i].kd);

                        this.listCBarang.push(
                            {
                                kd:response.data[i].kd,
                                nm:response.data[i].nm,
                                stok:parseInt(response.data[i].stok),
                                pcs:parseInt(response.data[i].pcs)
                            }
                        );
                    }
                })
        },
        
        getNamaBarang(item){
            let unique = [...new Set(this.listCBarang)]; 
            var nama="";
            var stok=0;
            var pcs=0;
            
            for(var i=0; i< unique.length; i++){
                if(unique[i].nm == item){
                    nama=unique[i].kd;
                    stok=parseInt(unique[i].stok);
                    pcs=parseInt(unique[i].pcs);
                }
            }

            this.barang.nama=item;
            this.barang.kode=nama;
            this.barang.stok=stok;
            this.barang.ppcs=pcs;
            // this.carinamabarang=nama;
            this.$refs.kodebarang.inputValue = nama
        },

        getKodeBarang(item){
            let unique = [...new Set(this.listCBarang)]; 
            var nama="";
            var stok=0;
            var pcs=0;
            
            for(var i=0; i< unique.length; i++){
                if(unique[i].kd == item){
                    nama=unique[i].nm;
                    stok=parseInt(unique[i].stok);
                    pcs=parseInt(unique[i].pcs);
                }
            }

            this.barang.kode=item;
            this.barang.nama=nama;
            this.barang.stok=stok;
            this.barang.ppcs=pcs;
            // this.carinamabarang=nama;
            this.$refs.namabarang.inputValue = nama;
        },

        validasiDetailDos(event){
            var pcs_per_dos=parseInt(this.barang.ppcs);
            var stok=parseInt(this.barang.stok);
            var dos=parseInt(this.barang.dos);
            var pcs=parseInt(this.barang.pcs);

            var jumlah = parseInt(pcs_per_dos*dos) + pcs;

            if(jumlah >  stok){
                alert('Jumlah pcs melebihi jumlah pcs yang diminta')
                this.barang.dos=0;
                this.barang.pcs=0;
            }
        },

        validasiDetailPcs(event){
            var pcs_per_dos=parseInt(this.barang.ppcs);
            var stok=parseInt(this.barang.stok);
            var dos=parseInt(this.barang.dos);
            var pcs=parseInt(this.barang.pcs);

            var jumlah = parseInt(pcs_per_dos*dos) + pcs;

            if(jumlah >  stok){
                alert('Jumlah pcs melebihi jumlah pcs yang diminta')
                this.barang.dos=0;
                this.barang.pcs=0;
            }
        },


        limitText (count) {
            return `and ${count} other countries`
        },

        saveBarang(){
            if(this.barang.rak_lama==""){
                alert('Rak harus diisi');

                return false;
            }

            if(this.barang.kode==""){
                alert('Barang harus diisi');

                return false;
            }

            if(this.barang.nama==""){
                alert('Nama Barang harus diisi');

                return false;
            }

            if(this.barang.rak_baru==""){
                alert('Rak Tujuan harus diisi');

                return false;
            }

            // if(this.barang.pcs==""){
            //     alert('PCS harus diisi');

            //     return false;
            // }

            console.log(this.state.gudang_baru);

            this.state.listBarang.push(
                {
                    // kd_barang:this.barang.kode.kd,
                    gudang_lama_id:this.state.gudang_lama.id,
                    gudang_lama:this.state.gudang_lama.nm,
                    rak_lama_id:this.state.rak_lama.kd,
                    rak_lama:this.state.rak_lama.nm,
                    gudang_baru_id:this.state.gudang_baru.id,
                    gudang_baru:this.state.gudang_baru.nm,
                    rak_baru_id:this.barang.rak_baru.kd,
                    rak_baru:this.barang.rak_baru.nm,
                    kd_barang:this.barang.kode,
                    nm_barang:this.barang.nama,
                    dos:this.barang.dos,
                    pcs:this.barang.pcs
                }
            );

            this.kosongBarang();
        },

        kosongBarang(){
            this.barang.kode='';
            this.barang.nama='';
            this.barang.dos=0;
            this.barang.pcs=0;
            this.barang.stok=0;
            this.barang.ppcs=0;
            this.$refs.kodebarang.inputValue = '';
            this.$refs.namabarang.inputValue = '';
        },

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then(response => {
                    this.lokasis = response.data;
                    this.lokasis2 = response.data;
                })
        },

        changeLokasi(gudang,type){
            axios.get('/data/list-rak?lokasi='+gudang)
                .then(response => {
                    if(type=="lama"){
                        this.raks=response.data;
                    }else{
                        this.rakslama=response.data;
                    }
                })
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

            // if(this.state.customer==""){
            //     alert('Customer harus diisi');

            //     return false;
            // }

            // if(this.state.perusahaan==""){
            //     alert('Perusahaan Barang harus diisi');

            //     return false;
            // }

            this.loading = true;

            axios.post('/data/mutasi', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state.kode='',
                        this.state.tanggal=new Date(),
                        this.state.keterangan='',
                        this.state.gudang_lama={},
                        this.state.gudang_baru={},
                        this.state.rak_lama='',
                        // perusahaan:'',
                        this.state.listBarang=[];

                        this.getCode();
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

    fieldset{
        border: 1px solid #ddd !important;
        margin: 0;
        min-width: 0;
        padding: 10px;       
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;
    }	

    legend{
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px; 
        width: 35%; 
        border: 1px solid #ddd;
        border-radius: 4px; 
        padding: 5px 5px 5px 10px; 
        background-color: #d8dfe5;
        color:#222;
    }
</style>
