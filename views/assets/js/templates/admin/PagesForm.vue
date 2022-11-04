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
                                <b-form-group label="Content:" label-for="input-2">
                                  <vue2-tinymce-editor v-model="item.content" :options="editor_options"></vue2-tinymce-editor>
                                </b-form-group>

                                <b-form-group label="SEO desc" label-for="input-2">
                                    <b-form-input v-model="item.seo" placeholder="" required></b-form-input>
                                </b-form-group>

                                <b-form-group label="Slug (URL)" label-for="input-2">
                                    <b-form-input v-model="item.slug" placeholder="" required></b-form-input>
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
import {Vue2TinymceEditor} from "vue2-tinymce-editor";

export default {
    name: "Categories",
    data: () => ({
        items: null,
        id: 'categories',
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
        editor_options:{
          menubar:false,
          plugins: 'advlist autolink charmap code codesample directionality emoticons',
          toolbar1:'fontselect | fontsizeselect | formatselect | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | link table removeformat code',
        }
    }),
    computed: {},
    mounted() {

        this.number = this.$route.params.id;
        if (this.$route.params.id) {
            this.edit = true;
            Vue.axios
                .get('/pages/'+this.$route.params.id).then(res => {
                this.item = res.data;
            });
        }
    },
    filters: {},
    methods: {
        reload: function () {

        },
        go_back: function () {
            this.$router.push('/pgs');
        },
        save_item: function () {
            if (this.edit) {
                this.item.id = this.number;
                Vue.axios
                    .put('/pages/' + this.number, this.item)
                    .then(res => (this.reload()));
            } else {
                Vue.axios
                    .post('/pages', this.item)
                    .then(res => (this.reload()));
            }
            this.show_message = true;
            setTimeout(() => this.show_message = false, 2000);
        }
    },
    components: {
        SideBar,
      Vue2TinymceEditor
    },
};
</script>

