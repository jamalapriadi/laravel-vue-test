<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Kelompok
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Nama</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <hr>
                    <div class="form-group">
                        <router-link to="/kelompok" class="btn btn-default">
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
            kelompokId:'',
            url: window.location.origin + window.location.pathname,
            state: {
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
            this.kelompokId = id;

            axios.get('/data/kelompok/'+id)
                .then(response => {
                    this.state.nama = response.data.nm;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/kelompok/'+this.kelompokId, newState)
                .then(response => {
                    this.$router.replace('/kelompok');
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