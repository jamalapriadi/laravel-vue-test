<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Add New Storing
        </div>
        <div class="card-body">

            <div v-if="message" class="alert alert-success">
                {{ message }}
            </div>

            <!-- <input type="text" class="form-control" v-autofocus placeholder="Tes"> -->

            <form @submit.prevent="store" action="/data/program" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Nomor Storing</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">No. Ref</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" v-model="state.no_ref">
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

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Tanggal</label>
                            <div class="col-lg-9">
                                <date-picker v-model="state.tanggal" :config="options"></date-picker>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">No. Surat Jalan</label>
                            <div class="col-lg-9">
                                <input class="form-control" v-model="state.no_surat_jalan">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-lg-3">Merk</label>
                            <div class="col-lg-9">
                                <select class="form-control" name="merk" id="merk" v-model="state.merk">
                                    <option value="">--Pilih Merk--</option>
                                    <option v-for="(p,index) in merk" v-bind:key="index" v-bind:value="p.id">{{p.nm}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>

            <br><br>
            <div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="" class="control-label">Kode / Nama barang</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <vue-bootstrap-typeahead v-model="carikodebarang" :data="listkodebarang" placeholder="Kode Barang..." @hit="getKodeBarang($event)" ref="kodebarang"/>
                            </div>
                            <div class="col-lg-6">
                                <vue-bootstrap-typeahead v-autofocus v-model="carinamabarang" :data="listcaribarang" placeholder="Nama Barang..." @hit="getNamaBarang($event)" ref="namabarang"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">Rak</label>
                        <multiselect v-model="barang.rak" :options="raks" placeholder="Cari Rak" label="nm" track-by="kd"></multiselect>
                        
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">Dos</label>
                        <input type="text" class="form-control" v-model="barang.dos">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">PCS</label>
                        <input type="text" class="form-control" v-model="barang.pcs">
                    </div>

                    <div class="form-group col-md-2">
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
                        <th>Rak</th>
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
                        <td>{{l.nama_rak}}</td>
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
                <router-link to="/storing" class="btn btn-default">
                    <i class="fa fa-backward"></i> Back
                </router-link>

                <button class="btn btn-primary float-right" v-on:click="saveProgram">
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
                lokasi:'',
                no_ref:'',
                tanggal:new Date(),
                no_surat_jalan:'',
                merk:'',
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
                nama_rak:'',
                rak:'',
                dos:0,
                pcs:0
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
            raks:[],
            merk: [],
            dynamicValue:true
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
        this.setFocusNamaBarang();
        this.getCode();
        this.getCustomer();
        this.getLokasi();
        this.getMerk();
        // this.getPerusahaan();
    },
    methods: {
        setFocusNamaBarang(){
            this.$nextTick(() => {
                this.$refs.namabarang.$el.focus()
            })
        },

        getCode(){
            axios.get('/data/autonumber-storing')
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
            axios.get('/data/cari-barang-by-nama?q='+query+'&merk='+this.state.merk)
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
            axios.get('/data/cari-barang-by-nama?kode='+q+'&merk='+this.state.merk)
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
            
            for(var i=0; i< unique.length; i++){
                if(unique[i].nm == item){
                    nama=unique[i].kd;
                    pcs=unique[i].pcs;
                }
            }

            this.barang.nama=item;
            this.barang.kode=nama;
            this.barang.pcs=0;
            this.barang.dos=0;
            this.hasilpcs=pcs;
            this.$refs.kodebarang.inputValue = nama
        },

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then(response => {
                    this.lokasis = response.data;
                })
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
            this.barang.pcs=0;
            this.barang.dos=0;
            this.hasilpcs=pcs;
            this.$refs.namabarang.inputValue = nama;
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
        },

        changePcs(){
            this.barang.pcs=parseInt(this.barang.dos)*parseInt(this.hasilpcs);
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

        changeLokasi(){
            axios.get('/data/list-rak?lokasi='+this.state.lokasi)
                .then(response => {
                    this.raks=response.data;
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

            if(this.barang.rak==""){
                alert('Rak harus diisi');

                return false;
            }

            // if(this.barang.pcs==""){
            //     alert('PCS harus diisi');

            //     return false;
            // }


            this.state.listBarang.push(
                {
                    // kd_barang:this.barang.kode.kd,
                    kd_barang:this.barang.kode,
                    nm_barang:this.barang.nama,
                    rak:this.barang.rak.kd,
                    nama_rak:this.barang.rak.nm,
                    dos:this.barang.dos,
                    pcs:this.barang.pcs
                }
            );

            this.kosongBarang();

            this.dynamicValue = true
        },

        kosongBarang(){
            this.barang.kode='';
            this.barang.nama='';
            this.barang.rak='';
            this.barang.dos=0;
            this.barang.pcs=0;
            this.hasilpcs=0;
            this.$refs.kodebarang.inputValue = '';
            this.$refs.namabarang.inputValue = '';
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

        getMerk(){
            axios.get('/data/list-merk')
                .then(response => {
                    this.merk = response.data;
                })
        },

        saveProgram(){
            if(this.state.kode==""){
                alert('Kode harus diisi');

                return false;
            }

            if(this.state.lokasi==""){
                alert('Lokasi harus diisi');

                return false;
            }

            this.loading = true;

            axios.post('/data/storing', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state.kode='';
                        this.state.no_ref= '';
                        this.state.lokasi='';
                        this.state.no_surat_jalan=''
                        this.state.merk=''
                        this.state.tanggal=new Date();
                        // this.$refs.kodecustomer.inputValue = '';
                        // this.$refs.namacustomer.inputValue = '';
                        this.raks=[];
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
