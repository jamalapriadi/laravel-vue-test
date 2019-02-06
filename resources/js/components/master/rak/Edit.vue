<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Rak
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Gudang</label>
                        <select class="form-control" name="lokasi" id="lokasi" v-model="state.lokasi">
                            <option value="">--Pilih Gudang--</option>
                            <option v-for="(l,index) in lokasi" v-bind:key="index" v-bind:value="l.id">{{l.nm}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nomor Rak</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <hr>

                    <vue-loading v-if="loading" type="bars" color="#d9544e" :size="{ width: '50px', height: '50px' }"></vue-loading>    

                    <div class="form-group">
                        <router-link to="/rak" class="btn btn-default">
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
            lokasiId:'',
            url: window.location.origin + window.location.pathname,
            state: {
                lokasi:'',
                nama: ''
            },
            message:'',
            errors: [],
            lokasi: []
        }
    },
    methods: {
        getData(){
            let app=this;
            let id= app.$route.params.id;
            this.lokasiId = id;

            axios.get('/data/rak/'+id)
                .then(response => {
                    this.state.lokasi= response.data.lokasi_id;
                    this.state.nama = response.data.nm;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/rak/'+this.lokasiId, newState)
                .then(response => {
                    this.$router.replace('/rak');
                })
                .catch( error => {
                    alert('data gagal diupdate');
                })
        },

        getLokasi(){
            axios.get('/data/list-lokasi')
                .then( response => {
                    this.lokasi = response.data;
                })
        }
    },
    mounted() {
        this.getLokasi();
        this.getData();
    }
}
</script>