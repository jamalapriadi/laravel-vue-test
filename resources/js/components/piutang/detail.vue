<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Detail Pembayaran Piutang
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Piutang</th>
                            <th>{{state.kode}}</th>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <th>{{state.tgl_pembayaran}}</th>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <th>{{state.customer.nm}}</th>
                        </tr>
                        <tr>
                            <th>Total Bayar</th>
                            <th>{{state.total_bayar | formatNumber}}</th>
                        </tr>
                    </thead>
                </table>

                <div class="card card-default">
                    <div class="card-header">Detail Pembayaran</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Order</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Bank</th>
                                    <th>No. Cek</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                                    <td>{{index+1}}</td>
                                    <td>{{l.no_order}}</td>
                                    <td>{{l.pivot.jns_pembayaran}}</td>
                                    <td>{{l.pivot.bank}}</td>
                                    <td>{{l.pivot.no_cek_bg}}</td>
                                    <td>{{l.pivot.nominal | formatNumber}}</td>
                                </tr>
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th colspan="9">Total</th>
                                    <th>{{state.total}}</th>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <router-link to="/list-piutang" class="btn btn-default">
                        <i class="fa fa-backward"></i> Back
                    </router-link>
                </div>


            </div>
        </div>
    </div>
</template>

<script>
var numeral = require("numeral");

Vue.filter("formatNumber", function (value) {
    return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});

export default {
    data() {
        return {
            kelompokId:'',
            url: window.location.origin + window.location.pathname,
            state: {
                kode:'',
                tgl_pembayaran:'',
                customer: {},
                customer:'',
                total_bayar:0,
                listBarang:[]
            },
            message:'',
            errors: [],
        }
    },
    methods: {
        getData(){
            let app=this;
            let id= app.$route.params.id;
            this.kelompokId = id;

            // axios.get('/data/piutang-by-order/'+id)
            //     .then(response => {
            //         this.state.kode= response.data.no_order;
            //         this.state.kd_picking=response.data.kd_picking;
            //         this.state.customer= response.data.picking.po.customer.nm;
            //         this.state.tanggal= response.data.tgl;
            //         this.state.tanggaljt= response.data.tgljt;
            //         this.state.sales= response.data.sales.nm;
            //         this.state.perusahaan= response.data.perusahaan.nama;
            //         this.state.keterangan= response.data.ket;
            //         this.state.kd_trans= response.data.kd_trans;
            //         this.state.listBarang= response.data.piutang;
            //         this.state.total= response.data.total;
            //     })
            //     .catch( error => {
            //         alert('data tidak dapat di load');
            //     })

            axios.get('/data/piutang/'+id)
                .then(response => {
                    this.state.kode= response.data.no_piutang;
                    this.state.tgl_pembayaran=response.data.tgl_pembayaran;
                    this.state.customer= response.data.customer;
                    this.state.total_bayar= response.data.total_bayar;
                    this.state.listBarang= response.data.detail;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/piutang-by-order/'+this.kelompokId, newState)
                .then(response => {
                    this.$router.replace('/list-order');
                })
                .catch( error => {
                    alert('data gagal diupdate');
                })
        }
    },
    mounted() {
        this.getData();
    }
}
</script>