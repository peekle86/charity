<template>
    <div class="dashboard">
        <SideBar></SideBar>
        <div class="container">
            <div class="row inner_row">
                <div class="dashboard-body">
                    <div class="container">

                        <div class="col-xl-12 mt-3 w-100">
                            <b-form @submit.prevent.stop="save_item">


                                <b-form-group label="Slug:" label-for="input-2">
                                    <b-form-input v-model="item.name" placeholder="" required></b-form-input>
                                </b-form-group>
                                <b-form-group label="Title:" label-for="input-2">
                                    <b-form-input v-model="item.title" placeholder="" required></b-form-input>
                                </b-form-group>
                                <b-form-group label="Target domain (without http:// ):" label-for="input-2">
                                    <b-form-input v-model="item.target_domain" placeholder="" required></b-form-input>
                                </b-form-group>
                                <b-form-group label="Text:" label-for="input-2">
                                    <b-form-input v-model="item.text" placeholder="" required></b-form-input>
                                </b-form-group>
                                <b-form-group label="Call to action:" label-for="input-2">
                                    <b-form-input v-model="item.call_to_action" placeholder="" required></b-form-input>
                                </b-form-group>

                                <b-form-group label="URL:" label-for="input-2">
                                    <b-form-input v-model="item.url" placeholder="" required></b-form-input>
                                </b-form-group>

                                <b-form-group label="Image:"  :class="{'has_image': item.image}" label-for="input-2">
                                        <img v-show="item.image" :src="item.image" style=" height: 50px" >
                                  <vue-anka-cropper
                                      :options="{
               aspectRatio: false,
               closeOnSave: true,
               cropArea: 'box',
               croppedHeight: 400,
               croppedWidth: 400,
               cropperHeight: false,
               dropareaMessage: 'Select image',
               frameLineDash: [5,3],
               frameStrokeColor: 'rgba(255, 255, 255, 0.8)',
               handleFillColor: 'rgba(255, 255, 255, 0.2)',
               handleHoverFillColor: 'rgba(255, 255, 255, 0.4)',
               handleHoverStrokeColor: 'rgba(255, 255, 255, 1)',
               handleSize: 10,
               handleStrokeColor: 'rgba(255, 255, 255, 0.8)',
               layoutBreakpoint: 850,
               maxCropperHeight: 768,
               maxFileSize: 8000000,
               overlayFill: 'rgba(0, 0, 0, 0.5)',
               previewOnDrag: true,
               previewQuality: 0.65,
               resultQuality: 0.8,
               resultMimeType: 'image/jpeg',
               selectButtonLabel: 'Select Files',
               showPreview: true,
               skin: 'light',
               uploadData: {},
               uploadTo: false}"
                                      @cropper-saved="saveCrop($event, 'cropper-saved')"></vue-anka-cropper>
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
import vueAnkaCropper from 'vue-anka-cropper'

export default {
    name: "Ads",
    data: () => ({
        items: null,
        id: 'ads',
        number: null,
        edit: false,
        item: {
            name: null,
            title: null,
            text: null,
            call_to_action: null,
            target_domain: null,
            url: null,
            place: null,
            image: null,
        },
        show_message: false,
        message: 'Saved'
    }),
    computed: {
        hasImage() {
            return false;
        }
    },
    mounted() {

        this.number = this.$route.params.id;
        if (this.$route.params.id) {
            this.edit = true;
            Vue.axios
                .get('/links/'+this.$route.params.id).then(res => {
                this.item = res.data;
            });
        }
    },
    filters: {},
    methods: {
        reload: function () {

        },
        go_back: function () {
            this.$router.push('/ads');
        },
        save_item: function () {
            if (this.edit) {
                this.item.id = this.number;
                Vue.axios
                    .put('/links/' + this.number, this.item)
                    .then(res => (this.reload()));
            } else {
                Vue.axios
                    .post('/links', this.item)
                    .then(res => (this.reload()));
            }
            this.show_message = true;
            setTimeout(() => this.show_message = false, 2000);
        },
        saveCrop: function (data) {
          this.item.image = data.croppedImageURI;
        },
        setImage: function (e) {
            const image = e.target.files[0];
            this.createBase64(image);
        },
        createBase64: function (image) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.item.image = e.target.result;
            }

            reader.readAsDataURL(image);
            this.logotext = '';
        },
    },
    components: {
        SideBar,vueAnkaCropper
    },
};
</script>

