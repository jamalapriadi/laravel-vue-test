<template>
    <div class="card card-default">
        <div class="card-header">Laporan Penjualan Sales</div>
        <div class="card-body">
            <div>
                <div class="form-group">
                    <label for="" class="control-label">Periode</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <date-picker v-model="state.mulai" :config="options"></date-picker>
                        </div>
                        <div class="col-lg-4">
                            <date-picker v-model="state.selesai" :config="options"></date-picker>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label for="" class="col-lg-2 control-label">Per Sales</label>
                    <div class="col-lg-3">
                        <select name="sales" id="sales" class="form-control" v-model="state.sales">
                            <option value="">--Semua Sales--</option>
                            <option v-for="(l,index) in sales" v-bind:key="index" v-bind:value="l.id">{{l.nm}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-lg-2 control-label">Per Kelompok</label>
                    <div class="col-lg-3">
                        <select name="kelompok" id="kelompok" class="form-control" v-model="state.kelompok">
                            <option value="">--Semua Kelompok--</option>
                            <option v-for="(l,index) in kelompok" v-bind:key="index" v-bind:value="l.id">{{l.nm}}</option>
                        </select>
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
            <h2>REKAP PENJUALAN <span style="font-size:16px;"><small>Periode : {{dataprint.mulai}} s/d {{dataprint.selesai}}</small></span></h2>
            <P>Per Salesman</P>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <!-- <th></th> -->
                        <th>Salesman</th>
                        <th>Kelompok Barang</th>
                        <th>Barang</th>
                        <th>Total Qty</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(l,index) in dataprint.lis" v-bind:key="index" v-if="l.no_order != 'TOTAL'">
                        <div v-if="l.id !='SUBTOTAL'">
                            <!-- <td>{{index+1}}</td> -->
                            <td>{{l.nama_sales}}</td>
                            <td>{{l.nama_kelompok}}</td>
                            <td>{{l.nama_barang}}</td>
                            <td class="text-right">
                                {{l.jumlah}}
                            </td>
                            <td class="text-right">
                                {{l.total}}
                            </td>
                        </div>

                        <div v-else>
                            <td></td>
                            <td colspan="2" class="text-center">SUBTOTAL {{l.nama_sales}}</td>
                            <td class="text-right">
                                {{l.jumlah}}
                            </td>
                            <td class="text-right">
                                {{l.total}}
                            </td>
                        </div>
                        
                    </tr>
                </tbody>
                <tfoot>
                    <tr v-for="(l,index) in dataprint.lis" v-bind:key="index" v-if="l.no_order == 'TOTAL'">
                        <th colspan="3">GRAND TOTAL</th>
                        <th class="text-right">
                            {{l.jumlah}}
                        </th>
                        <th class="text-right">
                            {{l.total}}
                        </th>
                    </tr>
                </tfoot>
                <tfoot>
                    
                </tfoot>
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
                sales:'',
                kelompok:''
            },
            options: {
                format: 'DD-MM-YYYY',
                useCurrent: false,
            }, 
            sales:[],
            kelompok:[],
            dataprint:{
                mulai:'',
                selesai:'',
                lis:[]
            }
        }
    },
    mounted(){
        this.getSales();
        this.getKelompok();
    },
    methods:{
        getSales(){
            axios.get('/data/list-sales')
                .then(response => {
                    this.sales=response.data
                })
        },
        getKelompok(){
            axios.get('/data/list-kelompok')
                .then(response => {
                    this.kelompok = response.data
                })
        },
        cetak(){
            axios.get('/data/laporan/per-sales',{
                params:this.state
            })
                .then(response => {
                    if(response.data.success == true){
                        this.dataprint = {
                            mulai : response.data.mulai,
                            selesai :  response.data.selesai,
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

