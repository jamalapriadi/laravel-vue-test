<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Customer
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Kode Customer</label>
                        <input type="text" class="form-control" name="kode" :class="{ 'is-invalid': errors.kode }" v-model="state.kode">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nama Toko</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.toko }" v-model="state.toko">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nama Pemilik</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Alias</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.alias }" v-model="state.alias">
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
                        <label for="" class="control-label">NIK</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nik }" v-model="state.nik">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">NPWP</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.npwp }" v-model="state.npwp">
                    </div>
                    
                    <div class="form-group">
                        <label for="" class="control-label">No. Hp / Telepon</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.telepon }" v-model="state.telepon">
                    </div>
                    <!-- <div class="form-group">
                        <label for="" class="control-label">Nmtk</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nmtk }" v-model="state.nmtk">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Kontak</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kontak }" v-model="state.kontak">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Fax</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.fax }" v-model="state.fax">
                    </div> -->
                    <div class="form-group">
                        <label for="" class="control-label">Plafon</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.plafon }" v-model="state.plafon">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Top</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.top }" v-model="state.top">
                    </div>
                    <!-- <div class="form-group">
                        <label for="" class="control-label">Jenis</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.jenis }" v-model="state.jenis">
                    </div> -->
                    <hr>
                    <div class="form-group">
                        <router-link to="/customer" class="btn btn-default">
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
            lokasiId:'',
            url: window.location.origin + window.location.pathname,
            state: {
                kode: '',
                toko:'',
                nama: '',
                nik:'',
                npwp:'',
                alamat:'',
                alias:'',
                kota:'',
                telepon:'',
                nmtk:'',
                kontak:'',
                fax:'',
                plafon:'',
                top:'',
                jenis:''
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
            this.lokasiId = id;

            axios.get('/data/customer/'+id)
                .then(response => {
                    this.state.kode = response.data.kd;
                    this.state.toko = response.data.nm_toko;
                    this.state.nama = response.data.nm;
                    this.state.nik = response.data.nik;
                    this.state.npwp = response.data.npwp;
                    this.state.alamat = response.data.alamat;
                    this.state.alias = response.data.alias;
                    this.state.kota = response.data.kota_id;
                    this.state.telepon = response.data.tlpn;
                    this.state.nmtk = response.data.nmtk;
                    this.state.kontak = response.data.kontak;
                    this.state.fax = response.data.fax;
                    this.state.plafon = response.data.plafon;
                    this.state.top = response.data.top;
                    this.state.jenis = response.data.jenis;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/customer/'+this.lokasiId, newState)
                .then(response => {
                    if(response.data.success==true){
                        this.$router.replace('/customer');
                    }else{
                        alert('Update Data Gagal');
                    }
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