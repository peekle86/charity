<template>
    <div class="dashboard">
        <SideBar></SideBar>
        <div class="container">
            <div class="row inner_row">
                <div class="dashboard-body">
                    <div class="container">
                        
                        <div class="col-xl-12 mt-3 w-100">
                            <b-form @submit.prevent.stop="save_item">
    
    
                                <b-form-group label="Name:" label-for="input-2">
                                    <b-form-input v-model="item.name" placeholder="Enter name" required></b-form-input>
                                </b-form-group>
                                
                                <b-button type="submit" variant="primary">Submit</b-button>
                                <b-button v-on:click="go_back" variant="danger">Back</b-button>
                                <div v-show="show_message" class="status">{{message}}</div>
                            </b-form>
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
        fields: ['name', 'url', 'description', 'email', 'logo'],
        id: 'categories',
        number: null,
        edit: false,
        item: {
            name: null,
        },
        show_message: false,
        message: 'Saved'
    }),
    computed: {},
    mounted() {
        
        this.load_categories();
        this.number = this.$route.params.id;
        if (this.$route.params.id) {
            this.edit = true;
            Vue.axios
                .get('/categories/'+this.$route.params.id).then(res => {
                    this.item = res.data;
            });
        }
    },
    filters: {},
    methods: {
        reload: function () {
        
        },
        go_back: function () {
            this.$router.push('/cats');
        },
        save_item: function () {
            if (this.edit) {
                this.item.id = this.number;
                Vue.axios
                    .put('/categories/' + this.number, this.item)
                    .then(res => (this.reload()));
            } else {
                Vue.axios
                    .post('/categories', this.item)
                    .then(res => (this.reload()));
            }
            this.show_message = true;
            setTimeout(() => this.show_message = false, 2000);
        },
        load_categories: function() {
            Vue.axios
                .get('/categories')
                .then((res) => {
                    let ret = [];
                    for (const one of res.data) {
                        ret.push({'text': one.name, 'value': one.id})
                    }
                    this.categories = ret;
                });
        },
    },
    components: {
        SideBar,
    },
};
</script>

