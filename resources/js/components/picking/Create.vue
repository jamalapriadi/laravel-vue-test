<template>
    <div class="row">
        <div class="col-lg-3">
            <div class="card card-accent-primary">
                <div class="card-header">
                    Picking
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="" class="control-label">Kode Picking</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">Tanggal</label>
                        <date-picker v-model="state.tanggal" :config="options"></date-picker>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">Kode Transaksi</label>
                        <select name="kd_trans" id="kd_trans" class="form-control" v-model="state.kd_trans">
                            <option v-for="(l,index) in kodetrans" v-bind:value="l" v-bind:key="index">{{l}}</option>
                        </select>
                    </div>

                     <!-- v-if="state.kd_trans == 'Kredit'" -->
                    <div class="form-group">
                        <label for="" class="control-label">Tanggal Jatuh Tempo</label>
                        <date-picker v-model="state.tanggaljt" :config="options"></date-picker>
                    </div>

                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card card-default">
                <div class="card-header">Detail Picking</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Nomor PO</label>
                                <div class="col-lg-9">
                                    <select name="po" id="po" class="form-control" v-model="state.no_po" @change="changePo">
                                        <option value="" disabled selected>--Pilih PO--</option>
                                        <option v-for="(l,index) in pos" v-bind:key="index" v-bind:value="l.no_po">{{l.no_po}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Customer</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" v-model="state.customer" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Sales</label>
                                <div class="col-lg-9">
                                    <select name="sales" id="sales" class="form-control" v-model="state.sales">
                                        <option value="" disabled selected>--Pilih Sales--</option>
                                        <option v-for="(l, index) in saless" v-bind:key="index"  v-bind:value="l.id">{{l.id}} - [{{l.nm}}]</option>
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

                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Keterangan</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" v-model="state.keterangan">
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode / Nama Barang</th>
                                <th>Dos / PCS  PO</th>
                                <th>Rak</th>
                                <th>Dos</th>
                                <th>PCS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(l,index) in barang" v-bind:key="index">
                                <!-- <input type="hidden" v-model="state.kodes[index]" :value="l.kd">
                                <input type="hidden" v-model="state.pdoss[index]" :value="l.pivot.dos">
                                <input type="hidden" v-model="state.ppcss[index]" :value="l.pivot.pcs"> -->
                                <td>{{ index+1 }}</td>
                                <td>{{l.kd}} / {{l.nm}}</td>
                                <td>{{l.pivot.dos}} / {{l.pivot.pcs}}</td>
                                <td>
                                    <select name="rak" id="rak" class="form-control" v-model="state.rak[index]">
                                        <option value="" disabled selected>--Pilih Rak--</option>
                                        <option v-for="(k,index) in raks" v-bind:key="index" v-bind:value="k.kd">{{k.kd}} [ {{k.nm}} ]</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="dos" placeholder="Dos" v-model="state.dos[index]">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="pcs" placeholder="pcs" v-model="state.pcs[index]">
                                </td>
                            </tr>
                        </tbody>
                    </table>

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

export default {
    components: {
        VueLoading,
        datePicker,
        Multiselect 
    },
    data() {
        return {
            state: {
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
                pcs:[]
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
        }
    },
    watch: {
        pencarian: function(q) {
            if (q != ''){
                this.cariData();
            }else{
                this.showData();
            }
        }
    },
    mounted() {
        this.getCode();
        // this.getCustomer();
        // this.getPerusahaan();
        this.getNoPo();
        this.getSales();
        this.getLokasi();
    },
    methods: {
        getNoPo(){
            axios.get('/data/list-po-not-in-picking')
                .then(response => {
                    this.pos= response.data
                })
        },
        getCode(){
            axios.get('/data/autonumber-picking')
                .then(response => {
                    this.state.kode = response.data;
                })
        },
        changePo(){
            axios.get('/data/po/'+this.state.no_po)
                .then(response => {
                    this.state.customer=response.data.customer.nm;
                    this.barang=response.data.detail;

                    for (var i = 0; i < this.barang.length; i++) {
                        this.state.kodes.push(this.barang[i].kd);
                        this.state.pdos.push(this.barang[i].pivot.dos)
                        this.state.ppcs.push(this.barang[i].pivot.pcs)
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

            if(this.state.sales==""){
                alert('Sales Barang harus diisi');

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
                        this.barang=[];

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
