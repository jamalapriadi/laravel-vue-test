<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Detail PO
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>{{state.kode}}</th>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <th>{{state.tanggal}}</th>
                        </tr>
                        <tr>
                            <th>Customer</th>
                            <th>{{state.customer}}</th>
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
                                    <th>Rak</th>
                                    <th>Dos</th>
                                    <th>Pcs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                                    <td>{{index+1}}</td>
                                    <td>{{l.kd}}</td>
                                    <td>{{l.nm}}</td>
                                    <td>
                                        <ul>
                                            <li v-for="(k,idx) in l.rak" v-bind:key="idx">{{k.rak}} - {{k.diambil}}</li>
                                        </ul>
                                    </td>
                                    <td>{{l.dos}}</td>
                                    <td>{{l.pcs}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <router-link to="/po" class="btn btn-default">
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
                lokasi:'',
                tanggal:new Date(),
                perusahaan:'',
                keterangan:'',
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

            axios.get('/data/po/'+id)
                .then(response => {
                    this.state.kode= response.data.po.no_po;
                    this.state.customer= response.data.po.customer.nm;
                    this.state.tanggal= response.data.po.tgl;
                    this.state.perusahaan= response.data.po.perusahaan.nama;
                    this.state.lokasi = response.data.po.lokasi.nm;
                    this.state.keterangan= response.data.po.ket;
                    this.state.listBarang= response.data.detail;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/po/'+this.kelompokId, newState)
                .then(response => {
                    this.$router.replace('/po');
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