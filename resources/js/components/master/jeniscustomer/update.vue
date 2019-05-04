<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Bank
            </div>
            <div class="card-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="form-group">
                        <label for="" class="control-label">Nama Jenis Customer</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.nama }" v-model="state.nama">
                    </div>
                    <hr>
                    <div class="form-group">
                        <router-link to="/jenis-customer" class="btn btn-default">
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
            this.bankId = id;

            axios.get('/data/jenis-customer/'+id)
                .then(response => {
                    // this.state.kode = response.data.kd_bank;
                    this.state.nama = response.data.jns_customer;
                    console.log(response);
                })
                .catch( error => {
                    alert('data tidak dapat di load');
                })
        },

        saveForm(){
            var newState = this.state;

            axios.patch('/data/jenis-customer/'+this.bankId, newState)
                .then(response => {
                    this.$router.replace('/jenis-customer');
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