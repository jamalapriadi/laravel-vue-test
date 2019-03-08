<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Data Piutang

            <div class="card-header-actions">
                <router-link to="/add-new-piutang" class="btn btn-primary">
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
                        <th>No. Order</th>
                        <th>Kd. Picking</th>
                        <th>Tgl</th>
                        <th>Tgl Jatuh Tempo</th>
                        <th>Sales</th>
                        <th>Total Hutang</th>
                        <th>Total Bayar</th>
                        <!-- <th>Sisa</th>
                        <th></th> -->
                        <th width="17%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l, index) in list" v-bind:key="index">
                        <td>{{index+1}}</td>
                        <td>{{l.no_order}}</td>
                        <td>{{l.kd_picking}}</td>
                        <td>{{l.tgl}}</td>
                        <td>{{l.tgljt}}</td>
                        <td>{{l.sales_id}}</td>
                        <td>{{l.total_hutang}}</td>
                        <td>{{l.total_dibayar}}</td>
                        <!-- <td>{{l.sisa_bayar}}</td>
                        <td>
                            <div v-if="l.sisa_bayar > 0">Belum Lunas</div>
                            <div v-if="l.sisa_bayar == 0">Lunas</div>
                        </td> -->
                        <td>
                            <div class="btn-group">
                                <router-link :to="{ name: 'piutangDetail', params: {id: l.no_order}}" class="btn btn-info">
                                    <i class="fa fa-list text-white"></i>
                                </router-link>

                                <!-- <a class="btn btn-danger" v-on:click="hapus(l.no_order, index, l.nm)" v-bind:id="'delete'+l.no_order">
                                    <i class="fa fa-trash text-white"></i>
                                </a> -->
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    
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

                axios.get('data/piutang?page='+page)
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

                axios.get('/data/piutang?q='+this.pencarian)
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
                        axios.delete('/data/piutang/'+id)
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
