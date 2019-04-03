<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Detail Status PO
            </div>
            <div class="card-body">
                <fieldset>
                    <legend>Pilih Status</legend>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="" class="control-label">Pilih Status</label>
                                <select name="status" id="status" class="form-control" v-model="form.status">
                                    <option value="" disabled selected>--Pilih Status--</option>
                                    <option value="Accept">Accept</option>
                                    <option value="Refuse">Refuse</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <button class="btn btn-primary" v-on:click="saveUpdateStatus()" style="margin-top:25px;">
                                    <i class="icon icon-doc"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </fieldset>
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
                                    <th>Dos</th>
                                    <th>Pcs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(l,index) in state.listBarang" v-bind:key="index">
                                    <td>{{index+1}}</td>
                                    <td>{{l.kd}}</td>
                                    <td>{{l.nm}}</td>
                                    <td>{{l.pivot.dos}}</td>
                                    <td>{{l.pivot.pcs}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <router-link to="/order-jatuh-tempo" class="btn btn-default">
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
            form:{
                status
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
                    this.state.kode= response.data.no_po;
                    this.state.customer= response.data.customer.nm;
                    this.state.tanggal= response.data.tgl;
                    this.state.perusahaan= response.data.perusahaan.nama;
                    this.state.lokasi = response.data.lokasi.nm;
                    this.state.keterangan= response.data.ket;
                    this.state.listBarang= response.data.detail;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveUpdateStatus(){
            axios.post('/data/update-status-order/'+this.$route.params.id, this.form)
                .then(response => {
                    // this.getData();
                    if(response.data.success==true){
                        this.$router.replace('/order-jatuh-tempo');
                    }else{
                        alert('Status belum terpilih');
                    }
                    
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