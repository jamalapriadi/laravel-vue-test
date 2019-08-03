<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Add New Customer
            </div>
            <div class="card-body">

                <div v-if="message" class="alert alert-success">
                    {{ message }}
                </div>

                <form @submit.prevent="store" action="/data/customer" method="post">
                    <div class="form-group">
                        <label for="" class="control-label">Kode Customer</label>
                        <input type="text" class="form-control" name="kode" :class="{ 'is-invalid': errors.kode }" v-model="state.kode">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Jenis Customer</label>
                        <select name="jeniscustomer" id="jeniscustomer" class="form-control" v-model="state.jenis_customer">
                            <option value="">--Pilih Jenis Customer--</option>
                            <option v-for="(l,index) in jeniscustomer" v-bind:key="index" v-bind:value="l.jns_customer">{{l.jns_customer}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nama Toko</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.toko }" v-model="state.toko">
                        <p style="color:red"><small>Nama - Wilayah</small></p>
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
                    
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

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
import { VueLoading } from 'vue-loading-template'

export default {
    components: {
        VueLoading
    },
    data() {
        return {
            state: {
                kode: '',
                jenis_customer:'',
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
            jeniscustomer:[],
            message:'',
            loading:false,
            errors: [],
            kota:[]
        }
    },
    methods: {
        store(e) {
            if(this.state.nama=="" || this.state.nama==null){
                alert('Nama harus diisi');

                return false;
            }

            if(this.state.jenis_customer=="" || this.state.jenis_customer==null){
                alert('Jenis Customer harus diisi');

                return false;
            }

            if(this.state.toko=="" || this.state.toko==null){
                alert('Nama Toko harus diisi');

                return false;
            }

            if(this.state.nik=="" || this.state.nik==null){
                alert('Nik harus diisi');

                return false;
            }

            if(this.state.npwp=="" || this.state.npwp==null){
                alert('NPWP harus diisi');

                return false;
            }

            if(this.state.alamat=="" || this.state.alamat==null){
                alert('Alamat harus diisi');

                return false;
            }

            if(this.state.alias=="" || this.state.alias==null){
                alert('Alias harus diisi');

                return false;
            }

            if(this.state.kota=="" || this.state.kota==null){
                alert('Kota harus diisi');

                return false;
            }

            if(this.state.telepon=="" || this.state.telepon==null){
                alert('Telepon harus diisi');

                return false;
            }

            if(this.state.kota=="" || this.state.kota==null){
                alert('Kota harus diisi');

                return false;
            }
            
            this.loading = true;

            axios.post(e.target.action, this.state).then(response => {
                if(response.data.success==true){
                    this.loading=false;
                    this.errors = [];
                    this.state = {
                        kode: '',
                        jenis_customer:'',
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
                    }
                    // this.getKodeCustomer();
                    this.message = 'Data has been saved.';
                    this.getJenisCustomer();
                }else{
                    this.loading=false;
                    this.errors.nama=true;
                    this.errors.lokasi=true;
                }
            }).catch(error => {
                if (! _.isEmpty(error.response)) {
                    if (error.response.status = 422) {
                        this.loading=false;
                        this.errors = error.response.data;
                        console.log(this.errors);
                    }
                }
            });
        },

        getKota(){
            axios.get('/data/list-kota')
                .then(response => {
                    this.kota = response.data;
                })
        },

        getJenisCustomer(){
            axios.get('/data/list-jenis-customer')
                .then( response => {
                    this.jeniscustomer = response.data;
                })
        },

        getKodeCustomer(){
            axios.get('/data/autonumber-customer')
                .then( response => {
                    this.state.kode = response.data
                });
        }
    },
    mounted() {
        // this.getKodeCustomer();
        this.getKota();
        this.getJenisCustomer()
    },
    computed:{
        valKode() {
            if (this.post.kode.length === 0 || this.post.kode.length > 50) {
                return true;
            } 
            return false;
        },
        valNama() {
            if (this.post.nama.length === 0 || this.post.nama.length > 50) {
                return true;
            } 
            return false;
        },
        valStatus() {
            if (this.post.status.length === 0 || this.post.status.length > 50) {
                return true;
            } 
            return false;
        }
    }
}
</script>