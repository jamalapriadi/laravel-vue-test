<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Add New Rak
            </div>
            <div class="card-body">

                <div v-if="message" class="alert alert-success">
                    {{ message }}
                </div>

                <div>
                    <div class="form-group">
                        <label for="" class="control-label">Gudang</label>
                        <!-- <input type="text" class="form-control" :class="{ 'is-invalid': errors.lokasi }" v-model="state.lokasi"> -->
                        <select class="form-control" name="lokasi" id="lokasi" v-model="state.lokasi">
                            <option value="">--Pilih Gudang--</option>
                            <option v-for="(l,index) in lokasi" v-bind:key="index" v-bind:value="l.id">{{l.nm}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nomor Rak</label>
                        <div class="input-prepend input-group">
                            <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="nama" v-on:keyup.enter="tambahRak()">
                            <div class="input-group-prepend">
                                <span class="input-group-text" @click="tambahRak()">
                                    <i class="icon-plus"></i>
                                </span>
                            </div>
                        </div>    
                    </div>
                    
                    <hr>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(l,index) in state.raks" v-bind:key="index">
                                <td>{{index+1}}</td>
                                <td>{{l}}</td>
                                <td>
                                    <a class="btn btn-sm btn-danger text-white" @click="hapusRak(index)">
                                        <i class="icon-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div class="form-group">
                        <router-link to="/rak" class="btn btn-default">
                            <i class="fa fa-backward"></i> Back
                        </router-link>

                        <button class="btn btn-primary" v-on:click="store">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                    </div>
                </div>
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
                lokasi:'',
                raks:[]
            },
            nama: '',
            message:'',
            loading:false,
            errors: [],
            lokasi: []
        }
    },
    methods: {
        tambahRak(){
            this.state.raks.push(this.nama);
            this.nama='';
        },

        hapusRak(index){
            this.state.raks.splice(index, 1);
        },

        store(e) {
            if(this.state.lokasi==""){
                alert('Lokasi harus diisi');

                return false;
            }

            if(this.state.raks.length === 0){
                alert('Rak harus diisi');

                return false;
            }


            this.loading = true;

            axios.post('/data/rak', this.state).then(response => {
                if(response.data.success==true){
                    this.loading=false;
                    this.errors = [];
                    this.state = {
                        lokasi:'',
                        raks: []
                    }
                    this.message = 'Data has been saved.';
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

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then( response => {
                    this.lokasi = response.data;
                })
        }
    },
    mounted(){
        this.getLokasi();
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