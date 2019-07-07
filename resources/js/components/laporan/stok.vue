<template>
    <div class="card card-default">
        <div class="card-header">Laporan Stok</div>
        <div class="card-body">
            <div class="form-horizontal">
                <div class="form-group row">
                    <label for="" class="col-lg-3 control-label">Lokasi</label>
                    <div class="col-lg-4">
                        <select name="lokasi" id="lokasi" class="form-control" v-model="state.lokasi">
                            <option value="">--Semua Lokasi--</option>
                            <option v-for="(l,index) in lokasi" v-bind:key="index" v-bind:value="l.id">{{l.lokasi}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-lg-3 control-label">Kelompok Barang</label>
                    <div class="col-lg-4">
                        <select name="kelompok" id="kelompok" class="form-control" v-model="state.kelompok">
                            <option value="">--Semua Kelompok--</option>
                            <option v-for="(l,index) in kelompok" v-bind:key="index" v-bind:value="l.id">{{l.nm}}</option>
                        </select>
                        <!-- <div class="checkbox">
                            <label for="">
                                <input type="checkbox"> Rekap Per Kode Barang
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="">
                                <input type="checkbox"> Rekap Per Kode Barang Tanpa Lokasi
                            </label>
                        </div> -->
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="" class="control-label"></label>
                    <button class="btn btn-primary" @click="cetak()">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>

        <div id="printMe" style="margin-top:10px;display:none;">
            <h2>LAPORAN STOK</h2>

            <p>Tanggal Cetak : {{dataprint.tanggal_cetak}}</p>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Lokasi</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Tot. Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l,index) in dataprint.lis" v-bind:key="index">
                        <td>{{index+1}}</td>
                        <td>{{l.lokasi}}</td>
                        <td>
                            {{l.kode_barang}}
                        </td>
                        <td>
                            {{l.nama_barang}}
                        </td>
                        <td class="text-right">
                            {{l.jumlah_stok}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


    </div>
</template>

<script>
import { VueLoading } from 'vue-loading-template'
// Import this component
import datePicker from 'vue-bootstrap-datetimepicker';

// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

export default {
    components: {
        VueLoading,
        datePicker 
    },
    data(){
        return {
            state:{
                mulai:new Date(),
                selesai:new Date(),
                lokasi:'',
                kelompok:''
            },
            options: {
                format: 'DD-MM-YYYY',
                useCurrent: false,
            }, 
            lokasi:[],
            kelompok:[],
            dataprint:{
                mulai:'',
                selesai:'',
                tanggal_cetak:'',
                lis:[]
            }
        }
    },
    mounted(){
        this.getLokasi()
        this.getKelompok()
    },
    methods:{
        getLokasi(){
            axios.get('/data/list-lokasi')
                .then(response => {
                    this.lokasi = response.data
                })
        },
        getKelompok(){
            axios.get('/data/list-kelompok')
                .then(response => {
                    this.kelompok = response.data
                })
        },
        cetak(){
            axios.get('/data/laporan/stok',{
                params:this.state
            })
                .then(response => {
                    if(response.data.success == true){
                        this.dataprint = {
                            tanggal_cetak : response.data.tanggal_cetak,
                            lis : response.data.lis
                        }

                        this.$nextTick(() => {
                            this.$htmlToPaper('printMe');
                        });
                    }
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        }
    }
}
</script>

