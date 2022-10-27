<template>
    <div class="dashboard">
        <SideBar></SideBar>
        <vue-confirm-dialog></vue-confirm-dialog>
    
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Users</h1>
                </div>
            </div>
            <div class="row">
                <div class="dashboard-body">
                    <div class="container">
                        <div class="col-xl-12 d-flex justify-content-end">
                            <b-button v-on:click="add_item">New User</b-button>
                        </div>
    
                        <div class="col-xl-12 mt-3 w-100">
                            <div class="dash__wrapper">
                                <div class="dash__header  ">
                                    <div class="header__cell" v-for="cell in fields">
                                        {{ cell }}
                                    </div>
                                </div>
                                <div class="dash__body">
                                    <div class="body__line  text-center" v-for="(item) in items" :key="item.id">
                                        <div class="body__cell">{{ item.first_name }}</div>
                                        <div class="body__cell">{{ item.email }}</div>
                                        <div class="body__cell">{{ item.created_date }}</div>
                                        <div class="body__cell">{{ item.user_role }}</div>
                                        <div class="body__cell">
                                            <b-button v-on:click="edit_item(item.id)" pill variant="primary">Edit</b-button>
                                            <b-button v-on:click="remove_item(item.id)" pill variant="danger">Remove</b-button>
                                        </div>
                                        <div class="body__cell">
                                            <b-icon v-on:click="toggle_active(item)" font-scale="3" v-if="item.active === 0" icon="toggle-off" variant="danger"></b-icon>
                                            <b-icon v-on:click="toggle_active(item)" font-scale="3" v-if="item.active === 1" icon="toggle-on" variant="success"></b-icon>
                                        </div>
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
    name: "Users",
    data: () => ({
        items: null,
        fields: ['Name', 'Email', 'Created at', 'User role', '', ''],
        id: 'users',
    }),
    computed: {},
    mounted() {
        this.reload();
    },
    filters: {},
    methods: {
        
        reload: function () {
            Vue.axios
                .get('/users')
                .then(res => (this.items = res.data));
        },
        add_item: function () {
            this.$router.push('/usr/new');
        },
        toggle_active: function (item) {
            if (item.active === 0) {
                item.active = 1;
            } else {
                item.active = 0;
            }
            Vue.axios
                .put('/users/' + item.id, item)
                .then(res => (this.reload()));
            
        },
        remove_item: function (id) {
            this.$confirm(
                {
                    message: `Are you sure?`,
                    button: {
                        no: 'No',
                        yes: 'Yes'
                    },
                    callback: confirm => {
                        if (confirm) {
                            Vue.axios
                                .delete('/users/' + id)
                                .then(res => (this.reload()));
                        }
                    }
                }
            )
        },
        edit_item: function (id) {
            this.$router.push('/usr/edit/'+id);
            
        }
    },
    components: {
        SideBar,
    },
};
</script>
