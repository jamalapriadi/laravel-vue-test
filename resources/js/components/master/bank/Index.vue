<template>
    <div class="container" id="example">
        <div class="card card-accent-primary">
            <div class="card-header">
                Data Bank

                <div class="card-header-actions">
                    <router-link to="/add-bank" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add New
                    </router-link>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Bank</th>
                            <th>Nama Bank</th>
                            <th>Status</th>
                            <th width="17%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(l, index) in list.data" v-bind:key="index">
                            <td>{{index+1}}</td>
                            <td>{{l.kd_bank}}</td>
                            <td>{{l.nm}}</td>
                            <td>{{l.status}}</td>
                            <td>
                                <div class="btn-group">
                                    <router-link :to="{ name: 'bankEdit', params: {id: l.kd_bank}}" class="btn btn-warning">
                                        <i class="fa fa-edit text-white"></i>
                                    </router-link>

                                    <button class="btn btn-danger" v-on:click="alertDisplay">
                                        <i class="fa fa-trash text-white"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                list:[]
            }
        },
        mounted() {
            this.showData();

            console.log(this.list);
        },
        methods: {
            paginate(url){
                axios.get(url)
                    .then(response => {
                        this.list = response.data;
                    })
            },

            showData(){
                axios.get('data/bank')
                    .then(response => {
                        this.list = response.data;
                    })
            },

            alertDisplay() {
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You can\'t revert your action',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes Delete it!',
                    cancelButtonText: 'No, Keep it!',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                })
                .then((result) => {
                    if(result.value) {
                        this.$swal('Deleted', 'You successfully deleted this file', 'success')
                    } else {
                        this.$swal('Cancelled', 'Your file is still intact', 'info')
                    }
                })
            }
        }
    }
</script>
