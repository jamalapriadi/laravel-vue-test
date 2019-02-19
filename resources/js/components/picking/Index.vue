<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Data Picking

            <div class="card-header-actions">
                <router-link to="/add-new-picking" class="btn btn-primary">
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
                        <th>Kd. Picking</th>
                        <th>No. PO</th>
                        <th>Customer</th>
                        <th>Keterangan</th>
                        <!-- <th>Kd Transaksi</th> -->
                        <th>Tgl</th>
                        <!-- <th>Tgl Jatuh Tempo</th> -->
                        <th>Lokasi</th>
                        <!-- <th>Jumlah Barang</th>
                        <th>Total</th> -->
                        <th width="17%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l, index) in list.data" v-bind:key="index">
                        <td>{{index+1}}</td>
                        <td>{{l.kd_picking}}</td>
                        <td>{{l.no_po}}</td>
                        <td>{{l.po.customer.nm}}</td>
                        <td>{{l.ket}}</td>
                        <!-- <td>{{l.kd_trans}}</td> -->
                        <td>{{l.tgl}}</td>
                        <!-- <td>{{l.tglj}}</td> -->
                        <td>{{l.po.lokasi.nm}}</td>
                        <!-- <td>{{l.ket}}</td>
                        <td>{{l.detail.length}}</td> -->
                        <!-- <td>{{l.total}}</td> -->
                        <td>
                            <div class="btn-group">
                                <router-link :to="{ name: 'pickingDetail', params: {id: l.kd_picking}}" class="btn btn-info">
                                    <i class="fa fa-list text-white"></i>
                                </router-link>

                                <a class="btn btn-danger" v-on:click="hapus(l.kd_picking, index, l.nm)" v-bind:id="'delete'+l.kd_picking">
                                    <i class="fa fa-trash text-white"></i>
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
                loading:true
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

                axios.get('data/picking?page='+page)
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

                axios.get('/data/picking?q='+this.pencarian)
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
                        axios.delete('/data/picking/'+id)
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
        }
    }
</script>
