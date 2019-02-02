<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Sales
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Nama Bank</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Status</label>
                        <select name="status" id="status" class="form-control" :class="{ 'is-invalid': errors.status }" v-model="state.status">
                            <option value="" disabled selected>--Pilih Status--</option>
                            <option value="Active">Active</option>
                            <option value="Non Active">Non Active</option>
                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        <router-link to="/sales" class="btn btn-default">
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
            bankId:'',
            url: window.location.origin + window.location.pathname,
            state: {
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
            this.bankId = id;

            axios.get('/data/sales/'+id)
                .then(response => {
                    this.state.nama = response.data.nm;
                    this.state.status = response.data.status;
                    console.log(response);
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/sales/'+this.bankId, newState)
                .then(response => {
                    this.$router.replace('/sales');
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