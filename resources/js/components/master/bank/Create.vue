<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Add New Bank
            </div>
            <div class="card-body">

                <div v-if="message" class="alert alert-success">
                    {{ message }}
                </div>

                <form @submit.prevent="store" action="/data/bank" method="post">
                    <div class="form-group">
                        <label for="" class="control-label">Kode Bank</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.kode }" v-model="state.kode">
                        
                        <!-- <div class="invalid-feedback">Please provide a valid informations.</div> -->

                    </div>
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
                        <router-link to="/bank" class="btn btn-default">
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
            state: {
                kode: '',
                nama: '',
                status: ''
            },
            message:'',

            // submitted: false,

            // array to hold form errors
            errors: [],
        }
    },
    methods: {
        store(e) {
            axios.post(e.target.action, this.state).then(response => {
                if(response.data.success==true){
                    this.errors = [];
                    this.state = {
                        kode: '',
                        nama: '',
                        status: ''
                    }
                    this.message = 'Data has been saved.';
                }else{
                    this.errors.kode=true;
                    this.errors.nama=true;
                    this.errors.status=true;
                }
            }).catch(error => {
                if (! _.isEmpty(error.response)) {
                    if (error.response.status = 422) {
                        this.errors = error.response.data;
                        console.log(this.errors);
                    }
                }
            });
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