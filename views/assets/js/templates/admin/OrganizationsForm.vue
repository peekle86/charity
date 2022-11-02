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

                                
                                <b-form-group label="Email:" label-for="input-2">
                                    <b-form-input v-model="item.email" placeholder="Enter email" required></b-form-input>
                                </b-form-group>
    
                                <b-form-group label="Description:" label-for="input-2">
                                  <vue2-tinymce-editor v-model="item.description" :options="editor_options"></vue2-tinymce-editor>
                                </b-form-group>

                              <b-form-group label="Url:" label-for="input-2">
                                    <b-form-input v-model="item.url" placeholder="Enter url" required></b-form-input>
                                </b-form-group>
                                
                                
                                <b-form-group label="Category:" label-for="input-3">
                                    <b-form-select
                                        id="input-3"
                                        v-model="item.category_id"
                                        :options="categories"
                                        required
                                    ></b-form-select>
                                </b-form-group>
                                <b-form-group label="Image:"  :class="{'has_image': item.logo}" label-for="input-2">
                                    <img v-show="item.logo" :src="item.logo" style=" height: 50px" >
                                    <b-form-file accept="image/*" @change="setImage" v-model="item.logo" placeholder=""></b-form-file>
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
import { Vue2TinymceEditor } from "vue2-tinymce-editor";


export default {
    name: "Organization",
    data: () => ({
        items: null,
        fields: ['name', 'url', 'description', 'email', 'logo'],
        id: 'organizations',
        number: null,
        edit: false,
        item: {
            name: null,
            url: null,
            description: null,
            email: null,
            logo: null,
            category_id: null,
        },
        categories: null,
        show_message: false,
        message: 'Saved',
        editor_options:{
          menubar:false,
          plugins: 'advlist autolink charmap code codesample directionality emoticons',
          toolbar1:'fontselect | fontsizeselect | formatselect | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | link table removeformat code',
        }
    }),
    computed: {
        hasImage() {
            return false;
        }
    },
    mounted() {
        
        this.load_categories();
        this.number = this.$route.params.id;
        if (this.$route.params.id) {
            this.edit = true;
            Vue.axios
                .get('/organizations/'+this.$route.params.id).then(res => {
                    this.item = res.data;
            });
        }
    },
    filters: {},
    methods: {
        reload: function () {
        
        },
        go_back: function () {
            this.$router.push('/orgs');
        },
        save_item: function () {
            if (this.edit) {
                this.item.id = this.number;
                Vue.axios
                    .put('/organizations/' + this.number, this.item)
                    .then(res => (this.reload()));
            } else {
                Vue.axios
                    .post('/organizations', this.item)
                    .then(res => (this.reload()));
            }
            this.show_message = true;
            setTimeout(() => this.show_message = false, 2000);
        },
        setImage: function (e) {
            const image = e.target.files[0];
            this.createBase64(image);
        },
        createBase64: function (image) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.item.logo = e.target.result;
            }
        
            reader.readAsDataURL(image);
            this.logotext = '';
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
      Vue2TinymceEditor
    },
};
</script>

