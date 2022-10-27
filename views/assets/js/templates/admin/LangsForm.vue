<template>
    <div class="dashboard">
        <SideBar></SideBar>
        <div class="container">
            <div class="row inner_row">
                <div class="dashboard-body">
                    <div class="container">
                        
                        <div class="col-xl-12 mt-3 w-100">
                            <b-form @submit.prevent.stop="save_item">
                                
                                
                                <b-form-group label="Language code:" label-for="input-2">
                                    <b-form-input v-model="item.lang_code" placeholder="" required></b-form-input>
                                </b-form-group>
                                <b-form-group label="Language name:" label-for="input-2">
                                    <b-form-input v-model="item.lang_name" placeholder="" required></b-form-input>
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
    name: "Languages",
    data: () => ({
        items: null,
        id: 'languages',
        number: null,
        edit: false,
        item: {
            lang_code: null,
            lang_name: null,
        },
        show_message: false,
        message: 'Saved'
    }),
    computed: {},
    mounted() {
        
        this.number = this.$route.params.id;
        if (this.$route.params.id) {
            this.edit = true;
            Vue.axios
                .get('/langs/'+this.$route.params.id).then(res => {
                this.item = res.data;
            });
        }
    },
    filters: {},
    methods: {
        reload: function () {
        
        },
        go_back: function () {
            this.$router.push('/lng');
        },
        save_item: function () {
            if (this.edit) {
                this.item.id = this.number;
                Vue.axios
                    .put('/langs/' + this.number, this.item)
                    .then(res => (this.reload()));
            } else {
                Vue.axios
                    .post('/langs', this.item)
                    .then(res => (this.reload()));
            }
            this.show_message = true;
            setTimeout(() => this.show_message = false, 2000);
        }
    },
    components: {
        SideBar,
    },
};
</script>

