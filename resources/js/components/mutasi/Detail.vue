<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Detail Picking
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Mutasi</th>
                            <th>{{state.kode}}</th>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <th>{{state.tanggal}}</th>
                        </tr>
                        <tr>
                            <th>Gudang Awal</th>
                            <th>{{state.gudang_lama}}</th>
                        </tr>
                        <tr>
                            <th>Gudang Tujuan</th>
                            <th>{{state.gudang_baru}}</th>
                        </tr>
                        
                        <tr>
                            <th>Keterangan</th>
                            <th>{{state.ket}}</th>
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
                                    <th>Rak Awal</th>
                                    <th>Rak Tujuan</th>
                                    <th>Dos</th>
                                    <th>Pcs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                                    <td>{{index+1}}</td>
                                    <td>{{l.kd}}</td>
                                    <td>{{l.nm}}</td>
                                    <td>{{l.rak_lama}}</td>
                                    <td>{{l.rak_baru}}</td>
                                    <td>{{l.pivot.dos}}</td>
                                    <td>{{l.pivot.pcs}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <router-link to="/list-mutasi" class="btn btn-default">
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
                tanggal:new Date(),
                gudang_lama: '',
                gudang_baru:'',
                ket:'',
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

            axios.get('/data/mutasi/'+id)
                .then(response => {
                    this.state.kode= response.data.no_mutasi;
                    this.state.gudang_lama= response.data.gudang_lama.nm;
                    this.state.gudang_baru= response.data.gudang_baru.nm;
                    this.state.tanggal= response.data.tgl;
                    this.state.keterangan= response.data.ket;
                    this.state.listBarang= response.data.detail;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },
    },
    mounted() {
        this.getData();
    }
}
</script>