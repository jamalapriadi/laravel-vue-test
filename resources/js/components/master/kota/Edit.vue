<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Kota
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Nama Kota</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Status</label>
                        <select name="status" id="status" class="form-control" :class="{ 'is-invalid': errors.jenis }" v-model="state.jenis">
                            <option value="" disabled selected>--Pilih Kota--</option>
                            <option value="Dalam Kota">Dalam Kota</option>
                            <option value="Luar Kota">Luar Kota</option>
                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        <router-link to="/kota" class="btn btn-default">
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
            kotaId:'',
            url: window.location.origin + window.location.pathname,
            state: {
                kode: '',
                nama: '',
                status: ''
            },
            message:'',
            errors: [],
        }
    },
    methods: {
        getData(){
            let app=this;
            let id= app.$route.params.id;
            this.kotaId = id;

            axios.get('/data/kota/'+id)
                .then(response => {
                    this.state.nama = response.data.nm;
                    this.state.jenis = response.data.jenis;
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/kota/'+this.kotaId, newState)
                .then(response => {
                    this.$router.replace('/kota');
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