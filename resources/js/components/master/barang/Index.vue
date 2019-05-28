<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Data Barang

            <div class="card-header-actions">
                
            </div>
        </div>

        <div class="card-body">
            <div class="btn-group">
                <router-link to="/add-barang" class="btn btn-primary text-white">
                    <i class="fa fa-plus"></i> Add New
                </router-link>

                <router-link to="/import-barang" class="btn btn-success text-white">
                    <i class="fa fa-file-excel"></i> Import Barang
                </router-link>

                <router-link to="/import-update-barang" class="btn btn-warning text-white">
                    <i class="fa fa-file-excel"></i> Update Barang
                </router-link>

                <a href="/data/export-barang" class="btn btn-info text-white">
                    <i class="fa fa-file-excel"></i> Export Barang
                </a>
            </div>
            <hr>
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
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kelompok</th>
                        <th>Merk</th>
                        <!-- <th>Status</th> -->
                        <!-- <th>Satuan</th> -->
                        <th>PCS</th>
                        <!-- <th>Harga Beli</th> -->
                        <th>Harga Pokok</th>
                        <th>HET</th>
                        <th width="17%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l, index) in list.data" v-bind:key="index">
                        <td>{{index+1}}</td>
                        <td>{{l.kd}}</td>
                        <td>{{l.nm}}</td>
                        <td>
                            <div v-if="l.kelompok!=null">
                                {{l.kelompok.nm}}
                            </div>
                        </td>
                        <td>
                            <div v-if="l.merk!=null">
                                {{l.merk.nm}}
                            </div>
                        </td>
                        <!-- <td>{{l.status}}</td> -->
                        <!-- <td>{{l.satuan}}</td> -->
                        <td>{{l.pcs}}</td>
                        <!-- <td>{{l.hrgb}}</td> -->
                        <td>{{l.hrgp}} </td>
                        <td>{{l.jual}}</td>
                        <td>
                            <div class="btn-group">
                                <router-link :to="{ name: 'barangEdit', params: {id: l.kd}}" class="btn btn-warning">
                                    <i class="fa fa-edit text-white"></i>
                                </router-link>

                                <a class="btn btn-danger" v-on:click="hapus(l.kd, index, l.nm)" v-bind:id="'delete'+l.kd">
                                    <i class="fa fa-trash text-white"></i>
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
                        axios.delete('/data/barang/'+id)
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
