<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Detail Order
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>{{state.kode}}</th>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <th>{{state.tanggal}}</th>
                        </tr>
                        <tr>
                            <th>Kd. Trans</th>
                            <th>{{state.kd_trans}}</th>
                        </tr>
                        <tr v-if="state.kd_trans=='Kredit'">
                            <th>Tanggal Jatuh Tempo</th>
                            <th>{{state.tanggaljt}}</th>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <th>{{state.customer}}</th>
                        </tr>
                        <tr>
                            <th>Sales</th>
                            <th>{{state.sales}}</th>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <th>{{state.lokasi}}</th>
                        </tr>
                        <tr>
                            <th>Perusahaan</th>
                            <th>{{state.perusahaan}}</th>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <th>{{state.keterangan}}</th>
                        </tr>
                    </thead>
                </table>

                <div class="card card-default">
                    <div class="card-header">Detail Barang</div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Dos</th>
                                    <th>Pcs</th>
                                    <th>Diskon</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                                    <td>{{index+1}}</td>
                                    <td>{{l.kd}}</td>
                                    <td>{{l.nm}}</td>
                                    <td>{{l.pivot.hrg}}</td>
                                    <td>{{l.pivot.dos}}</td>
                                    <td>{{l.pivot.pcs}}</td>
                                    <td>{{l.pivot.diskon}}</td>
                                    <td>{{l.pivot.jumlah}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <router-link to="/list-order" class="btn btn-default">
                        <i class="fa fa-backward"></i> Back
                    </router-link>
                </div>


            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            kelompokId:'',
            url: window.location.origin + window.location.pathname,
            state: {
                kode:'',
                nama: '',
                customer:'',
                tanggal:new Date(),
                tanggaljt:new Date(),
                perusahaan:'',
                keterangan:'',
                lokasi:'',
                sales:'',
                kd_trans:'Tunai',
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

            axios.get('/data/order/'+id)
                .then(response => {
                    this.state.kode= response.data.no_order;
                    this.state.customer= response.data.customer.nm;
                    this.state.tanggal= response.data.tgl;
                    this.state.tanggaljt= response.data.tgljt;
                    this.state.perusahaan= response.data.perusahaan.nama;
                    this.state.keterangan= response.data.keterangan;
                    this.state.lokasi= response.data.lokasi.nm;
                    this.state.sales= response.data.sales.nm;
                    this.state.kd_trans= response.data.kd_trans;
                    this.state.listBarang= response.data.detail;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/order/'+this.kelompokId, newState)
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