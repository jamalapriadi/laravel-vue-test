<template>
    <div class="row">
        <div class="col-lg-3">
            <div class="card card-accent-primary">
                <div class="card-header">
                    Retur
                </div>
                <div class="card-body">
                    <!-- <div class="form-group">
                        <label for="" class="control-label">No. Retur</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                    </div> -->

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
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="form-group row">
                                        <label for="" class="control-label col-lg-5">Full Nota?</label>
                                        <div class="col-lg-7">
                                            <toggle-button :value="true" :labels="{checked: 'Yes', unchecked: 'No'}" v-model="state.full_nota" @change="ubahPoPending(state.full_nota)"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group row">
                                        <label for="" class="control-label col-lg-5">Lunas?</label>
                                        <div class="col-lg-7">
                                            <toggle-button :value="true" :labels="{checked: 'Yes', unchecked: 'No'}" v-model="state.lunas"/>
                                        </div>
                                    </div>
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

                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Nomor Order</label>
                                <div class="col-lg-9">
                                    <select name="po" id="po" class="form-control" v-model="state.no_order" @change="changeOrder">
                                        <option value="" disabled selected>--Pilih PO--</option>
                                        <option v-for="(l,index) in pos" v-bind:key="index" v-bind:value="l.no_order">{{l.no_order}}</option>
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

                            <div class="form-group row">
                                <label for="" class="control-label col-lg-3">Keterangan</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" :class="{ 'is-invalid': errors.keterangan }" v-model="state.keterangan">
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
                    <!-- <div v-show="state.tampil_order==false"> -->
                    <div v-show="state.full_nota==false">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="" class="control-label">Kode Barang</label>
                                    <select name="kode" id="kode" class="form-control" v-model="form.kd" @change="ubahBarang(form.kd,state.no_order)">
                                        <option value="">--Pilih Barang--</option>
                                        <option v-for="(p,index) in barang" v-bind:key="index" v-bind:value="p.kd_brg">{{p.kd_brg}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="" class="control">Nama Barang</label>
                                    <input type="text" class="form-control" v-model="form.nama" readonly>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="" class="control-label">Dos</label>
                                    <input type="text" class="form-control" name="dos" v-model="form.dos" readonly>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="" class="control-label">Pcs</label>
                                    <input type="text" class="form-control" name="pcs" v-model="form.pcs" readonly>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-grou">
                                    <label for="harga" class="control-label">Harga</label>
                                    <input type="text" class="form-control" v-model="form.harga" readonly>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-grou">
                                    <label for="" class="control-label">Diskon ( % )</label>
                                    <input type="text" class="form-control" v-model="form.diskon_persen" readonly>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="" class="control-label">Diskon ( Rp. )</label>
                                    <input type="text" class="form-control" v-model="form.diskon_rupiah" readonly>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="" class="control-label">Keterangan</label>
                                    <input type="text" class="form-control" name="dos" v-model="form.keterangan">
                                </div>
                            </div>
                        </div>

                        <fieldset>
                            <legend>Info Retur</legend>
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="" class="control-label">Jumlah Dos</label>
                                        <input type="text" class="form-control" v-model="form.return_dos">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="pcs" class="control-label">Jumlah Pcs</label>
                                        <input type="text" class="form-control" v-model="form.return_pcs">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-primary" style="margin-top:25px;"  v-on:click="saveBarang()">
                                        <i class="fa fa-plus"></i>
                                        Add
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Order</th>
                                <th>Kode / Nama Barang</th>
                                <th>Dos / PCS</th>
                                <th>Harga</th>
                                <th>Diskon (%)</th>
                                <th>Diskon (Rp.)</th>
                                <th>Return Dos</th>
                                <th>Return PCS</th>
                                <th>Keterangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(l,index) in state.barang" v-bind:key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{l.no_order}}</td>
                                <td>{{l.kd_barang}} - {{l.nm_barang}}</td>
                                <td>{{l.dos}} Dos / {{l.pcs}} Pcs</td>
                                <td>{{l.harga}}</td>
                                <td>{{l.diskon_persen}}</td>
                                <td>{{l.diskon_rupiah}}</td>
                                <td>{{l.return_dos}}</td>
                                <td>{{l.return_pcs}}</td>
                                <td>{{l.keterangan}}</td>
                                <td>
                                    <!-- <div v-if="tampilTambah[index]==true">
                                        <a class="btn btn-info text-white" v-on:click="tambahBarang(l)">Pilih Dari Rak Lain</a>
                                    </div> -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
            
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div v-if="message" class="alert alert-success">
                        {{ message }}
                    </div>

                    <div class="form-group">
                        <router-link to="/list-retur" class="btn btn-default">
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
                full_nota:true,
                lunas:true,
                no_order:'',
                tampil_order:false,
                kode:'',
                tanggal:new Date(),
                customer:'',
                keterangan:'',
                lokasi:'',
                kd_trans:'Tunai',
                barang:[]
            },
            date: new Date(),
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            }, 
            form:{
                no_order:'',
                kd:'',
                nama:'',
                harga:'',
                jumlah:0,
                dos:'',
                pcs:'',
                diskon_persen:'',
                diskon_rupiah:'',
                return_dos:0,
                return_pcs:0,
                total_pcs:0,
                pcs_barang:0,
                keterangan:''
            },
            pos:[],
            barang:[],
            message:'',
            loading:false,
            errors: [],
            value: null,
            tanggal: ['list', 'of', 'options'],
            kodetrans: ['Tunai','Kredit'],
            pencarian:'',
            isLoading: false,
            customers:[],
            lokasis:[],
            carinamacustomer:'',
            caritokocustomer:'',
            listcaricustomer:[],
            listtokocustomer:[]
            
            
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
        this.getLokasi();
    },
    methods: {
        rupiah(value) {
            let val = (value/1).toFixed().replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        
        getNoOrder(){
            axios.get('/data/list-order-client?customer='+this.state.customer+"&nota="+this.state.full_nota+"&lunas="+this.state.lunas)
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
            this.getNoOrder();
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

        ubahPoPending(ppending){
            this.state.barang=[];
            this.kosongBarang();
            this.state.full_nota=ppending;

            this.changeOrder();
        },

        ubahBarang(kd,no){
            axios.get('/data/info-barang-by-order?noorder='+no+"&kode="+kd)
                .then(response => {
                    if(response.data.data.length>0){
                        this.form.no_order=no;
                        this.form.kd=kd;
                        this.form.nama= response.data.data[0].nm;
                        this.form.dos=response.data.data[0].dos
                        this.form.pcs=response.data.data[0].pcs;
                        this.form.diskon_persen=response.data.data[0].diskon_persen;
                        this.form.diskon_rupiah=response.data.data[0].diskon_rupiah;
                        this.form.harga=response.data.data[0].hrg;
                        this.form.return_dos=0;
                        this.form.return_pcs=0;
                        this.form.jumlah=response.data.data[0].jumlah;
                        this.form.pcs_barang=response.data.data[0].pcs_barang;
                        this.form.total_pcs=response.data.data[0].total_pcs;
                    }
                })
        },

        changeOrder(){
            this.state.barang=[];

            if(this.state.full_nota==false){
                axios.get('/data/detail-order-by-id/'+this.state.no_order)
                    .then(response => {
                        // this.state.customer=response.data.order[0].nm;
                        this.state.lokasi=response.data.order[0].lokasi_id;
                        this.barang=response.data.detail;
                        this.changeLokasi();
                    })
            }else{
                axios.get('/data/detail-order-by-id/'+this.state.no_order)
                    .then(response => {
                        // this.state.customer=response.data.order[0].nm;
                        this.state.lokasi=response.data.order[0].lokasi_id;
                        this.barang=response.data.detail;
                        this.changeLokasi();

                        for(var a=0; a<this.barang.length; a++){
                            this.state.barang.push(
                                {
                                    no_order:this.state.no_order,
                                    kd_barang:this.barang[0].kd_brg,
                                    nm_barang:this.barang[0].nm,
                                    harga:this.barang[0].hrg,
                                    dos:this.barang[0].dos,
                                    pcs:this.barang[0].pcs,
                                    diskon_persen:this.barang[0].diskon_persen,
                                    diskon_rupiah:this.barang[0].diskon_rupiah,
                                    return_dos:this.barang[0].dos,
                                    return_pcs:this.barang[0].pcs,
                                    pcs_barang:this.barang[0].pcs_barang
                                }
                            );   
                        }
                    })
            }
            
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

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then(response => {
                    this.lokasis = response.data;
                })
        },

        saveBarang(){
            if(this.form.kode==""){
                alert('Barang harus diisi');

                return false;
            }

            if(this.form.return_dos > this.form.dos){
                alert('Jumlah dos tidak boleh melebihi jumlah yang di order');

                return false;
            }

            if(this.form.return_pcs > this.form.pcs){
                alert('Jumlah pcs tidak boleh melebihi jumlah yang di order');

                return false;
            }

            var statusnya="sama";
            var totalpcs=parseInt(this.form.return_dos*this.form.pcs_barang)+parseInt(this.form.return_pcs);

            if(totalpcs > this.form.total_pcs){
                alert('Jumlah barang retur tidak sesuai dengan jumlah barang dibeli');
            }else{
                if(this.form.total_pcs == totalpcs){
                    statusnya="sama";
                }else{
                    statusnya="kurang";
                }

                this.state.barang.push(
                    {
                        no_order:this.form.no_order,
                        kd_barang:this.form.kd,
                        nm_barang:this.form.nama,
                        harga:this.form.harga,
                        dos:this.form.dos,
                        pcs:this.form.pcs,
                        diskon_persen:this.form.diskon_persen,
                        diskon_rupiah:this.form.diskon_rupiah,
                        return_dos:this.form.return_dos,
                        return_pcs:this.form.return_pcs,
                        jumlah:this.form.jumlah,
                        statusnya:statusnya,
                        pcs_barang:this.form.pcs_barang,
                        keterangan:this.form.keterangan
                    }
                );
            }

            console.log(this.state.barang);

            this.kosongBarang();
        },

        kosongBarang(){
            this.form.no_order='';
            this.form.kd='';
            this.form.nama='';
            this.form.harga=0;
            this.form.pcs=0;
            this.form.dos=0;
            this.form.diskon_persen=0;
            this.form.diskon_rupiah=0;
            this.form.return_dos=0;
            this.form.return_pcs=0;
            this.$refs.tokocustomer.inputValue = '';
            this.$refs.namacustomer.inputValue = '';
        },

        deleteBarang: function(index) {
            this.state.listBarang.splice(index, 1);
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

            axios.post('/data/retur', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state.full_nota=true;
                        this.state.no_order='';
                        this.state.tampil_order=false;
                        this.state.kode='';
                        this.state.tanggal=new Date();
                        this.state.customer='';
                        this.state.lokasi='';
                        this.state.kd_trans='Tunai';
                        this.state.barang=[]

                        this.getCode();
                        this.getLokasi();
                        this.kosongBarang()

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
