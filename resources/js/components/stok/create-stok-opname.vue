<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Add New Stok Opname
        </div>
        <div class="card-body">

            <div v-if="message" class="alert alert-success">
                {{ message }}
            </div>
            
            <form @submit.prevent="store" action="/data/program" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Nomor SO</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control"  v-model="state.kode" readonly>
                            </div>
                        </div>

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
            </form>


            <br>
            <div v-if="state.lokasi">
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
                        <multiselect v-model="barang.rak" :options="raks" placeholder="Cari Rak" label="nm" track-by="kd" @input="changeRak(barang.rak)"></multiselect>
                        
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">Qty Prog</label>
                        <input type="number" class="form-control" v-model="barang.stok">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">Qty STO</label>
                        <input type="number" class="form-control" v-model="barang.sto">
                    </div>

                    <div class="form-group col-md-2">
                        <div class="btn-group">
                            <button v-on:click="saveBarang()" class="btn btn-primary" style="margin-top:25px;">
                                <i class="fa fa-plus"></i>
                                Add
                            </button>
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
                        <!-- <th>Harga</th> -->
                        <th>Rak</th>
                        <th>Qty Prog</th>
                        <th>Qty SO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                        <td>{{ index+1 }}</td>
                        <td>{{l.kd_barang}}</td>
                        <td>{{l.nm_barang}}</td>
                        <!-- <td>{{l.harga}}</td> -->
                        <td>{{l.rak}} - {{l.nama_rak}}</td>
                        <td>{{l.stok}}</td>
                        <td>{{l.sto}}</td>
                        <!-- <td>
                            <input class="form-control" v-model="state.prog[index]">
                        </td> -->
                    </tr>
                </tbody>
            </table>

            
            <hr>
            
            <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

            <div class="form-group">
                <router-link to="/stok-opname" class="btn btn-default">
                    <i class="fa fa-backward"></i> Back
                </router-link>

                <button class="btn btn-primary float-right" v-on:click="saveProgram">
                    <i class="fa fa-save"></i>
                    Save
                </button>
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
                tanggal:new Date(),
                lokasi:'',
                keterangan:'',
                listBarang:[],
                prog:[],
            },
            date: new Date(),
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            }, 
            message:'',
            loading:false,
            listBarang:[],
            lokasis:[],
            carinamabarang:'',
            carikodebarang:'',
            listkodebarang:[],
            listcaribarang:[],
            raks:[],
            barang:{
                kode:'',
                nama:'',
                nama_rak:'',
                rak:'',
                stok:0,
                sto:0
            },
        }
    },
    watch: {
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
    },
    methods: {
        async cariBarangByNama(query){
            this.listcaribarang=[];
            this.listCBarang=[];
            let result=[];
            axios.get('/data/cari-barang-by-nama?q='+query+'&lokasi='+this.state.lokasi)
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
            axios.get('/data/cari-barang-by-nama?kode='+q+'&lokasi='+this.state.lokasi)
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
            this.barang.stok=0;
            this.hasilpcs=pcs;
            this.$refs.namabarang.inputValue = nama;
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
            this.barang.stok=0;
            this.hasilpcs=pcs;
            this.$refs.kodebarang.inputValue = nama
        },

        getCode(){
            axios.get('/data/autonumber-stok-opname')
                .then(response => {
                    this.state.kode = response.data;
                })
        },

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then(response => {
                    this.lokasis = response.data;
                })
        },

        changeLokasi(){
            // axios.get('/data/stok-opname-by-lokasi/'+this.state.lokasi)
            //     .then(response => {
            //         // this.raks=response.data;
            //         this.state.listBarang=response.data;
            //     })
            axios.get('/data/list-rak?lokasi='+this.state.lokasi)
                .then(response => {
                    this.raks=response.data;
                })
        },

        changeRak(rak){
            console.log(rak.kd)
            axios.get('/data/cek-stok-by-rak/'+rak.kd+'?barang='+this.barang.kode)  
                .then(response => {
                    this.barang.stok=response.data
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


            this.state.listBarang.push(
                {
                    // kd_barang:this.barang.kode.kd,
                    kd_barang:this.barang.kode,
                    nm_barang:this.barang.nama,
                    rak:this.barang.rak.kd,
                    nama_rak:this.barang.rak.nm,
                    stok:this.barang.stok,
                    sto:this.barang.sto
                }
            );

            this.kosongBarang();
        },

        kosongBarang(){
            this.barang.kode='';
            this.barang.nama='';
            this.barang.rak='';
            this.barang.stok=0;
            this.barang.sto=0;
            this.hasilpcs=0;
            this.$refs.kodebarang.inputValue = '';
            this.$refs.namabarang.inputValue = '';
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

            this.loading = true;

            axios.post('/data/stok-opname', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state= {
                            kode:'',
                            tanggal:new Date(),
                            lokasi:'',
                            keterangan:'',
                            listBarang:[],
                            prog:[],
                        },

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
