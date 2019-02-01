<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Edit Bank
            </div>
            <div class="card-body">
                <form @submit.prevent="store" action="/data/bank" method="post">
                    <div class="form-group">
                        <label for="" class="control-label">Kode Bank</label>
                        <input type="text" class="form-control" value="{{state.kode}}" :class="{ 'is-invalid': errors.kode }" v-model="state.kode">
                        
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
    mounted() {
        // get by user id
        const url = `/data/bank/${this.$route.params.id}`;
        axios.get(url).then(response => {
            console.log(response);
            this.state=response.data;
            // this.user = response.data;
            // this.notFound = false;
        }).catch(error => {
            if (error.response.status == 404) {
                this.notFound = true;
                this.message = error.response.data.message;
            }
        });
    }
}
</script>