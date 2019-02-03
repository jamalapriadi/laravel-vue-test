<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Add New Suplier
            </div>
            <div class="card-body">

                <div v-if="message" class="alert alert-success">
                    {{ message }}
                </div>

                <form @submit.prevent="store" action="/data/suplier" method="post">
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
                    
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

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
import { VueLoading } from 'vue-loading-template'

export default {
    components: {
        VueLoading
    },
    data() {
        return {
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
            loading:false,
            errors: [],
            kota:[]
        }
    },
    methods: {
        store(e) {
            this.loading = true;

            axios.post(e.target.action, this.state).then(response => {
                if(response.data.success==true){
                    this.loading=false;
                    this.errors = [];
                    this.state = {
                        nama: '',
                        alamat:'',
                        kota:'',
                        telepon:'',
                        perusahaan:'',
                        kontak:'',
                        fax:''
                    }
                    this.message = 'Data has been saved.';
                }else{
                    this.loading=false;
                    this.errors.nama=true;
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
        }
    },
    mounted() {
        this.getKota();
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