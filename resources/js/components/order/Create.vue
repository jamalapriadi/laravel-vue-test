<template>
    <div class="card card-default">
        <div class="card-heading">

        </div>
        <div class="card-body">

            <div v-if="message" class="alert alert-success">
                {{ message }}
            </div>


            <div class="row">
                <!-- <div class="col-lg-6">
                    <div class="form-group">
                        <label for="" class="control-label">Nomor Order</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                    </div>
                </div> -->

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="" class="control-label">Tanggal</label>
                        <date-picker v-model="state.tanggal" :config="options"></date-picker>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="" class="control-label">Kd. Picking</label>
                        <select name="kd_picking" id="kd_picking" class="form-control" v-model="state.kd_picking" @change="ubahPicking">
                            <option value="" disabled selected>--Pilih Kode Picking--</option>
                            <option v-for="(l,index) in pickings" v-bind:key="index" v-bind:value="l.kd_picking">{{l.kd_picking}} - {{l.nm_toko}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">Sales</label>
                        <select name="sales" id="sales" class="form-control" v-model="state.sales">
                            <option value="" disabled selected>--Pilih Sales--</option>
                            <option v-for="(l, index) in saless" v-bind:key="index"  v-bind:value="l.id">{{l.id}} - [{{l.nm}}]</option>
                        </select>
                    </div>  
                </div>

                <div class="col-lg-6">

                    <div class="form-group">
                        <label for="" class="control-label">Kode Transaksi</label>
                        <select name="kd_trans" id="kd_trans" class="form-control" v-model="state.kd_trans" @change="ubahKdTrans(state.kd_trans)">
                            <option v-for="(l,index) in kodetrans" v-bind:value="l" v-bind:key="index">{{l}}</option>
                        </select>
                    </div>

                        <!-- v-if="state.kd_trans == 'Kredit'" -->
                    <div class="form-group">
                        <label for="" class="control-label">Tanggal Jatuh Tempo</label>
                        <date-picker v-model="state.tanggaljt" :config="options"></date-picker>
                    </div>
                </div>
            </div>

            <div class="card card-default" v-show="tampilDetail">
                <div class="card-header">Detail Picking</div>
                <div class="card-body">
                    <table class="table">
                        <!-- <thead> -->
                            <tr>
                                <th>No. PO</th>
                                <td>{{state.no_po}}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Picking</th>
                                <td>{{state.tanggal}}</td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td>{{state.customer}}</td>
                            </tr>
                            <!-- <tr>
                                <th>Kode Transaksi</th>
                                <td>{{state.kd_trans}}</td>
                            </tr> -->
                            <tr>
                                <th>Lokasi</th>
                                <td>{{state.lokkasiname}}</td>
                            </tr>
                        <!-- </thead> -->
                    </table>

                    <br>
                    <a class="btn btn-primary text-white" v-show="tambah == false" @click="tambahItem">
                        <i class="fa fa-plus"></i> Add New 
                    </a>

                    <div v-show="tambah == true">
                        <fieldset>
                            <legend>Tambah Barang</legend>
                            
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="" class="control-label">Nama Barang</label>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <vue-bootstrap-typeahead v-model="carinamabarang" :data="listcaribarang" placeholder="Cari Barang..." @hit="getNamaBarang($event)" ref="namabarang"/>
                                            stok <strong>{{newbarang.stok}}</strong> PCS
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="" class="control-label">Dos</label>
                                    <input type="text" class="form-control" v-model="newbarang.dos" @keyup.enter="changeDos" @input="hitungTotal($event)">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="" class="control-label">PCS</label>
                                    <input type="text" class="form-control" v-model="newbarang.pcs" @keyup.enter="changePcs(newbarang.pcs)" @input="hitungTotalPcs($event)">
                                    <p>
                                        <small>
                                            {{hasilpcs}} jumlah per 1 dos
                                        </small>
                                    </p>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="" class="control-label">Total Pcs</label>
                                    <input type="text" class="form-control" v-model="newbarang.total_pcs" readonly>
                                </div>


                                <div class="form-group col-md-1">
                                    <div class="btn-group">
                                        <button v-on:click="saveBarang()" class="btn btn-primary" style="margin-top:25px;">
                                            <i class="fa fa-plus"></i>
                                            Add
                                        </button>

                                        <a @click="batalTambahBarang" class="btn btn-info text-white" style="margin-top:25px;">
                                            <i class="fa fa-cancel"></i> Batal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                        
                    <br><br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode / Nama Barang</th>
                                <th>Dos / Pcs</th>
                                <th width='15%'>Harga</th>
                                <th width='11%'>Jumlah</th>
                                <th width='30%' colspan='2'>Diskon</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(l,index) in hitungan" v-bind:key="index">
                                <td>{{ index+1 }}</td>
                                <td>{{l.kd_brg}} / {{l.nm}}</td>
                                <td>{{l.dos}} / {{l.pcs}}</td>
                                <td>
                                    <!-- <input type="text" class="form-control" v-model="state.jualhit[index]"  @input="hitungJualHit($event,index)"> -->
                                    <money v-model="state.jualhit[index]" v-bind="money" @input="hitungJualHit($event,index)"></money>
                                </td>
                                <td>
                                    <input type="number" class="form-control" v-model="state.jumlahhit[index]">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" class="form-control" v-model="state.diskon_persen[index]" @keyup.enter="ubahJumlahDiskonPersen(index)" @input="hitungTotal($event, index)" placeholder="%">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                %
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" class="form-control" v-model="state.diskon_rupiah[index]" @keyup.enter="ubahJumlahDiskonRupiah(index)" @input="hitungTotalRupiah($event, index)">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                %
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" class="form-control" v-model="state.subtotal[index]" readonly>
                                    <!-- <money v-model="state.subtotal[index]" v-bind="money"></money> -->
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7">SUBTOTAL</th>
                                <th>Rp. {{rupiah(subtotal)}}</th>
                            </tr>
                            <tr>
                                <th colspan="7">DISKON</th>
                                <!-- <th>Rp. {{rupiah(diskon)}}</th> -->
                                <th>
                                    <!-- <input class="form-control" v-model="state.diskon_tambahan" @input="hitungDiskonTambahan()"> -->
                                    <money v-model="state.diskon_tambahan" v-bind="money" @input="hitungDiskonTambahan()"></money>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="7">TOTAL</th>
                                <th>Rp. {{rupiah(state.total)}}</th>
                            </tr>
                        </tfoot>
                    </table>

                    <hr>
            
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <router-link to="/list-picking" class="btn btn-default">
                                    <i class="fa fa-backward"></i> Back
                                </router-link>
                                <button class="btn btn-danger" v-on:click="batalOrder">
                                    <i class="fa fa-trash"></i>
                                    Batal
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="float-right">
                                <button class="btn btn-primary" v-on:click="saveProgram">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>

            <!-- SOURCE -->
            <!-- print -->
            <div id="printMe" style="margin-top:10px;display:none;size: landscape;">
                <div class="container">
                    <br><br>
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="form-group row" style="margin-top:10px;">
                                <label for="" class="control-label">{{user.perusahaan.nama}}</label>
                            </div>
                            <div class="form-group row" style="margin-top:-20px;">
                                <label for="" class="control-label">{{user.perusahaan.ket.no_hp}}</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h3 class="text-center">NOTA PENJUALAN</h3>
                        </div>
                        <div class="col-lg-3 col-md-3" style="margin-top:10px;">
                            <div class="form-group row">
                                <label for="" class="control-label">Sales : {{dataprint.sales}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group row">
                                <label for="" class="control-label">Nomor</label>
                                <div class="col-lg-9 col-md-9">
                                    : {{dataprint.no_order}}
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top:-20px;">
                                <label for="" class="control-label">Tanggal</label>
                                <div class="col-lg-9 col-md-9">
                                    : {{dataprint.tanggal}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group row">
                                <label for="" class="control-label">Customer</label>
                                <div class="col-lg-9 col-md-9">
                                    {{dataprint.customer.nm}}
                                    <!-- <br>
                                    {{dataprint.customer.nm_toko}} -->
                                    <br>
                                    {{dataprint.customer.alamat}}
                                    <br>
                                    {{dataprint.customer.tlpn}}
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Dos</th>
                                    <th>PCS</th>
                                    <th>Harga</th>
                                    <th>Diskon 1 (%)</th>
                                    <th>Diskon 2 (%)</th>
                                    <!-- <th>Discount</th> -->
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(l,index) in dataprint.detail" v-bind:key="index">
                                    <td>{{l.nm}}</td>
                                    <td>{{l.pivot.dos}}</td>
                                    <td>{{l.pivot.pcs}}</td>
                                    <td>{{l.pivot.hrg | formatNumber}}</td>
                                    <td>{{l.pivot.diskon_persen}} %</td>
                                    <td>{{l.pivot.diskon_persen_2}} %</td>
                                    <!-- <td>{{l.pivot.diskon_rupiah}}</td> -->
                                    <td>{{l.pivot.subtotal | formatNumber}}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th rowspan="2">{{dataprint.update_at}}</th>
                                    <th rowspan="2">{{user.username}}</th>
                                    <th colspan="4">DISKON TAMBAHAN</th>
                                    <th>{{dataprint.diskon_rupiah | formatNumber}}</th>
                                </tr>
                                <tr>
                                    <th colspan="4">TOTAL</th>
                                    <th>{{dataprint.total | formatNumber}}</th>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group row">
                                <label for="" class="control-label">No. HP</label>
                                <div class="col-lg-9 col-md-9">
                                    : {{user.perusahaan.ket.no_hp}} (WHATSAPP)
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="control-label">Email</label>
                                <div class="col-lg-9 col-md-9">
                                    : {{user.perusahaan.ket.email}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="" class="control-label">Penerima</label>
                                        <br>
                                        <br>
                                        <br>
                                        {{dataprint.customer.nm}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="" class="control-label">Hormat Kami</label>
                                        <br>
                                        <br>
                                        <br>
                                        ......................
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    <!-- OUTPUT -->

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
import {Money} from 'v-money'

var numeral = require("numeral");

Vue.filter("formatNumber", function (value) {
    return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});


export default {
    components: {
        VueLoading,
        datePicker,
        Multiselect,
        VueBootstrapTypeahead,
        Money 
    },
    data() {
        return {
            money: {
                decimal: ',',
                thousands: '.',
                // prefix: 'Rp. ',
                // suffix: ' #',
                precision: 0,
                masked: false
            },
            tambah:false,
            detailPicking:[],
            state: {
                kd_picking:'',
                kd_customer:'',
                kode:'',
                nama: '',
                customer:'',
                tanggal:new Date(),
                tanggaljt:new Date(),
                perusahaan:'',
                keterangan:'',
                sales:'',
                lokkasiname:'',
                lokasiid:'',
                customertop:'',
                total:0,
                kd_trans:'Tunai',
                listBarang:[],
                kodes:[],
                kodehit:[],
                jual:[],
                jumlah:[],
                diskon_persen:[],
                diskon_rupiah:[],
                subtotal:[],
                dos:[],
                pcs:[],
                saless:[],
                lokasi:[],
                rak:[],
                stokid:[],
                doshit:[],
                pcshit:[],
                jualhit:[],
                jumlahhit:[],
                idstok:[],
                diskon_tambahan:0,
                status:[]
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
            barang:{
                kode:'',
                nama:'',
                harga:'',
                dos:'',
                pcs:'',
                diskon:'',
                jumlah:''
            },
            hitungan:{
                kd_brg:'',
                harga:'',
                jumlah:'',
                dos:'',
                pcs:'',
                diskon_persen:'',
                diskon_rupiah:'',
                status:'old'
            },
            listBarang:[],
            list:[],
            listData:{},
            pencarian:'',
            isLoading: false,
            customers:[],
            perusahaans:[],
            saless:[],
            lokasis:[],
            pickings:[],
            tampilDetail:false,
            subtotal:0,
            diskon:0,
            dataprint:{
                update_at:'',
                perusahaan:'',
                telp:'',
                sales:'',
                no_order:'',
                tanggal:'',
                tanggaljt:'',
                customer:{},
                datail:[],
                diskon_rupiah:0,
                total:0,
                kd_trans:'',
                kd_picking:'',
                status_pembayaran:'',
                detail:[],
                total:0,
                keterangan:{}
            },
            output:null,
            user:{
                username:'',
                perusahaan:{
                    ket:{
                        no_hp:'',
                        email:''
                    }
                }
            },
            listcaribarang:[],
            listCBarang:[],
            carinamabarang:'',
            newbarang:{
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
            hasilpcs:0,
        }
    },
    created(){
        axios.get('/data/user')
            .then(response => {
                this.user={
                    username: response.data.username,
                    perusahaan: response.data.perusahaan
                }
            })
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
    },
    mounted() {
        this.getCode();
        this.getPicking();
        // this.getCustomer();
        // this.getPerusahaan();
        this.getSales();
        // this.getLokasi();
    },
    methods: {
        batalTambahBarang(){
            this.tambah= false

            this.newbarang={
                kode:'',
                nama:'',
                qty:'',
                point:'',
                dos:0,
                pcs:0,
                total_pcs:0,
                harga:0,
                stok:0
            }

            this.$refs.namabarang.inputValue = ''
        },
        async cariBarangByNama(query){
            this.listcaribarang=[];
            this.listCBarang=[];
            let result=[];
            axios.get('/data/cari-barang-in-picking/'+this.state.kd_picking+'?q='+query+"&lokasi="+this.state.lokasiid)
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

            this.newbarang.kode=nama;
            this.newbarang.nama=item;
            this.newbarang.pcs=pcs;
            this.newbarang.dos=0;
            this.newbarang.pcs=0;
            this.newbarang.total_pcs=0;
            this.newbarang.harga=jual;
            this.hasilpcs=pcs;
            this.newbarang.stok=stok;
            // this.carinamabarang=nama;
            // this.$refs.kodebarang.inputValue = nama
        },

        rupiah(value) {
            let val = (value/1).toFixed().replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },

        tambahItem(){
            this.tambah = true
        },

        print(){
            this.$htmlToPaper('printMe');
        },

        getCode(){
            axios.get('/data/autonumber-order')
                .then(response => {
                    this.state.kode = response.data;
                })
        },

        ubahKdTrans(kode){
            var date = new Date();

            if(kode=="Kredit"){
                date.setDate(date.getDate() + this.state.customertop);
                this.state.tanggaljt=new Date(date);
            }
        },

        backEndDateFormat: function(date) {
            return moment(date, 'DD/MM/YYYY').format('YYYY-MM-DD');
        },

        ubahPicking(){
            this.hitungan=[];
            this.barangs=[];
            this.state.kodehit=[];
            this.state.doshit=[];
            this.state.pcshit=[];
            this.state.stokid=[];
            this.state.idstok=[];
            this.state.kodes=[];
            this.state.jumlah=[];
            this.state.jumlahhit=[];
            this.state.jualhit=[];
            this.state.subtotal=[];
            this.state.diskon_persen=[];
            this.state.diskon_rupiah=[];
            this.state.status=[]

            axios.get('/data/picking/'+this.state.kd_picking)
                .then(response => {
                    this.detailPicking = response.data;
                    this.tampilDetail=true;
                    console.log(this.detailPicking);
                    this.state.no_po=response.data.no_po;
                    this.state.tanggal=response.data.tgl;
                    this.state.customer=response.data.po.customer.nm;
                    this.state.customertop=response.data.po.customer.top;
                    this.state.kd_trans='Tunai';
                    this.state.lokkasiname=response.data.po.lokasi.nm;
                    this.state.lokasiid=response.data.po.lokasi_id;
                    // this.state.sales=response.data.sales.nm;
                    this.barangs=response.data.detail;
                    this.hitungan = response.data.hitungan;
                    this.state.kd_customer= response.data.po.customer_id;
                    this.subtotal=0;
                    this.state.total=0;
                    this.state.diskon_tambahan=0;

                    for(var c=0; c < this.hitungan.length; c++){
                        this.state.kodehit.push(this.hitungan[c].kd_brg);
                        this.state.doshit.push(this.hitungan[c].dos);
                        this.state.pcshit.push(this.hitungan[c].pcs);
                        this.state.jumlahhit[c]=this.hitungan[c].jumlah;
                        this.state.jualhit[c]=this.hitungan[c].harga;
                        this.state.subtotal[c]=parseFloat(this.hitungan[c].subtotal);
                        this.state.diskon_persen[c]=0;
                        this.state.diskon_rupiah[c]=0;
                        this.state.status.push(this.hitungan[c].status);
                        // this.ubahJumlah(c);
                    }

                    for (var i = 0; i < this.barangs.length; i++) {
                        this.state.stokid.push(this.barangs[i].pivot.id);
                        this.state.idstok.push(this.barangs[i].pivot.stok_id);
                        this.state.kodes.push(this.barangs[i].kd);
                        // this.state.jual.push(this.barangs[i].jual);
                        // this.state.dos.push(this.barangs[i].pivot.dos);
                        // this.state.pcs.push(this.barangs[i].pivot.pcs);
                        // this.state.rak.push(this.barangs[i].pivot.kd_rak);
                        this.state.jumlah.push(this.barangs[i].total);
                        
                        
                        // this.state.subtotal.push(this.barangs[i].jual);

                        // this.state.jual[i]=this.barangs[i].jual;

                        
                    }

                    console.log(this.state.rak);

                    this.total();
                    this.subtotalnya();
                    this.diskonnya();
                })
        },

        getSales(){
            axios.get('/data/list-sales')
                .then(response => {
                    this.saless = response.data;
                })
        },


        getPicking(){
            axios.get('/data/list-picking-not-in-order')
                .then(response => {
                    this.pickings = response.data;
                })
        },

        ubahJumlah(index){
            this.state.subtotal[index]=parseFloat(this.state.jual[index]) * parseFloat(this.state.jumlah[index]);

            this.total();
            // this.subtotalnya();
            // this.diskonnya();
        },

        ubahJumlahDiskonPersen(index){
            var jumlah=parseFloat(this.state.jumlahhit[index]);
            var harga=parseFloat(this.state.jualhit[index])
            var subtotal=harga * jumlah;
            var diskon_pertama=parseFloat(this.state.diskon_persen[index]);
            var diskon_kedua=parseFloat(this.state.diskon_rupiah[index]);
            var nilai_diskon_pertama=parseFloat(subtotal * diskon_pertama / 100);
            var hasil_hitung_diskon_pertama=parseFloat(subtotal - nilai_diskon_pertama);
            var hasil_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama * diskon_kedua / 100);
            var hasil_hitung_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama - hasil_diskon_kedua);
            var hasil_akhir=parseFloat(hasil_hitung_diskon_kedua);

            
            
            this.state.subtotal[index]=parseInt(hasil_hitung_diskon_kedua);

            this.total();
            // this.subtotalnya();
            // this.diskonnya();
        },

        hitungTotal(event, index){
            var jumlah=parseFloat(this.state.jumlahhit[index]);
            var harga=parseFloat(this.state.jualhit[index])
            var subtotal=harga * jumlah;
            var diskon_pertama=parseFloat(this.state.diskon_persen[index]);
            var diskon_kedua=parseFloat(this.state.diskon_rupiah[index]);
            var nilai_diskon_pertama=parseFloat(subtotal * diskon_pertama / 100);
            var hasil_hitung_diskon_pertama=parseFloat(subtotal - nilai_diskon_pertama);
            var hasil_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama * diskon_kedua / 100);
            var hasil_hitung_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama - hasil_diskon_kedua);
            var hasil_akhir=parseFloat(hasil_hitung_diskon_kedua);

            
            
            this.state.subtotal[index]=parseInt(hasil_hitung_diskon_kedua);

            this.total();
            // this.subtotalnya();
            // this.diskonnya();
        },

        hitungJualHit(event,index){
            var jumlah=parseFloat(this.state.jumlahhit[index]);
            var harga=parseFloat(this.state.jualhit[index])
            var subtotal=harga * jumlah;
            var diskon_pertama=parseFloat(this.state.diskon_persen[index]);
            var diskon_kedua=parseFloat(this.state.diskon_rupiah[index]);
            var nilai_diskon_pertama=parseFloat(subtotal * diskon_pertama / 100);
            var hasil_hitung_diskon_pertama=parseFloat(subtotal - nilai_diskon_pertama);
            var hasil_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama * diskon_kedua / 100);
            var hasil_hitung_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama - hasil_diskon_kedua);
            var hasil_akhir=parseFloat(hasil_hitung_diskon_kedua);

            
            
            this.state.subtotal[index]=parseInt(hasil_hitung_diskon_kedua);

            this.total();
        },

        hitungTotalRupiah(event, index){
            var jumlah=parseFloat(this.state.jumlahhit[index]);
            var harga=parseFloat(this.state.jualhit[index])
            var subtotal=harga * jumlah;
            var diskon_pertama=parseFloat(this.state.diskon_persen[index]);
            var diskon_kedua=parseFloat(this.state.diskon_rupiah[index]);
            var nilai_diskon_pertama=parseFloat(subtotal * diskon_pertama / 100);
            var hasil_hitung_diskon_pertama=parseFloat(subtotal - nilai_diskon_pertama);
            var hasil_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama * diskon_kedua / 100);
            var hasil_hitung_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama - hasil_diskon_kedua);
            var hasil_akhir=parseFloat(hasil_hitung_diskon_kedua);

            
            
            this.state.subtotal[index]=parseInt(hasil_hitung_diskon_kedua);

            this.total();
            // this.subtotalnya();
            // this.diskonnya();
        },

        ubahJumlahDiskonRupiah(index){
            var jumlah=parseFloat(this.state.jumlahhit[index]);
            var harga=parseFloat(this.state.jualhit[index])
            var subtotal=harga * jumlah;
            var diskon_pertama=parseFloat(this.state.diskon_persen[index]);
            var diskon_kedua=parseFloat(this.state.diskon_rupiah[index]);
            var nilai_diskon_pertama=parseFloat(subtotal * diskon_pertama / 100);
            var hasil_hitung_diskon_pertama=parseFloat(subtotal - nilai_diskon_pertama);
            var hasil_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama * diskon_kedua / 100);
            var hasil_hitung_diskon_kedua=parseFloat(hasil_hitung_diskon_pertama - hasil_diskon_kedua);
            var hasil_akhir=parseFloat(hasil_hitung_diskon_kedua);

            
            
            this.state.subtotal[index]=parseInt(hasil_hitung_diskon_kedua);

            this.total();
            // this.subtotalnya();
            // this.diskonnya();
        },

        ubahDiskon(index){
            var jual=parseFloat(this.state.jual[index]);
            var jumlah=parseFloat(this.state.jumlah[index]);
            var diskon=parseFloat(this.state.diskon[index]);

            var h=(jual*jumlah)*diskon/100;

            // this.state.subtotal[index]=(jual*jumlah)*diskon/100;

            this.total();
            // this.subtotalnya();
            // this.diskonnya();
        },

        total(){
            var s=0;
            var subtotalnya=0;
            var diskon_tambahan=this.state.diskon_tambahan;
            for(var i=0; i<this.state.subtotal.length; i++){
                subtotalnya=parseFloat(subtotalnya)+parseFloat(this.state.subtotal[i]);
            }

            var hasil_diskon_tambahan=subtotalnya * diskon_tambahan / 100;
            this.subtotal=subtotalnya;
            this.state.total=parseFloat(subtotalnya - hasil_diskon_tambahan);
        },

        hitungDiskonTambahan(){
            var s=0;
            var subtotalnya=0;
            var diskon_tambahan=this.state.diskon_tambahan;
            this.subtotal=0;
            for(var i=0; i<this.state.subtotal.length; i++){
                subtotalnya=parseFloat(subtotalnya)+parseFloat(this.state.subtotal[i]);
            }
            console.log(subtotalnya);

            // var hasil_diskon_tambahan=subtotalnya * diskon_tambahan / 100;
            var hasil_diskon_tambahan=parseInt(subtotalnya) - parseInt(diskon_tambahan);
            this.subtotal=subtotalnya;
            this.state.total=hasil_diskon_tambahan;
        },

        subtotalnya(){
            var s=0;
            for(var i=0; i<this.state.subtotal.length; i++){
                s=parseFloat(s)+parseFloat(this.state.subtotal[i]);
            }

            this.subtotal=parseFloat(s);
        },
        
        diskonnya(){
            var s=0;
            for(var i=0; i<this.state.subtotal.length; i++){
                s=parseFloat(s)+parseFloat(this.state.diskon_rupiah[i]);
            }

            this.diskon=parseFloat(s);
        },


        store(e) {
            this.loading = true;

            axios.post(e.target.action, this.state).then(response => {
                if(response.data.success==true){
                    this.loading=false;
                    this.errors = [];
                    this.state = {
                        kd_picking:'',
                        kode:'',
                        nama: '',
                        customer:'',
                        tanggal:new Date(),
                        tanggaljt:new Date(),
                        perusahaan:'',
                        keterangan:'',
                        sales:'',
                        lokkasiname:'',
                        lokasiid:'',
                        customertop:'',
                        total:0,
                        kd_trans:'Tunai',
                        listBarang:[],
                        kodes:[],
                        kodehit:[],
                        jual:[],
                        jumlah:[],
                        diskon_persen:[],
                        diskon_rupiah:[],
                        subtotal:[],
                        dos:[],
                        pcs:[],
                        saless:[],
                        lokasi:[],
                        rak:[],
                        stokid:[],
                        doshit:[],
                        pcshit:[],
                        jualhit:[],
                        jumlahhit:[]
                    },
                    this.barangs=[];
                    this.hitungan=[]
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
            if(this.newbarang.kode==""){
                alert('Barang harus diisi');

                return false;
            }

            if(this.newbarang.nama==""){
                alert('Nama Barang harus diisi');

                return false;
            }

            if(this.newbarang.harga==""){
                alert('Harga barang harus diisi');

                return false;
            }

            if(this.newbarang.dos==""){
                alert('Dos harus diisi');

                return false;
            }

            if(this.newbarang.pcs==""){
                alert('PCS harus diisi');

                return false;
            }

            if(this.newbarang.jumlah==""){
                alert('Jumlah harus diisi');

                return false;
            }

            axios.get('/data/cari-stok-by-barang/'+this.newbarang.kode)
                .then(response => {
                    if(response.data.success == true){
                        this.hitungan.push(
                            {
                                kd_brg:this.newbarang.kode,
                                nm:this.newbarang.nama,
                                harga:this.newbarang.harga,
                                jumlah:this.newbarang.total_pcs,
                                dos:this.newbarang.dos,
                                pcs:this.newbarang.pcs,
                                diskon_persen:0,
                                diskon_rupiah:0,
                                subtotal:parseInt(this.newbarang.harga) * parseInt(this.newbarang.total_pcs),
                                status:'new'
                            }
                        )

                        this.state.kodehit=[];
                        this.state.doshit=[];
                        this.state.pcshit=[];
                        // this.state.stokid=[];
                        // this.state.idstok=[];
                        this.state.kodes=[];
                        this.state.jumlah=[];
                        this.state.jumlahhit=[];
                        this.state.jualhit=[];
                        this.state.subtotal=[];
                        this.state.diskon_persen=[];
                        this.state.diskon_rupiah=[];
                        this.state.status=[]

                        for(var c=0; c < this.hitungan.length; c++){
                            this.state.kodehit.push(this.hitungan[c].kd_brg);
                            this.state.doshit.push(this.hitungan[c].dos);
                            this.state.pcshit.push(this.hitungan[c].pcs);
                            this.state.jumlahhit[c]=this.hitungan[c].jumlah;
                            this.state.jualhit[c]=this.hitungan[c].harga;
                            this.state.subtotal[c]=parseFloat(this.hitungan[c].subtotal);
                            this.state.diskon_persen[c]=0;
                            this.state.diskon_rupiah[c]=0;
                            this.state.status.push(this.hitungan[c].status);
                            // this.ubahJumlah(c);
                        }

                        this.batalTambahBarang()
                    }
                })


            // this.state.listBarang.push(
            //     {
            //         // kd_barang:this.barang.kode.kd,
            //         kd_barang:this.newbarang.kode,
            //         nm_barang:this.newbarang.nama,
            //         harga:this.newbarang.harga,
            //         dos:this.newbarang.dos,
            //         pcs:this.newbarang.pcs,
            //         diskon:this.newbarang.diskon,
            //         jumlah:this.newbarang.jumlah
            //     }
            // );
            // console.log(this.state.listBarang)

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
            if(this.state.kode==""){
                alert('Kode harus diisi');

                return false;
            }

            if(this.state.kd_picking==""){
                alert('No Po harus diisi');

                return false;
            }

            if(this.state.sales==""){
                alert('Sales harus diisi');

                return false;
            }

            this.loading = true;

            axios.post('/data/order', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.state.kd_picking='';
                        this.state.kode='';
                        this.state.nama= '';
                        this.state.customer='';
                        this.state.tanggal=new Date();
                        this.state.tanggaljt=new Date();
                        this.state.perusahaan='';
                        this.state.keterangan='';
                        this.state.sales='';
                        this.state.lokkasiname='';
                        this.state.lokasiid='';
                        this.state.customertop='';
                        this.state.total=0;
                        this.state.kd_trans='Tunai';
                        this.state.listBarang=[];
                        this.state.kodes=[];
                        this.state.kodehit=[];
                        this.state.jual=[];
                        this.state.jumlah=[];
                        this.state.diskon_persen=[];
                        this.state.diskon_rupiah=[];
                        this.state.subtotal=[];
                        this.state.dos=[];
                        this.state.pcs=[];
                        this.state.saless=[];
                        this.state.lokasi=[];
                        this.state.rak=[];
                        this.state.stokid=[];
                        this.state.doshit=[];
                        this.state.pcshit=[];
                        this.state.jualhit=[];
                        this.state.jumlahhit=[]
                        this.state.diskon_tambahan=0

                        this.tampilDetail=false;

                        // console.log(this.state);
                        this.dataprint={
                            perusahaan:response.data.nota.perusahaan.nama,
                            telp:response.data.nota.picking.po.customer.tlpn,
                            sales:response.data.nota.sales.nm,
                            no_order:response.data.nota.no_order,
                            tanggal:response.data.nota.tgl,
                            tanggaljt:response.data.nota.tgljt,
                            customer:response.data.nota.picking.po.customer,
                            diskon_rupiah:response.data.nota.diskon_rupiah,
                            total:response.data.nota.total,
                            kd_trans:response.data.nota.kd_trans,
                            kd_picking:response.data.nota.kd_picking,
                            status_pembayaran:response.data.nota.status_pembayaran,
                            // detail:response.data.nota.detail,
                            keterangan:response.data.keterangan,
                            total:response.data.nota.total,
                            update_at: response.data.nota.updated_at,
                        }
                        
                        this.dataprint.detail = response.data.nota.detail;
                        // this.print();

                        this.$nextTick(() => {
                            this.$htmlToPaper('printMe');
                        });
                        // window.open("/data/print-order/"+response.data.nota.no_order, "_blank");   

                        this.getCode();
                        this.getPicking();
                        this.message = 'Data has been saved.';
                        this.loading = false;
                    }else{
                        alert('Internal server error');
                    }
                })
        },

        batalOrder(){
            if(this.state.kode==""){
                alert('Kode harus diisi');

                return false;
            }

            if(this.state.kd_picking==""){
                alert('No Po harus diisi');

                return false;
            }

            this.loading = true;

            axios.post('/data/cancel-order', this.detailPicking)
                .then(response => {
                    if(response.data.success==true){
                        this.state.kd_picking='';
                        this.state.kode='';
                        this.state.nama= '';
                        this.state.customer='';
                        this.state.tanggal=new Date();
                        this.state.tanggaljt=new Date();
                        this.state.perusahaan='';
                        this.state.keterangan='';
                        this.state.sales='';
                        this.state.lokkasiname='';
                        this.state.lokasiid='';
                        this.state.customertop='';
                        this.state.total=0;
                        this.state.kd_trans='Tunai';
                        this.state.listBarang=[];
                        this.state.kodes=[];
                        this.state.kodehit=[];
                        this.state.jual=[];
                        this.state.jumlah=[];
                        this.state.diskon_persen=[];
                        this.state.diskon_rupiah=[];
                        this.state.subtotal=[];
                        this.state.dos=[];
                        this.state.pcs=[];
                        this.state.saless=[];
                        this.state.lokasi=[];
                        this.state.rak=[];
                        this.state.stokid=[];
                        this.state.doshit=[];
                        this.state.pcshit=[];
                        this.state.jualhit=[];
                        this.state.jumlahhit=[]
                        this.state.diskon_tambahan=0

                        this.tampilDetail=false;

                        this.getCode();
                        this.getPicking();
                        this.message = 'Order berhasil dicancel';
                        this.loading = false;
                    }else{
                        alert('Internal server error');
                    }
                })
        },

        hitungTotal(event){
            if(this.newbarang.kode==""){
                alert('Barang harus diisi');

                return false;
            }


            this.newbarang.total_pcs=parseInt(this.newbarang.dos)*parseInt(this.hasilpcs) + parseInt(this.newbarang.pcs);
        },

        hitungTotalPcs(event){
            if(this.newbarang.kode==""){
                    alert('Barang harus diisi');

                    return false;
                }


                this.newbarang.total_pcs=parseInt(this.newbarang.dos)*parseInt(this.hasilpcs) + parseInt(this.newbarang.pcs);
        },
 
    },
    computed:{
        totalQty: function() {
            return this.state.subtotal.reduce(function(a, c){
                return a + Number(c || 0)
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
