<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Add New Barang
            </div>
            <div class="card-body">

                <div v-if="message" class="alert alert-success">
                    {{ message }}
                </div>

                <form @submit.prevent="store" action="/data/barang" method="post">
                    <div class="form-group">
                        <label for="" class="control-label">Kode</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode" readonly>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">Nama</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">Kelompok</label>
                        <select name="kelompok" id="kelompok" class="form-control" :class="{ 'is-invalid': errors.kelompok }" v-model="state.kelompok">
                            <option value="" disabled selected>--Pilih Kelompok--</option>
                            <option v-for="(p,index) in kelompok" v-bind:key="index" v-bind:value="p.id">{{p.nm}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">Merk</label>
                        <select name="merk" id="merk" class="form-control" :class="{ 'is-invalid': errors.merk }" v-model="state.merk">
                            <option value="" disabled selected>--Pilih Merk--</option>
                            <option v-for="(p,index) in merk" v-bind:key="index" v-bind:value="p.id">{{p.nm}}</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="" class="control-label">Satuan</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.satuan }" v-model="state.satuan">
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">Status</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.status }" v-model="state.status">
                    </div> -->

                    <div class="form-group">
                        <label for="" class="control-label">PCS</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.pcs }" v-model="state.pcs">
                    </div>

                    <!-- <div class="form-group">
                        <label for="" class="control-label">Harga Beli</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.hrgb }" v-model="state.hrgb">
                    </div> -->

                    <div class="form-group">
                        <label for="" class="control-label">Harga Pokok</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.hrgp }" v-model="state.hrgp">
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">HET</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.jual }" v-model="state.jual">
                    </div>
                    <hr>
                    
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div class="form-group">
                        <router-link to="/barang" class="btn btn-default">
                            <i class="fa fa-backward"></i> Back
                        </router-link>

                        <button class="btn btn-primary float-right">
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
                kode:'',
                nama: '',
                kelompok:'',
                merk:'',
                satuan:'',
                status:'',
                pcs:'',
                hrgb:'',
                hrgp:'',
                jual:''
            },
            message:'',
            loading:false,
            errors: [],
            kelompok: [],
            merk: []
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
                        kode:'',
                        nama: '',
                        kelompok:'',
                        merk:'',
                        satuan:'',
                        status:'',
                        pcs:'',
                        hrgb:'',
                        hrgp:'',
                        jual:''
                    }
                    this.message = 'Data has been saved.';
                    this.getAutonumber();
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

        getKelompok(){
            axios.get('/data/list-kelompok')
                .then(response => {
                    this.kelompok = response.data;
                })
        },

        getMerk(){
            axios.get('/data/list-merk')
                .then(response => {
                    this.merk = response.data;
                })
        },

        getAutonumber(){
            axios.get('/data/autonumber-barang')
                .then(response => {
                    this.state.kode= response.data;
                })
        }
    },
    mounted(){
        this.getAutonumber();
        this.getKelompok();
        this.getMerk();
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