<template>
    <div class="dashboard">
        <SideBar></SideBar>
        <vue-confirm-dialog></vue-confirm-dialog>
    
        <div class="container">
            <div class="row">
                <div class="row">
                    <div class="col-12">
                        <h1>Categories</h1>
                    </div>
                </div>
                <div class="dashboard-body">
                    <div class="container">
                        <div class="col-xl-12 d-flex justify-content-end">
                            <b-button v-on:click="add_item">New Category</b-button>
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
                                        <div class="body__cell">{{ item.name }}</div>
                                        <div class="body__cell">
                                            <b-button v-on:click="edit_item(item.id)" pill variant="primary">Edit</b-button>
                                            <b-button v-on:click="remove_item(item.id)" pill variant="danger">Remove</b-button>
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
    name: "Categories",
    data: () => ({
        items: null,
        fields: ['name', ''],
        id: 'categories',
    }),
    computed: {},
    mounted() {
        this.reload();
    },
    filters: {},
    methods: {
        
        reload: function () {
            Vue.axios
                .get('/categories')
                .then(res => (this.items = res.data));
        },
        add_item: function () {
            this.$router.push('/cats/new');
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
                                .delete('/categories/' + id)
                                .then(res => (this.reload()));
                        }
                    }
                }
            )
        },
        edit_item: function (id) {
            this.$router.push('/cats/edit/'+id);
            
        }
    },
    components: {
        SideBar,
    },
};
</script>
