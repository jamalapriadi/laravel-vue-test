<template>
    <div class="card card-default">
        <div class="card-header">Laporan Penjualan</div>
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
                <div class="form-group">
                    <label for="" class="control-label"></label>
                    <button class="btn btn-primary" @click="cetak()">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </div>

            <div id="printMe" style="margin-top:10px;display:none;">
                <h2>REKAP PENJUALAN <span style="font-size:16px;"><small>Periode : {{dataprint.mulai}} s/d {{dataprint.selesai}}</small></span></h2>

                <p>Jenis : Jual</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Transaksi</th>
                            <th>Customer</th>
                            <th>Nilai Tunai</th>
                            <th>Nilai Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(l,index) in dataprint.lis" v-bind:key="index" v-if="l.no_order != 'TOTAL'">
                            <td>{{index+1}}</td>
                            <td>{{l.no_order}}</td>
                            <td>
                                {{l.picking.po.customer.nm}}
                            </td>
                            <td class="text-right">
                                <div v-if="l.kd_trans!='Kredit'">{{l.total}}</div>
                                <div v-else>0</div>
                            </td>
                            <td class="text-right">
                                <div v-if="l.kd_trans=='Kredit'">{{l.sisa_pembayaran}}</div>
                                <div v-else>0</div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr v-for="(l,index) in dataprint.lis" v-bind:key="index" v-if="l.no_order == 'TOTAL'">
                            <th colspan="3" class="text-center">SUBTOTAL</th>
                            <th class="text-right">
                                <div v-if="l.kd_trans!='Kredit'">{{l.total}}</div>
                                <div v-else>0</div>
                            </th>
                            <th class="text-right">
                                <div v-if="l.kd_trans=='Kredit'">{{l.sisa_pembayaran}}</div>
                                <div v-else>0</div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
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
                selesai:new Date()
            },
            options: {
                format: 'DD-MM-YYYY',
                useCurrent: false,
            }, 
            dataprint:{
                mulai:'',
                selesai:'',
                lis:[]
            }
        }
    },
    methods:{
        cetak(){
            axios.get('/data/laporan/penjualan',{
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

