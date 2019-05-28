<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Data Customer

            <div class="card-header-actions">
                <router-link to="/add-customer" class="btn btn-primary">
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
                            <th>Jenis Customer</th>
                            <th>Nama Toko</th>
                            <th>Nama Pemilik</th>
                            <th>Alias</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>NIK</th>
                            <th>NPWP</th>
                            <!-- <th>NMTK</th> -->
                            <th>No. HP / Telpon</th>
                            <!-- <th>Kontak</th>
                            <th>Fax</th> -->
                            <th>Plafon</th>
                            <th>Top</th>
                            <!-- <th>Jenis</th> -->
                            <th width="17%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(l, index) in list.data" v-bind:key="index">
                            <td>{{index+1}}</td>
                            <td>{{l.jenis_customer}}</td>
                            <td>{{l.nm_toko}}</td>
                            <td>{{l.nm}}</td>
                            <td>{{l.alias}}</td>
                            <td>{{l.alamat}}</td>
                            <td>{{l.kota.nm}}</td>
                            <td>{{l.nik}}</td>
                            <td>{{l.npwp}}</td>
                            <!-- <td>{{l.nmtk}}</td> -->
                            <td>{{l.tlpn}}</td>
                            <!-- <td>{{l.kontak}}</td>
                            <td>{{l.fax}}</td> -->
                            <td>{{l.plafon}}</td>
                            <td>{{l.top}}</td>
                            <!-- <td>{{l.jenis}}</td> -->
                            <td>
                                <div class="btn-group">
                                    <router-link :to="{ name: 'customerEdit', params: {id: l.kd}}" class="btn btn-warning">
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
            </div>

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

                axios.get('data/customer?page='+page)
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

                axios.get('/data/customer?q='+this.pencarian)
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
                        axios.delete('/data/customer/'+id)
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
