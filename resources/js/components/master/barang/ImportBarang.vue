<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Import Data Barang
            </div>
            <div class="card-body">

                <div v-if="message" class="alert alert-success">
                    {{ message }}
                </div>

                <form @submit.prevent="store" action="/data/import-new-barang" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="" class="control-label">Upload File</label>
                        <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
                    </div>

                    <breadcrumbs>
                        Berikut adalah sample data barang yang bisa anda gunakan sebagai format
                        standar untuk import data barang : <a href="/data/sample-barang">Klik di sini</a>
                    </breadcrumbs>


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
                file: ''
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

            let formData = new FormData();
            formData.append('file', this.state.file);

            axios.post(e.target.action, formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if(response.data.success==true){
                    this.loading=false;
                    this.errors = [];
                    this.state = {
                        file: ''
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

        handleFileUpload(){
            this.state.file = this.$refs.file.files[0];
        }
    },
    mounted() {
        
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
