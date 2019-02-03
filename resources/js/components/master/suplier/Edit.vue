<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Suplier
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Nama</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Alamat</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.alamat }" v-model="state.alamat">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Kota</label>
                        <select name="kota" id="kota" class="form-control" :class="{ 'is-invalid': errors.kota }" v-model="state.kota">
                            <option value="" disabled selected>--Pilih Kota--</option>
                            <option v-for="(p,index) in kota" v-bind:key="index" v-bind:value="p.kd_kota">{{p.nm}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Telepon</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.telepon }" v-model="state.telepon">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.perusahaan }" v-model="state.perusahaan">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Kontak</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kontak }" v-model="state.kontak">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Fax</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.fax }" v-model="state.fax">
                    </div>
                    <hr>
                    <div class="form-group">
                        <router-link to="/suplier" class="btn btn-default">
                            <i class="fa fa-backward"></i> Back
                        </router-link>

                        <button class="btn btn-primary">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            suplierId:'',
            url: window.location.origin + window.location.pathname,
            state: {
                nama: '',
                alamat:'',
                kota:'',
                telepon:'',
                perusahaan:'',
                kontak:'',
                fax:''
            },
            message:'',
            errors: [],
            kota:[]
        }
    },
    methods: {
        getData(){
            let app=this;
            let id= app.$route.params.id;
            this.suplierId = id;

            axios.get('/data/suplier/'+id)
                .then(response => {
                    this.state.nama = response.data.nm;
                    this.state.alamat = response.data.alamat;
                    this.state.kota = response.data.kota_id;
                    this.state.telepon = response.data.telepon;
                    this.state.perusahaan = response.data.nm_perusahaan;
                    this.state.kontak = response.data.kontak;
                    this.state.fax = response.data.fax;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/suplier/'+this.suplierId, newState)
                .then(response => {
                    this.$router.replace('/suplier');
                })
                .catch( error => {
                    alert('data gagal diupdate');
                })
        },

        getKota(){
            axios.get('/data/list-kota')
                .then(response => {
                    this.kota = response.data;
                })
        }
    },
    mounted() {
        this.getKota();
        this.getData();
    }
}
</script>