<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Data Order

            <div class="card-header-actions">
                <router-link to="/add-new-order" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New
                </router-link>
            </div>
        </div>

        <div class="card-body">
            
            <div class="row">
                <div class="col-lg-5">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="" class="control-label col-lg-4">FILTER:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="q" id="q" placeholder="Type and Enter" v-model="pencarian">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <br>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th>Nmr. Order</th>
                            <th>Kd. Picking</th>
                            <th>Customer</th>
                            <!-- <th>Perusahaan</th> -->
                            <th>Sales</th>
                            <th>Kd Transaksi</th>
                            <th>Tgl</th>
                            <th>Tgl Jatuh Tempo</th>
                            <!-- <th>Lokasi</th> -->
                            <th>Keterangan</th>
                            <th>Jumlah Barang</th>
                            <th>Total</th>
                            <th width="17%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(l, index) in list.data" v-bind:key="index">
                            <td>{{index+1}}</td>
                            <td>{{l.no_order}}</td>
                            <td>{{l.kd_picking}}</td>
                            <td>
                                <p v-if="l.picking!=null">
                                    {{l.picking.po.customer.nm}}
                                </p>
                                <p class="label label-danger" v-else>Picking Not Found</p>
                            </td>
                            <!-- <td>{{l.perusahaan.nama}}</td> -->
                            <td>{{l.sales.nm}}</td>
                            <td>{{l.kd_trans}}</td>
                            <td>{{l.tgl}}</td>
                            <td>{{l.tgljt}}</td>
                            <!-- <td></td> -->
                            <td>{{l.ket}}</td>
                            <td>{{l.detail.length}}</td>
                            <td>{{l.total | formatNumber}}</td>
                            <td>
                                <div class="btn-group">
                                    <router-link :to="{ name: 'orderDetail', params: {id: l.no_order}}" class="btn btn-info">
                                        <i class="fa fa-list text-white"></i>
                                    </router-link>

                                    <a class="btn btn-danger" v-on:click="hapus(l.no_order, index, l.nm)" v-bind:id="'delete'+l.no_order">
                                        <i class="fa fa-trash text-white"></i>
                                    </a>

                                    <!-- <a class="btn btn-success" v-on:click="cetak(l.no_order, index, l.nm)" v-bind:id="'cetak'+l.no_order">
                                        <i class="fa fa-print text-white"></i>
                                    </a> -->
                                    <a class="btn btn-success" :href="'/data/print-order/'+l.no_order" target="_blank">
                                        <i class="fa fa-print text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    
            <div align="right">
                <pagination :data="listData" :limit="3" @pagination-change-page="showData" :show-disabled="true"></pagination>
            </div>

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


        </div>
    </div>
</template>

<script>
    import { VueLoading } from 'vue-loading-template'

    var numeral = require("numeral");

    Vue.filter("formatNumber", function (value) {
        return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
    });

    export default {
        components: {
            VueLoading
        },
        data(){
            return {
                list:[],
                listData:{},
                pencarian:'',
                loading:true,
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
                user:{
                    username:'',
                    perusahaan:{
                    ket:{
                        no_hp:'',
                        email:''
                    }
                }
                },
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
        mounted() {
            this.showData();

            console.log(this.list);
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
        methods: {
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

                axios.get('data/order?page='+page)
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

                axios.get('/data/order?q='+this.pencarian)
                    .then(response => {
                        this.list = response.data;
                    })
                    .catch( errors => {
                        alert('pencarian tidak ditemukan');
                    })
            },

            hapus(id,index,name){
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You can\'t revert your action',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes Delete it!',
                    cancelButtonText: 'No, Keep it!',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                })
                .then((result) => {
                    if(result.value) {
                        axios.delete('/data/order/'+id)
                            .then(response => {
                                if(response.data.success==true){
                                    this.$swal('Deleted', response.data.pesan , 'success');
                                    this.showData();
                                }else{
                                    this.$swal('Deleted', response.data.pesan , 'error');
                                }
                            })
                        
                    } else {
                        this.$swal('Cancelled', 'Your file is still intact', 'info')
                    }
                })
            },

            cetak(id,index,name){

                axios.get('/data/order/'+id)
                    .then(response => {
                        this.dataprint={
                            perusahaan:response.data.perusahaan.nama,
                            telp:response.data.picking.po.customer.telp,
                            sales:response.data.sales.nm,
                            no_order:response.data.no_order,
                            tanggal:response.data.tgl,
                            tanggaljt:response.data.tgljt,
                            customer:response.data.picking.po.customer,
                            total:response.data.total,
                            kd_trans:response.data.kd_trans,
                            kd_picking:response.data.kd_picking,
                            status_pembayaran:response.data.status_pembayaran,
                            keterangan:response.data.ket,
                            diskon_rupiah:response.data.diskon_rupiah,
                            total:response.data.total,
                            update_at: response.data.updated_at,
                        }
                        this.dataprint.detail = response.data.detail;
                        // this.print();

                        this.$nextTick(() => {
                            this.$htmlToPaper('printMe');
                        });
                    })
                    .catch( error => {
                        alert('data tidak dapat di load');
                    })
            }
        }
    }
</script>