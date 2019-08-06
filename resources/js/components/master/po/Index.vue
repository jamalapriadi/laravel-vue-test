<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Data PO

            <div class="card-header-actions">
                <router-link to="/add-new-po" class="btn btn-primary">
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
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>Nmr. PO</th>
                        <th>Customer</th>
                        <th>Lokasi</th>
                        <th>Perusahaan</th>
                        <th>Tgl</th>
                        <th>Keterangan</th>
                        <th>Jumlah Barang</th>
                        <th width="17%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l, index) in list.data" v-bind:key="index">
                        <td>{{index+1}}</td>
                        <td>{{l.no_po}}</td>
                        <td>{{l.customer.nm}}</td>
                        <td>{{l.lokasi.nm}}</td>
                        <td>{{l.perusahaan.nama}}</td>
                        <td>{{l.tgl}}</td>
                        <td>{{l.ket}}</td>
                        <td>{{l.detail.length}}</td>
                        <td>
                            <div class="btn-group">
                                <router-link :to="{ name: 'poDetail', params: {id: l.no_po}}" class="btn btn-info">
                                    <i class="fa fa-list text-white"></i>
                                </router-link>

                                <a class="btn btn-danger" v-on:click="hapus(l.no_po, index, l.nm)" v-bind:id="'delete'+l.no_po">
                                    <i class="fa fa-trash text-white"></i>
                                </a>

                                <a class="btn btn-success" v-on:click="cetak(l.no_po, index, l.nm)" v-bind:id="'cetak'+l.no_po">
                                    <i class="fa fa-print text-white"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    
            <div align="right">
                <pagination :data="listData" :limit="3" @pagination-change-page="showData" :show-disabled="true"></pagination>
            </div>

            <!-- print -->
            <div id="printMe" style="margin-top:10px;display:none;">
                <br>
                <table>
                    <tr>
                        <td>No. PO</td>
                        <td> : </td>
                        <td>{{dataprint.no_po}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td> : </td>
                        <td>{{dataprint.tanggal}}</td>
                    </tr>
                    <tr>
                        <td>Customer</td>
                        <td> : </td>
                        <td>{{dataprint.customer}}</td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td> : </td>
                        <td>{{dataprint.lokasi}}</td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td> : </td>
                        <td>{{dataprint.keterangan}}</td>
                    </tr>
                </table>
                <hr>
                <div v-for="(l,index) in dataprint.detail" v-bind:key="index">
                    <table width="45%">
                        <thead>
                            <tr>
                                <th rowspan="3">{{l.nm}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> Rak :
                                    <ul>
                                        <li v-for="(k,idx) in l.rak" v-bind:key="idx">{{k.rak}} - {{k.diambil}}</li>
                                    </ul>
                                </td>
                                <td>{{l.dos}} Dos</td>
                                <td>{{l.pcs}} Pcs</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
    import { VueLoading } from 'vue-loading-template'

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
                    no_po:'',
                    tanggal:'',
                    customer:'',
                    lokasi:'',
                    keterangan:'',
                    detail:[]
                },
            }
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

                axios.get('data/po?page='+page)
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

                axios.get('/data/po?q='+this.pencarian)
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
                        axios.delete('/data/po/'+id)
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
                axios.get('/data/po/'+id)
                    .then(response => {
                        this.dataprint={
                            perusahaan:response.data.po.perusahaan.nama,
                            no_po:response.data.po.no_po,
                            tanggal:response.data.po.tgl,
                            customer:response.data.po.customer.nm,
                            lokasi:response.data.po.lokasi.nm,
                            keterangan:response.data.po.ket,
                            detail:response.data.detail
                        }

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
