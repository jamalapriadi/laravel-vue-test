<template>
    <div class="card card-default">
        <div class="card-header">List PO Konfirmasi</div>
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
                        <th>Status Konfirmasi</th>
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
                        <td><label class='label label-info'>{{l.status_konfirmasi}}</label></td>
                        <td>{{l.detail.length}}</td>
                        <td>
                            <div class="btn-group">
                                <router-link :to="{ name: 'poDetailStatus', params: {id: l.no_po}}" class="btn btn-info">
                                    <i class="fa fa-list text-white"></i>
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div align="right">
                <pagination :data="listData" @pagination-change-page="showData" :show-disabled="true"></pagination>
            </div>
        </div>

        <b-modal ref="myModalRef" size="lg" hide-footer title="Update Status">
            <div >
                <div class="form-group">
                    <label for="" class="control-label">Pilih Status</label>
                    <select name="status" id="status" class="form-control" v-model="form.status">
                        <option value="" disabled selected>--Pilih Status--</option>
                        <option value="Accept">Accept</option>
                        <option value="Refuse">Refuse</option>
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" v-on:click="saveUpdateStatus()">
                        <i class="icon icon-doc"></i>
                        Save
                    </button>
                </div>
            </div>
        </b-modal>

    </div>
</template>

<script>
export default {
    data(){
        return {
            list:[],
            noorder:'',
            form:{
                noorder:'',
                status:''
            },
            pencarian:'',
            listData:{},
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
    methods:{
        paginate(url){
            axios.get(url)
                .then(response => {
                    this.list = response.data;
                })
        },

        showData(){
            axios.get('/data/order-jatuh-tempo')
                .then(response => {
                    this.list=response.data;
                })
        },
        updatestatus(n){
            this.noorder=n;
            this.form.noorder=n;
            this.$refs.myModalRef.show()
        },
        saveUpdateStatus(){
            axios.post('/data/update-status-order', this.form)
                .then(response => {
                    this.showData();
                })
        },
        cariData(page){
            if(typeof page === 'undefined'){
                page = 1;
            }

            axios.get('/data/order-jatuh-tempo?q='+this.pencarian)
                .then(response => {
                    this.list = response.data;
                })
                .catch( errors => {
                    alert('pencarian tidak ditemukan');
                })
        },
    }
}
</script>
