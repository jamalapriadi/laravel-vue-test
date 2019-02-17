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
                            <th>No. Storing</th>
                            <th>{{state.kode}}</th>
                        </tr>
                        <tr>
                            <th>No. Ref</th>
                            <th>{{state.no_ref}}</th>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <th>{{state.tanggal}}</th>
                        </tr>
                        
                        <tr>
                            <th>Lokasi</th>
                            <th>{{state.lokasi}}</th>
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
                                    <td>{{l.nama_rak}}</td>
                                    <td>{{l.pivot.dos}}</td>
                                    <td>{{l.pivot.pcs}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <router-link to="/storing" class="btn btn-default">
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
                no_ref: '',
                tanggal:new Date(),
                lokasi:'',
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

            axios.get('/data/storing/'+id)
                .then(response => {
                    this.state.kode= response.data.no_storing;
                    this.state.no_ref= response.data.no_ref;
                    this.state.tanggal= response.data.tgl;
                    this.state.lokasi= response.data.lokasi.nm;
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