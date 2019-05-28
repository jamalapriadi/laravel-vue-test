<template>
    <div class="card card-default">
        <div class="card-header">Lihat Stok</div>
        <div class="card-body">
            <fieldset>
                <legend>Filter</legend>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="" class="control-label">Lokasi</label>
                            <select name="lokasi" id="lokasi" class="form-control" v-model="state.lokasi">
                                <option value="" selected>--Pilih Lokasi--</option>
                                <option v-for="(l,index) in lokasi" v-bind:key="index" v-bind:value="l.id">{{l.nm}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="" class="control-label">Rak</label>
                            <select name="rak" id="rak" class="form-control" v-model="state.rak">
                                <option value="" selected>--Pilih Rak--</option>
                                <option v-for="(l,index) in rak" v-bind:key="index" v-bind:value="l.kd">{{l.nm}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="" class="control-label">Keyword</label>
                            <input type="text" class="form-control" v-model="state.pencarian">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <button class="btn btn-primary" style="margin-top:25px;" @click="showData(1)">
                                <i class="icon-magnifier"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </div>
            </fieldset>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width='5%'>No.</th>
                        <!-- <th>Kode Barang</th> -->
                        <th>Nama Barang</th>
                        <th>Lokasi</th>
                        <th>Rak</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l, index) in list.data" v-bind:key="index">
                        <td>{{index+1}}</td>
                        <td>{{l.nama_barang}}</td>
                        <td>{{l.nama_lokasi}}</td>
                        <td>{{l.nama_rak}}</td>
                        <td>{{l.harga | formatNumber}}</td>
                        <td>{{l.jumlah_stok | formatNumber}}</td>
                        <td></td>
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
var numeral = require("numeral");

Vue.filter("formatNumber", function (value) {
return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});
import { VueLoading } from 'vue-loading-template'

export default {
    components: {
        VueLoading
    },
    data(){
        return {
            state:{
                lokasi:'',
                rak:'',
                pencarian:''
            },
            list:[],
            listData:{},
            pencarian:'',
            loading:true,
            lokasi:[],
            rak:[],
            merk:[]
        }
    },
    mounted() {
        this.showData();
        this.getLokasi();
        this.getRak();
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

            axios.get('/data/lihat-stok?page='+page+"&lokasi="+this.state.lokasi+"&rak="+this.state.rak+"&q="+this.state.pencarian)
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

            axios.get('/data/lihat-stok?q='+this.pencarian)
                .then(response => {
                    this.list = response.data;
                })
                .catch( errors => {
                    alert('pencarian tidak ditemukan');
                })
        },

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then(response => {
                    this.lokasi=response.data;
                })
        },

        getRak(){
            axios.get('/data/list-rak')
                .then(response => {
                    this.rak=response.data;
                })
        }
    }
}
</script>

