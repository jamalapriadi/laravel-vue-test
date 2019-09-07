<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header">Detail Piutang</div>
                <div class="card-body">
                    <div v-if="message" class="alert alert-success">
                        {{ message }}
                    </div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group row">
                                <label for="" class="col-lg-3 control-label">Tanggal</label>
                                <div class="col-lg-6">
                                    <date-picker v-model="state.tanggal" :config="options"></date-picker>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <!-- <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Saldo</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" v-model="state.saldo" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Total Piutang</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" v-model="state.total_piutang_rupiah" readonly>
                                </div>
                            </div> -->

                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Keterangan</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" v-model="state.keterangan">
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
                            <a class="btn btn-primary text-white" @click="showModal" style="margin-top:25px;">
                                List Nota
                            </a>
                        </div>
                    </div>

                    <br>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Order</th>
                                <th>Jenis Pembayaran</th>
                                <th>Bank</th>
                                <th>No. CEK / BG</th>
                                <th>Tgl. JT / Transfer</th>
                                <th>Jumlah Tagihan</th>
                                <th>Bayar</th>
                                <th>Keterangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(l,index) in state.detail" v-bind:key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{l.no_order}}</td>
                                <td>{{l.jns_pembayaran}}</td>
                                <td>{{l.bank}}</td>
                                <td>{{l.no_cek_bg}}</td>
                                <td>{{format_date(l.tgl_jt)}}</td>
                                <td>Rp. {{rupiah(l.tagihan)}}</td>
                                <td>Rp. {{rupiah(l.nominal)}}</td>
                                <td>{{l.keterangan}}</td>
                                <td>
                                    <a @click="deleteBarang(index)" class="btn btn-danger text-white">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan='7'>Total</th>
                                <th>Rp. {{rupiah(state.total)}}</th>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <th colspan='7'>Jumlah Bayar</th>
                                <th>Rp. {{rupiah(state.nominal)}}</th>
                                <th colspan="2"></th>
                            </tr>
                            <tr v-if="state.nominal - state.total > 0">
                                <th colspan='7'>Kembali</th>
                                <th>Rp. {{rupiah(state.nominal - state.total)}}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>

                    <br>
            
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

        <b-modal ref="myModalRef" size="lg" hide-footer title="Cari Nota" id="modalku"  style="width:1250px;">
            <div class="form-group row">
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
                <div class="col-lg-3">
                    
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="" class="control-label">Jenis Pembayaran</label>
                        <select name="jnspembayaran" id="jnspembayaran" class="form-control" v-model="detail.jns_pembayaran">
                            <option value="Tunai">Tunai</option>
                            <option value="Cek">Cek</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group" v-if="detail.jns_pembayaran=='Transfer' || detail.jns_pembayaran=='Cek'">
                        <label for="" class="control-label">Bank</label>
                        <select name="bank" id="bank" class="form-control" v-model="detail.bank">
                            <option disabled selected>--Pilih Bank--</option>
                            <option v-for="(l,index) in banks" v-bind:key="index" v-bind:value="l.nm">{{l.nm}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group" v-if="detail.jns_pembayaran=='Cek'">
                        <label for="" class="control-label">No. Cek / BG</label>
                        <input type="text" class="form-control" v-model="detail.no_cek_bg" :readonly="detail.jns_pembayaran!='Transfer' ? false : true">
                    </div>
                </div>
            </div>

            <br>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>No. Nota</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Piutang</th>
                        <th>Sudah Dibayar</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th width="17%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l,index) in pos" v-bind:key="index" v-if="l.sudah_dibayar < l.total">
                        <td>{{index+1}}</td>
                        <td>{{l.kd_picking}}</td>
                        <td>{{l.nm}}</td>
                        <td>
                            <input type="date" class="form-control" v-model="tanggal[index]">
                        </td>
                        <td>{{l.total}}</td>
                        <td>{{l.sudah_dibayar}}</td>
                        <td>
                            <input type="text" class="form-control" v-model="nominal[index]">
                        </td>
                        <td>
                            <input type="text" class="form-control" v-model="keterangan[index]">
                        </td>
                        <td>
                            <a class="btn btn-info text-white" @click="pilih(l, index)">
                                Pilih
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            
        </b-modal>

    </div>
    
</template>

<script>
import { VueLoading } from 'vue-loading-template'
// Import required dependencies 
import 'bootstrap/dist/css/bootstrap.css';
import moment from 'moment'

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
                saldo:0,
                customer:'',
                total_piutang:0,
                total_piutang_rupiah:0,
                keterangan:'',
                total:0,
                nominal:0,
                tanggal:new Date(),
                detail:[]
            },
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            },
            banks:[],
            message:'',
            loading:false,
            errors: [],
            pos:[],
            detail:{
                jns_pembayaran:'Tunai',
                bank:'',
                no_cek_bg:'',
                no_order:'',
                total:0,
                total_rupiah:0,
                tgl_jt:new Date(),
                nominal:0,
                keterangan:''
            },
            carinamacustomer:'',
            caritokocustomer:'',
            listcaricustomer:[],
            listtokocustomer:[],
            barang:[],
            carinonota:'',
            listnonota:[],
            listCnonota:[],
            nominal:[],
            keterangan:[],
            tanggal:[]
            
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

        carinonota: _.debounce(function(q){
            this.cariNoNota(q);
        },500),
    },
    mounted() {
        this.getCode();
        this.getBank();
    },
    methods: {
        rupiah(value) {
            let val = (value/1).toFixed().replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

        showModal () {
            this.$refs.myModalRef.show()
        },

        hideModal () {
            this.$refs.myModalRef.hide()
        },

        pilih(l, index){
            console.log(l);
            if(this.nominal[index]==0){
                alert('Nominal harus lebih dari 0');
                return false;
            }

            if(this.state.detail.length>0){
                for(var a=0; a<this.state.detail.length; a++){
                    sisa+=this.state.detail[a].total;
                }
            }

            var jumlahbayar=this.nominal[index];
            var bayar=this.detail.total;

            var sisa=this.detail.total;
            if(this.state.detail.length>0){
                for(var a=0; a<this.state.detail.length; a++){
                    if(this.state.detail[a].no_order == l.no_order){
                        this.state.detail.splice(a,1);
                    }
                }
            }
            
            // alert(sisa)
            
            if(jumlahbayar >  sisa){
                bayar=this.detail.total;
            }else{
                bayar=this.state.nominal - this.state.total;
            }

            this.state.detail.push(
                {
                    jns_pembayaran:this.detail.jns_pembayaran,
                    bank:this.detail.bank,
                    no_cek_bg:this.detail.no_cek_bg,
                    no_order:l.no_order,
                    total:l.total,
                    // tgl_jt:l.tgljt,
                    tgl_jt:this.tanggal[index],
                    tagihan:l.total,
                    nominal:this.nominal[index],
                    keterangan:this.keterangan[index]
                }
            )

            var total=0;
            var newpos=[];
            for(var a=0; a<this.state.detail.length; a++){
                total+=this.state.detail[a].total;
            }

            this.hitungTotal()
        },

        getOrderBelumLunasCostomer(nama){
            axios.get('/data/order-belum-lunas?customer='+nama)
                .then(response => {
                    if(response.data.success==true){
                        this.pos= response.data.data

                        for(var a=0; a < response.data.data.length; a++){
                            this.nominal[a]=0;
                            this.keterangan[a]="";
                        }
                    }
                })
        },
        format_date(value){
            if (value) {
                return moment(String(value)).format('DD-MM-YYYY')
            }
        },
        getTotalHutangCustomer(){
            this.state.saldo=0;
            this.state.total_piutang_rupiah=0;

            axios.get('/data/tampil-hutang-customer?customer='+this.state.customer)
                .then(response => {
                    if(response.data.success==true){
                        if(response.data.data.length > 0){
                            this.state.total_piutang=response.data.data[0].total_hutang;
                            this.state.total_piutang_rupiah=this.rupiah(response.data.data[0].total_hutang);
                        }
                    }
                })
        },
        getBank(){
            axios.get('/data/list-bank')
                .then(response => {
                    this.banks=response.data;
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
                                nm_toko:response.data[i].nm_toko,
                                saldo:response.data[i].saldo
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
                                nm_toko:response.data[i].nm_toko,
                                saldo:response.data[i].saldo
                            }
                        );
                    }
                })
        },

        async cariNoNota(q){
            this.listnonota=[];
            this.listCnonota=[];
            axios.get('/data/list-order-hutang?q='+q)
                .then(response => {
                    for(var i=0; i< response.data.length; i++){
                        this.listnonota.push(response.data[i].no_order);

                        this.listCnonota.push(
                            {
                                no_order:response.data[i].no_order,
                                nm:response.data[i].nm,
                                nm_toko:response.data[i].nm_toko,
                                customer_id:response.data[i].customer_id,
                                saldo:response.data[i].saldo
                            }
                        );
                    }
                })
        },

        getNamaCustomer(item){
            let uniqueCus = [...new Set(this.listCCustomer)]; 
            var nama="";
            var toko="";
            var saldo=0;
            
            for(var i=0; i< uniqueCus.length; i++){
                if(uniqueCus[i].nm == item){
                    nama=uniqueCus[i].kd;
                    toko=uniqueCus[i].nm_toko;
                    saldo=uniqueCus[i].saldo;
                }
            };

            this.$refs.tokocustomer.inputValue = toko
            this.state.customer=nama;
            this.state.saldo=saldo;
            this.getOrderBelumLunasCostomer(nama);
            this.getTotalHutangCustomer();
        },

        getTokoCustomer(item){
            let uniqueCus = [...new Set(this.listCCustomer)]; 
            var nama="";
            var kode="";
            
            for(var i=0; i< uniqueCus.length; i++){
                if(uniqueCus[i].nm_toko == item){
                    nama=uniqueCus[i].nm;
                    kode=uniqueCus[i].kd;
                    saldo=uniqueCus[i].saldo;
                }
            };
            
            this.$refs.namacustomer.inputValue = nama
            this.state.customer=kode;
            this.state.saldo=saldo;
            this.getOrderBelumLunasCostomer(kode);
            this.getTotalHutangCustomer();
        },

        getNoNota(item){
            let uniqueCus = [...new Set(this.listCnonota)]; 
            var no_order="";
            var nm="";
            var nm_toko="";
            var customer_id="";
            var saldo="";
            
            for(var i=0; i< uniqueCus.length; i++){
                if(uniqueCus[i].no_order == item){
                    no_order=uniqueCus[i].no_order;
                    nm=uniqueCus[i].nm;
                    nm_toko=uniqueCus[i].nm_toko;
                    customer_id=uniqueCus[i].customer_id;
                    saldo=uniqueCus[i].saldo;
                }
            };
            
            this.$refs.namacustomer.inputValue = nm
            this.$refs.tokocustomer.inputValue = nm_toko
            this.state.customer=customer_id;
            this.state.saldo=saldo;
            this.detail.no_order=no_order;
            this.getOrderBelumLunasCostomer(customer_id);
            this.getTotalHutangCustomer();
            this.changeOrder();
        },

        changeOrder(){
            axios.get('/data/order-by-id/'+this.detail.no_order)
                .then(response => {
                    this.detail.total=response.data.total_hutang;
                    this.detail.total_rupiah=this.rupiah(response.data.total_hutang);
                })
        },

        kosong(){
            // this.detail.jns_pembayaran=''
            this.detail.bank=''
            this.detail.no_cek_bg=''
            this.detail.no_order=''
            this.detail.keterangan=''
            this.detail.total=0
            this.detail.total_rupiah=0;
            this.detail.tgl_jt=new Date()
            this.detail.nominal=0
        },

        saveBarang(){
            if(this.state.nominal==0){
                alert('Nominal harus lebih dari 0');
                return false;
            }

            // if(this.detail.total > this.state.nominal){
            //     alert('Jumlah melebihi data yang dibayar')
            //     return false;
            // }

            // if(this.detail.no_order==""){
            //     alert('No Order harus diisi');
            //     return false;
            // }
            var jumlahbayar=this.state.nominal;
            var bayar=this.detail.total;

            var sisa=this.detail.total;
            if(this.state.detail.length>0){
                for(var a=0; a<this.state.detail.length; a++){
                    sisa+=this.state.detail[a].total;
                }
            }
            
            // alert(sisa)
            
            if(jumlahbayar >  sisa){
                bayar=this.detail.total;
            }else{
                bayar=this.state.nominal - this.state.total;
            }

            this.state.detail.push(
                {
                    jns_pembayaran:this.detail.jns_pembayaran,
                    bank:this.detail.bank,
                    no_cek_bg:this.detail.no_cek_bg,
                    no_order:this.detail.no_order,
                    total:this.detail.total,
                    tgl_jt:this.detail.tgl_jt,
                    tagihan:this.detail.total,
                    nominal:bayar,
                    keterangan:this.detail.keterangan
                }
            )

            var total=0;
            var newpos=[];
            for(var a=0; a<this.state.detail.length; a++){
                total+=this.state.detail[a].total;
                // for(var b=0; b<this.pos.length; b++){
                //     if(this.pos[b].no_order != this.state.detail[b].no_order){
                //         newpos=this.pos[b];
                //     }
                // }
            }

            // this.pos=newpos;

            // if(total > this.state.nominal){
            //     this.state.detail.splice(this.detail, 1);
            //     alert('Total melebihi data yang dibayar')
            //     return false;
            // }

            this.hitungTotal()
            this.kosong()
        },

        hitungTotal(){
            var total=0;
            for(var a=0; a<this.state.detail.length; a++){
                total+=parseInt(this.state.detail[a].nominal);
            }
            this.state.total=parseInt(total);
        },

        changeNota(no_nota){
            
        },

        deleteBarang: function(index) {
            this.state.detail.splice(index, 1);
            this.getOrderBelumLunasCostomer(this.state.customer);

            var newpos=[];
            for(var a=0; a<this.state.detail.length; a++){
                total+=this.state.detail[a].total;
                for(var b=0; b<this.pos.length; b++){
                    if(this.pos[b].no_order != this.state.detail[a].no_order){
                        newpos=this.pos[a];
                    }
                }
            }

            this.pos=newpos;

            this.hitungTotal()
        },

        saveProgram(){
            if(this.state.customer==""){
                alert('Customer harus diisi');

                return false;
            }

            // if(this.state.no_order==""){
            //     alert('No Order harus diisi');

            //     return false;
            // }

            this.loading = true;

            axios.post('/data/piutang', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state.kode='';
                        this.state.saldo=0;
                        this.state.customer='';
                        this.state.keterangan='';
                        this.state.total_piutang=0;
                        this.state.total_piutang_rupiah=0;
                        this.state.nominal=0;
                        this.state.total=0;
                        this.state.tanggal=new Date();
                        this.state.detail=[]
                        this.pos=[]
                        this.$refs.namacustomer.inputValue = ""
                        this.$refs.tokocustomer.inputValue = ""

                        this.getCode();
                        this.getBank();
                        this.message = 'Data has been saved.';
                        this.loading = false;
                    }else{
                        alert('Internal server error');
                    }
                })
        },

        tampilListBarang(){

        },
 
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

    #modalku{
        width: 90%; 
    }

    .modal-lg {
        max-width: 80% !important;
        margin: 30px auto;
    }
</style>
