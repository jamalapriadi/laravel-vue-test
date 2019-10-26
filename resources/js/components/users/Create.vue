<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Add New User
            </div>
            <div class="card-body">

                <div v-if="message" class="alert alert-success">
                    {{ message }}
                </div>

                <form @submit.prevent="store" action="/data/users" method="post">
                    <div class="form-group">
                        <label for="" class="control-label">Username</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.username }" v-model="state.username">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Password</label>
                        <input type="password" class="form-control" :class="{ 'is-invalid': errors.password }" v-model="state.password">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Confirm Password</label>
                        <input type="password" class="form-control" :class="{ 'is-invalid': errors.password_confirm }" v-model="state.password_confirm">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Level</label>
                        <select name="level" id="level" class="form-control" :class="{ 'is-invalid': errors.level }" v-model="state.level">
                            <option value="" disabled selected>--Pilih level--</option>
                            <option value="Admin">Admin</option>
                            <option value="Manajer">Manajer</option>
                            <option value="Direktur">Direktur</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Perusahaan</label>
                        <select name="perusahaan" id="perusahaan" class="form-control" :class="{ 'is-invalid': errors.perusahaan }" v-model="state.perusahaan">
                            <option value="" disabled selected>--Pilih Perusahaan--</option>
                            <option v-for="(p,index) in perusahaan" v-bind:key="index" v-bind:value="p.id">{{p.nama}}</option>
                        </select>
                    </div>
                    <hr>
                    
                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div class="form-group">
                        <router-link to="/users" class="btn btn-default">
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
                username: '',
                password:'',
                password_confirm:'',
                level:'',
                perusahaan:''
            },
            message:'',
            loading:false,
            errors: [],
            perusahaan:[]
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
                        username: '',
                        password:'',
                        password_confirm:'',
                        level:'',
                        perusahaan:''
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

        getPerusahaan(){
            axios.get('/data/list-perusahaan')
                .then(response => {
                    this.perusahaan = response.data;
                })
        }
    },
    mounted() {
        this.getPerusahaan();
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