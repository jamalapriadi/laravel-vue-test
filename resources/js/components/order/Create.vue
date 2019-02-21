<template>
    <div class="card card-default">
        <div class="card-heading">

        </div>
        <div class="card-body">

            <div v-if="message" class="alert alert-success">
                {{ message }}
            </div>


            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="" class="control-label">Nomor Order</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                    </div>
                </div>

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
                            <option v-for="(l,index) in pickings" v-bind:key="index" v-bind:value="l.kd_picking">{{l.kd_picking}}</option>
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
                                    <input type="text" class="form-control" v-model="state.jualhit[index]" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control" v-model="state.jumlahhit[index]" readonly>
                                </td>
                                <td>
                                    <div class="input-group" style="width:80px;">
                                        <input type="number" class="form-control" v-model="state.diskon_persen[index]" @keyup.enter="ubahJumlahDiskonPersen(index)" placeholder="%">
                                        <span style="margin-top:5px;margin-left:5px;">%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Rp.
                                            </span>
                                        </div>
                                        <input type="number" class="form-control" v-model="state.diskon_rupiah[index]" @keyup.enter="ubahJumlahDiskonRupiah(index)">
                                    </div>
                                </td>
                                <td>
                                    <input type="number" class="form-control" v-model="state.subtotal[index]" readonly>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7">TOTAL</th>
                                <th>{{state.total}}</th>
                            </tr>
                        </tfoot>
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
                diskon_rupiah:''
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
        this.getPicking();
        // this.getCustomer();
        // this.getPerusahaan();
        this.getSales();
        // this.getLokasi();
    },
    methods: {
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
                this.state.tanggaljt=date;
            }
        },

        ubahPicking(){
            console.log(this.state.kd_picking);
            this.hitungan=[];
            this.barangs=[];
            axios.get('/data/picking/'+this.state.kd_picking)
                .then(response => {
                    this.tampilDetail=true;
                    console.log(response.data);
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

                    for(var c=0; c < this.hitungan.length; c++){
                        this.state.kodehit.push(this.hitungan[c].kd_brg);
                        this.state.doshit.push(this.hitungan[c].dos);
                        this.state.pcshit.push(this.hitungan[c].pcs);
                        this.state.jumlahhit.push(this.hitungan[c].jumlah);
                        this.state.jualhit.push(this.hitungan[c].harga);
                        this.state.subtotal[c]=this.hitungan[c].subtotal;
                        this.state.diskon_persen[c]=0;
                        this.state.diskon_rupiah[c]=0;
                        // this.ubahJumlah(c);
                    }

                    for (var i = 0; i < this.barangs.length; i++) {
                        this.state.stokid.push(this.barangs[i].pivot.id);
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
            this.state.subtotal[index]=parseInt(this.state.jual[index]) * parseInt(this.state.jumlah[index]);

            this.total();
        },

        ubahJumlahDiskonPersen(index){
            var diskonnya=parseInt(this.state.jualhit[index]) * parseInt(this.state.jumlahhit[index]) * parseInt(this.state.diskon_persen[index]) / 100;
            var harganya=parseInt(this.state.jualhit[index]) * parseInt(this.state.jumlahhit[index]);

            // this.state.diskon_rupiah[index]= diskonnya;
            this.state.subtotal[index]=harganya - diskonnya - parseInt(this.state.diskon_rupiah[index]);

            this.total();
        },

        ubahJumlahDiskonRupiah(index){
            var diskonnya=parseInt(this.state.jualhit[index]) * parseInt(this.state.jumlahhit[index]) * parseInt(this.state.diskon_persen[index]) / 100;
            var harganya=parseInt(this.state.jualhit[index]) * parseInt(this.state.jumlahhit[index]);

            // this.state.diskon_rupiah[index]= diskonnya;
            this.state.subtotal[index]=harganya - diskonnya - parseInt(this.state.diskon_rupiah[index]);

            this.total();
        },

        ubahDiskon(index){
            var jual=parseInt(this.state.jual[index]);
            var jumlah=parseInt(this.state.jumlah[index]);
            var diskon=parseInt(this.state.diskon[index]);

            var h=(jual*jumlah)*diskon/100;

            // this.state.subtotal[index]=(jual*jumlah)*diskon/100;

            this.total();
        },

        total(){
            var s=0;
            for(var i=0; i<this.state.subtotal.length; i++){
                s=parseInt(s)+parseInt(this.state.subtotal[i]);
            }

            this.state.total=s;
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
            if(this.state.kode==""){
                alert('Kode harus diisi');

                return false;
            }

            if(this.state.kd_picking==""){
                alert('No Po harus diisi');

                return false;
            }

            this.loading = true;

            axios.post('/data/order', this.state)
                .then(response => {
                    if(response.data.success==true){
                        this.stete.kd_picking='';
                        this.stete.kode='';
                        this.stete.nama= '';
                        this.stete.customer='';
                        this.stete.tanggal=new Date();
                        this.stete.tanggaljt=new Date();
                        this.stete.perusahaan='';
                        this.stete.keterangan='';
                        this.stete.sales='';
                        this.stete.lokkasiname='';
                        this.stete.lokasiid='';
                        this.stete.customertop='';
                        this.stete.total=0;
                        this.stete.kd_trans='Tunai';
                        this.stete.listBarang=[];
                        this.stete.kodes=[];
                        this.stete.kodehit=[];
                        this.stete.jual=[];
                        this.stete.jumlah=[];
                        this.stete.diskon_persen=[];
                        this.stete.diskon_rupiah=[];
                        this.stete.subtotal=[];
                        this.stete.dos=[];
                        this.stete.pcs=[];
                        this.stete.saless=[];
                        this.stete.lokasi=[];
                        this.stete.rak=[];
                        this.stete.stokid=[];
                        this.stete.doshit=[];
                        this.stete.pcshit=[];
                        this.stete.jualhit=[];
                        this.stete.jumlahhit=[]

                        this.tampilDetail=false;

                        this.getCode();
                        this.getPicking();
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
