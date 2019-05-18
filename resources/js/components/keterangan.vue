<template>
    <div class="container">
        <div class="card card-accent-primary">
            <div class="card-header">
                Keterangan
            </div>
            <div class="card-body">

                <div v-if="message" class="alert alert-success">
                    {{ message }}
                </div>

                <form @submit.prevent="store" action="/data/keterangan" method="post">
                    <div class="form-group">
                        <label for="" class="control-label">No. HP</label>
                        <input type="text" class="form-control" v-model="state.no_hp">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Email</label>
                        <input type="email" class="form-control" v-model="state.email">
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">No. Rekening</label>
                        <textarea name="" id="" cols="30" rows="3" class="form-control" v-model="state.no_rekening"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="" class="control-label">NPWP</label>
                        <input type="text" class="form-control" v-model="state.npwp">
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
                no_hp: '',
                email: '',
                no_rekening: '',
                npwp:''
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
                        no_hp: '',
                        email: '',
                        no_rekening: '',
                        npwp:''
                    }
                    this.message = 'Data has been saved.';
                    this.getKeterangan();
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
        },

        getKeterangan(){
            axios.get('data/list-keterangan')
                .then(response => {
                    if(response.data!=null){
                        this.state={
                            'no_hp':response.data[0].no_hp,
                            'email':response.data[0].email,
                            'no_rekening':response.data[0].no_rekening,
                            'npwp':response.data[0].npwp
                        }
                    }else{
                        this.state= {
                            no_hp: '',
                            email: '',
                            no_rekening: '',
                            npwp:''
                        }
                    }
                })
        }
    },
    mounted() {
        this.getKeterangan();
    },
    computed:{
        // valKode() {
        //     if (this.post.kode.length === 0 || this.post.kode.length > 50) {
        //         return true;
        //     } 
        //     return false;
        // },
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