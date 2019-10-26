<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Lokasi
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Lokasi</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.lokasi }" v-model="state.lokasi">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nama</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <hr>
                    <div class="form-group">
                        <router-link to="/lokasi" class="btn btn-default">
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
export default {
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
        }
    },
    methods: {
        getData(){
            let app=this;
            let id= app.$route.params.id;
            this.lokasiId = id;

            axios.get('/data/lokasi/'+id)
                .then(response => {
                    this.state.lokasi= response.data.lokasi;
                    this.state.nama = response.data.nm;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/lokasi/'+this.lokasiId, newState)
                .then(response => {
                    this.$router.replace('/lokasi');
                })
                .catch( error => {
                    alert('data gagal diupdate');
                })
        }
    },
    mounted() {
        this.getData();
    }
}
</script>