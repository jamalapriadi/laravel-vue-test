<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Detail Program
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>{{state.kode}}</th>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <th>{{state.nama}}</th>
                        </tr>
                        <tr>
                            <th>Start Periode</th>
                            <th>{{state.start}}</th>
                        </tr>
                        <tr>
                            <th>End Periode</th>
                            <th>{{state.end}}</th>
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
                                    <th>QTY</th>
                                    <th>Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                                    <td>{{index+1}}</td>
                                    <td>{{l.kd}}</td>
                                    <td>{{l.nm}}</td>
                                    <td>{{l.pivot.qty}}</td>
                                    <td>{{l.pivot.point}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <router-link to="/program" class="btn btn-default">
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
                start:new Date(),
                end:new Date(),
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

            axios.get('/data/program/'+id)
                .then(response => {
                    this.state.nama = response.data.nm;
                    this.state.kode = response.data.nmr;
                    this.state.start = response.data.awprriod;
                    this.state.end = response.data.akpriod;
                    this.state.listBarang = response.data.detail;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/program/'+this.kelompokId, newState)
                .then(response => {
                    this.$router.replace('/program');
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