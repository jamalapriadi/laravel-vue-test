<template>
    <div class="card card-accent-primary">
        <div class="card-header">
            Add New Program
        </div>
        <div class="card-body">

            <div v-if="message" class="alert alert-success">
                {{ message }}
            </div>

            <form @submit.prevent="store" action="/data/kelompok" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Nomor Program</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Periode</label>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <date-picker v-model="date" :config="options"></date-picker>
                                    </div>

                                    <div class="col-lg-6">
                                        <date-picker v-model="date" :config="options"></date-picker>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="control-label col-lg-3">Nama</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        
                    </div>
                </div>
                
            </form>

            <br><br>
            <form action="">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="" class="control-label">Kode barang</label>
                        <!-- <multiselect v-model="value" :options="tanggal"></multiselect> -->

                        <multiselect v-model="selectedBarangs" 
                            id="ajax" 
                            label="nm" 
                            track-by="kd" 
                            placeholder="Type to search" 
                            open-direction="bottom" 
                            :multiple="false"
                            :options="barangs" 
                            :searchable="true" 
                            :loading="isLoading" 
                            :internal-search="false" 
                            :clear-on-select="false" 
                            :close-on-select="true" 
                            :options-limit="25" 
                            :limit="3" 
                            :limit-text="limitText" 
                            :max-height="600" 
                            :show-no-results="false" 
                            :hide-selected="false" 
                            :custom-label="nameWithLang"
                            @search-change="asyncFind">
                        </multiselect>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">Nama Barang</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">QTY</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="form-group col-md-2">
                        <label for="" class="control-label">Point</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="form-group col-md-3">
                        <div class="btn-group">
                            <button class="btn btn-primary" style="margin-top:25px;">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>QTY</th>
                            <th>Point</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>

                
                <hr>
                
                <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                <div class="form-group">
                    <router-link to="/kelompok" class="btn btn-default">
                        <i class="fa fa-backward"></i> Back
                    </router-link>

                    <button class="btn btn-primary">
                        <i class="fa fa-save"></i>
                        Save
                    </button>
                </div>

        </div>
    </div>
</template>

<script>
import { VueLoading } from 'vue-loading-template'
// Import required dependencies 
import 'bootstrap/dist/css/bootstrap.css';

// Import this component
import datePicker from 'vue-bootstrap-datetimepicker';

// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

import Multiselect from 'vue-multiselect'

export default {
    components: {
        VueLoading,
        datePicker,
        Multiselect 
    },
    data() {
        return {
            state: {
                nama: ''
            },
            date: new Date(),
            options: {
                format: 'DD/MM/YYYY',
                useCurrent: false,
            }, 
            message:'',
            loading:false,
            errors: [],
            value: null,
            tanggal: ['list', 'of', 'options'],
            selectedBarangs: '',
            barangs: [],
            isLoading: false
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
                        nama: ''
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

        limitText (count) {
            return `and ${count} other countries`
        },

        asyncFind (query) {
            this.isLoading = true
            axios.get('/data/list-barang',  query)
                .then(response => {
                    this.barangs=response.data
                    this.isLoading = false
                })
        },

        nameWithLang ({ kd, nm }) {
            return `${kd} — [${nm}]`
        }
 
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

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    input,
    select {
        padding: 0.75em 0.5em;
        font-size: 100%;
        border: 1px solid #ccc;
        width: 100%;
    }

    select {
        height: 2.5em;
    }

    .example {
        background: #f2f2f2;
        border: 1px solid #ddd;
        padding: 0em 1em 1em;
        margin-bottom: 2em;
    }

    code,
    pre {
        margin: 1em 0;
        padding: 1em;
        border: 1px solid #bbb;
        display: block;
        background: #ddd;
        border-radius: 3px;
    }

    .settings {
        margin: 2em 0;
        border-top: 1px solid #bbb;
        background: #eee;
    }

    h5 {
        font-size: 100%;
        padding: 0;
    }

    .form-group {
        margin-bottom: 1em;
    }

    .form-group label {
        font-size: 80%;
        display: block;
    }
</style>
