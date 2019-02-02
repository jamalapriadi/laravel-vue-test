<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Merk
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Nama</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Persen</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.persen }" v-model="state.persen">
                    </div>
                    <hr>
                    <div class="form-group">
                        <router-link to="/merk" class="btn btn-default">
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
                persen:'',
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

            axios.get('/data/merk/'+id)
                .then(response => {
                    this.state.persen= response.data.persen;
                    this.state.nama = response.data.nm;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/merk/'+this.lokasiId, newState)
                .then(response => {
                    this.$router.replace('/merk');
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