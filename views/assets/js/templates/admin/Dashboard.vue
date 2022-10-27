<template>

    <div class="dashboard">

        <SideBar></SideBar>

        <div class="container">
            <div class="row">
                <div class="row">
                    <div class="col-12">
                        <h1>Dashboard</h1>
                    </div>
                </div>

                <div class="dashboard-body">
                    <div class="container">

                        <div class="row mt-5">
                            <div class="col-xl-3 mt-2">
                                <div class="dashboard-body__per dashboard-body_bg">
                                    <h3 class="dashboard-body__title mb-4">Users</h3>
                                    <div class="row">
                                        <div class="col-12">Total: {{total_users}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">Active: {{active_users}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 mt-2">
                                <div class="dashboard-body__per dashboard-body_bg">
                                    <h3 class="dashboard-body__title mb-4">Requests</h3>
                                    <div class="row">
                                        <div class="col-12">Search req: {{total_req}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">API req: {{api_req}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-2">
                                <div class="dashboard-body_bg w-100">
                                    <h3 class="dashboard-body__title mb-4" >Organizations</h3>
                                    <div class="row">
                                        <div class="col-12">Categories: {{categories}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">Organizations (total/active): {{organizations}}/{{active_organizations}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-xl-7 d-flex justify-content-end">
                </div>
            </div>
        </div>
    
    </div>
</template>
<script>
import SideBar from "@ad/SideBar.vue";

export default {
    name: "Dashboard",
    data: () => ({
        total_req: 0,
        api_req: 0,
        total_users: 0,
        active_users: 0,
        categories: 0,
        organizations: 0,
        active_organizations: 0
    }),
    computed: {
    
    },
    mounted() {
        Vue.axios
            .get('/status')
            .then(res => {
                this.total_users = res.data.users;
                this.active_users = res.data.users_active;
                this.categories = res.data.cats;
                this.organizations = res.data.orgs;
                this.active_organizations = res.data.orgs_active;
                this.api_req = res.data.api_req;
                this.total_req = res.data.total_req;
            });
    },
    filters: {

    },
    methods: {

    },
    components: {
        SideBar,
    },
};
</script>
