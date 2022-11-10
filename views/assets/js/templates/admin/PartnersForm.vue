<template>
    <div class="dashboard">
        <SideBar></SideBar>
        <div class="container">
            <div class="row inner_row">
                <div class="dashboard-body">
                    <div class="container">

                        <div class="col-xl-12 mt-3 w-100">
                            <b-form @submit.prevent.stop="save_item">


                                <b-form-group label="Title:" label-for="input-2">
                                    <b-form-input v-model="item.title" placeholder="" required></b-form-input>
                                </b-form-group>

                                <b-form-group v-if="item.id == 1 || item.id == 2" :label="this.label_public" label-for="input-2">
                                    <b-form-input v-model="item.public_key" placeholder=""></b-form-input>
                                </b-form-group>

                                <b-form-group v-if="item.id == 1 || item.id == 2" :label="this.label_secret" label-for="input-2">
                                    <b-form-input v-model="item.secret_key" placeholder=""></b-form-input>
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
    name: "Partners",
    data: () => ({
        items: null,
        id: 'partners',
        number: null,
        edit: false,
        item: {
            title: null,
            content: null,
            seo: null,
            slug: null,
        },
        show_message: false,
        message: 'Saved',
      label_public: null,
      label_secret: null,
    }),
    computed: {},
    mounted() {

        this.number = this.$route.params.id;
        if (this.$route.params.id) {
            this.edit = true;
            Vue.axios
                .get('/partners/'+this.$route.params.id).then(res => {
                this.item = res.data;
                if (this.item.id == 1) {
                  this.label_public = 'Public key'
                  this.label_secret = 'Secret key'
                } else if (this.item.id == 2) {
                  this.label_public = 'Client id'
                  this.label_secret = 'Client secret'
                }
            });
        }
    },
    filters: {},
    methods: {
        reload: function () {

        },
        go_back: function () {
            this.$router.push('/prtns');
        },
        save_item: function () {
            if (this.edit) {
                this.item.id = this.number;
                Vue.axios
                    .put('/partners/' + this.number, this.item)
                    .then(res => (this.reload()));
            } else {
                Vue.axios
                    .post('/partners', this.item)
                    .then(res => (this.reload()));
            }
            this.show_message = true;
            setTimeout(() => this.show_message = false, 2000);
        }
    },
    components: {
        SideBar
    },
};
</script>

