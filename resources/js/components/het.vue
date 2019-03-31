<template>
    <div class="card card-accent-primary">
        <div class="card card-header">Pencarian Barang</div>
        <div class="card-body">
            <fieldset>
                <legend>Filter</legend>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="kelompok" class="control-label">Kelompok</label>
                            <select name="kelompok" id="kelompok" class="form-control" v-model="state.kelompok" @change="ubahKelompok">
                                <option value="">--Pilih Kelompok--</option>
                                <option v-for="(l, index) in kelompok" v-bind:value="l.id" v-bind:key="index">{{l.nm}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="merk" class="control-label">Merk</label>
                            <select name="merk" id="merk" class="form-control" v-model="state.merk" @change="ubahMerk">
                                <option value="">--Pilih Merk--</option>
                                <option v-for="(l, index) in merk" v-bind:value="l.id" v-bind:key="index">{{l.nm}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="" class="control-label">Nama / Kode Barang</label>
                            <input type="text" class="form-control" name="q" id="q" placeholder="Type and Enter" v-model="pencarian">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <button class="btn btn-primary" style="margin-top:25px;">
                            <i class="icon icon-magnifier"></i> 
                            Cari
                        </button>
                    </div> -->
                </div>
            </fieldset>

            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kelompok</th>
                        <th>Merk</th>
                        <th>PCS</th>
                        <th>HET</th>
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
                        <td>{{l.pcs}}</td>
                        <td>{{l.jual}}</td>
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
                state:{
                    merk:'',
                    kelompok:''
                },
                list:[],
                listData:{},
                pencarian:'',
                loading:true,
                kelompok:[],
                merk:[]
            }
        },
        mounted() {
            this.showData();
            this.showKelompok()
            this.showMerk()
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

                axios.get('/data/barang',{
                        params:{
                            q:this.pencarian,
                            merk:this.state.merk,
                            kelompok:this.state.kelompok
                        }
                    })
                    .then(response => {
                        this.list = response.data;
                    })
                    .catch( errors => {
                        alert('pencarian tidak ditemukan');
                    })
            },

            showKelompok(){
                axios.get('/data/list-kelompok')
                    .then(response => {
                        this.kelompok=response.data;
                    })
            },

            showMerk(){
                axios.get('/data/list-merk')
                    .then(response => {
                        this.merk = response.data;
                    })
            },

            ubahMerk(){
                this.cariData();
            },

            ubahKelompok(){
                this.cariData()
            }
        }
    }
</script>
