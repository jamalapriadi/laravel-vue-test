<template>
    <div class="row">

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header">Detail Piutang</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
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

                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Nomor Order</label>
                                <div class="col-lg-9">
                                    <select name="po" id="po" class="form-control" v-model="state.no_order" @change="changeOrder">
                                        <option value="" disabled selected>--Pilih Order--</option>
                                        <option v-for="(l,index) in pos" v-bind:key="index" v-bind:value="l.no_order">{{l.no_order}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">No. Pembayaran</label>
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
                        </div>
                    </div>

                    <br>

                    
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header">Detail Pembayaran</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="" class="control-label">Jumlah Pembayaran</label>
                            <input type="text" class="form-control" v-model="state.jumlah_pembayaran" @input="ubahJumlahPembayaran">
                        </div>
                        <div class="col-lg-3">
                            <button class="btn btn-primary" v-on:click="saveProgram" style="margin-top:30px;">
                                <i class="fa fa-save"></i>
                                Save
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <div style="float:right">
                                <h1 class="pull-right">
                                    <span style="font-size:18px;"><small>Sisa Hutang</small></span>
                                    {{state.sisa_hutang}}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <br>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Pembayaran</th>
                                <th>Tgl. Pembayaran</th>
                                <th>Jumlah Bayar</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(l,index) in barang" v-bind:key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{l.no_piutang}}</td>
                                <td>{{l.tgl_pembayaran}}</td>
                                <td>{{l.total_bayar}}</td>
                                <td>

                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <br>
            
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div class="form-group">
                        <router-link to="/list-piutang" class="btn btn-default">
                            <i class="fa fa-backward"></i> Back
                        </router-link>
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
                kode:'',
                no_order:'',
                customer:'',
                sisa_hutang:0,
                tanggal:new Date(),
                jumlah_pembayaran:0
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
            selectedBarangs: '',
            barangs: [],
            barang:[],
            listBarang:[],
            list:[],
            listData:{},
            pos:[],
            pencarian:'',
            isLoading: false,
            customers:[],
            carinamacustomer:'',
            caritokocustomer:'',
            listcaricustomer:[],
            listtokocustomer:[],
            
            
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
    },
    mounted() {
        this.getCode();
        // this.getCustomer();
        // this.getPerusahaan();
        // this.getNoPo("true");
        // this.getSales();
    },
    methods: {
        getNoPo(status){
            axios.get('/data/order-belum-lunas?customer='+this.state.customer)
                .then(response => {
                    if(response.data.success==true){
                        this.pos= response.data.data
                    }
                })
        },
        getCode(){
            axios.get('/data/autonumber-piutang')
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

        changeOrder(){
            axios.get('/data/pilih-order-di-new-po/'+this.state.no_order)
                .then(response => {
                    this.barang=response.data.order.piutang;

                    if(response.data.piutang.length>0){
                        this.state.sisa_hutang=response.data.piutang[0].sisa_hutang;
                    }else{
                        this.state.sisa_hutang=response.data.order.total;
                    }
                })
        },

        getCustomer(){
            axios.get('/data/list-customer')
                .then(response => {
                    this.customers = response.data;
                })
        },

        ubahJumlahPembayaran(){
            // var hasil=this.state.sisa_hutang - this.state.jumlah_pembayaran;

            // this.state.sisa_hutang=hasil;
        },

        saveProgram(){
            if(this.state.customer==""){
                alert('Customer harus diisi');

                return false;
            }

            if(this.state.no_order==""){
                alert('No Order harus diisi');

                return false;
            }

            this.loading = true;

            axios.post('/data/piutang', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state.kode='';
                        // this.state.no_order='';
                        // this.state.customer='';
                        this.state.sisa_hutang=0;
                        this.state.tanggal=new Date();
                        this.state.jumlah_pembayaran=0

                        this.getCode();
                        this.changeOrder();
                        this.message = 'Data has been saved.';
                        this.loading = false;
                    }else{
                        alert('Internal server error');
                    }
                })
        }
 
    },
    // computed:{
    //     totalQty: function() {
    //         return this.listBarang.reduce(function(a, c){
    //             return a + Number(c.dos || 0)
    //         }, 0)
    //     }
    // }
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
