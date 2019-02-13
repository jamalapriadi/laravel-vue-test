<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Add New PO
        </div>
        <div class="card-body">

            <div v-if="message" class="alert alert-success">
                {{ message }}
            </div>

            <form @submit.prevent="store" action="/data/program" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Nomor PO</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Tanggal</label>
                            <div class="col-lg-9">
                                <date-picker v-model="state.tanggal" :config="options"></date-picker>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Customer</label>
                            <div class="col-lg-9">
                                <select name="customer" id="customer" class="form-control" v-model="state.customer">
                                    <option value="" disabled selected>--Pilih Customer--</option>
                                    <option v-for="(l, index) in customers" v-bind:key="index"  v-bind:value="l.kd">{{l.kd}} - [{{l.nm}}]</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Perusahaan</label>
                            <div class="col-lg-9">
                                <select name="perusahaan" id="perusahaan" class="form-control" v-model="state.perusahaan">
                                    <option value="" disabled selected>--Pilih Perusahaan--</option>
                                    <option v-for="(l, index) in perusahaans" v-bind:key="index"  v-bind:value="l.id">{{l.nama}}</option>
                                </select>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Keterangan</label>
                            <div class="col-lg-9">
                                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" v-model="state.keterangan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>

            <br><br>
            <div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="" class="control-label">Kode barang</label>
                        <!-- <multiselect v-model="value" :options="tanggal"></multiselect> -->

                        <!-- <multiselect v-model="barang.kode" 
                            id="ajax" 
                            label="nm" 
                            track-by="kd" 
                            placeholder="Type to search" 
                            open-direction="bottom" 
                            :multiple="false"
                            :options="barangs" 
                            :searchable="true" 
                            :loading="isLoading" 
                            :internal-search="false" 
                            :clear-on-select="false" 
                            :close-on-select="true" 
                            :options-limit="25" 
                            :limit="3" 
                            :limit-text="limitText" 
                            :max-height="600" 
                            :show-no-results="false" 
                            :hide-selected="false" 
                            :custom-label="nameWithLang"
                            @input="executeLoader"
                            @search-change="asyncFind">
                        </multiselect> -->

                        <input type="text" placeholder="Cari Kode barang" class="form-control" v-model="barang.kode" v-on:keyup.enter="cariBarang">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">Nama Barang</label>
                        <input type="text" placeholder="Cari barang" class="form-control" v-model="barang.nama" readonly>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">Dos</label>
                        <input type="text" class="form-control" v-model="barang.dos">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">PCS</label>
                        <input type="text" class="form-control" v-model="barang.pcs">
                    </div>

                    <div class="form-group col-md-3">
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


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
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
                nama: '',
                customer:'',
                tanggal:new Date(),
                // perusahaan:'',
                keterangan:'',
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
            value: null,
            tanggal: ['list', 'of', 'options'],
            selectedBarangs: '',
            barangs: [],
            barang:{
                kode:'',
                nama:'',
                qty:'',
                point:''
            },
            listBarang:[],
            list:[],
            listData:{},
            pencarian:'',
            isLoading: false,
            customers:[],
            perusahaans:[]
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
        this.getCustomer();
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

            if(this.barang.dos==""){
                alert('Dos barang harus diisi');

                return false;
            }

            if(this.barang.pcs==""){
                alert('PCS harus diisi');

                return false;
            }


            this.state.listBarang.push(
                {
                    // kd_barang:this.barang.kode.kd,
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
            this.barang.dos='';
            this.barang.pcs='';
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
</style>
