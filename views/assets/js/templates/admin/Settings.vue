<template>
  <div class="dashboard">
    <SideBar></SideBar>
    <div class="container">
      <div class="row">
        <div class="dashboard-body">
          <div class="container">
            <b-form @submit.prevent.stop="save_item">
              <h2>General</h2>
              <b-form-group label="Google ID:" label-for="input-2">
                <b-form-input v-model="g_id"></b-form-input>
              </b-form-group>
              <b-form-group label="Google API key" label-for="input-2">
                <b-form-input v-model="g_api_key"></b-form-input>
              </b-form-group>
              <b-form-group label="Logo:" :class="{'has_image': logo}" label-for="input-2">
                <img v-show="logo" :src="logo" style=" height: 50px">
                <b-form-file accept="image/*" @change="setImage" placeholder=""></b-form-file>
              </b-form-group>
              <b-form-group label="Brand title" label-for="input-2">
                <b-form-input v-model="b_title"></b-form-input>
              </b-form-group>
              <h2>Home page</h2>
              <b-form-group label="Title" label-for="input-2">
                <b-form-input v-model="home_title"></b-form-input>
              </b-form-group>
              <b-form-group label="Meta description" label-for="input-2">
                <b-form-input v-model="home_desc"></b-form-input>
              </b-form-group>
              <b-form-group label="Meta keywords" label-for="input-2">
                <b-form-input v-model="home_keys"></b-form-input>
              </b-form-group>
              <b-button type="submit" variant="primary">Submit</b-button>
            </b-form>
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
    name: "Settings",
    data: () => ({
        g_api_key: '',
        g_id: '',
        home_title: '',
        home_desc: '',
        home_keys: '',
        b_title: '',
        logo: null,
    }),
    computed: {},
    mounted() {
        Vue.axios
            .get('/getsettings')
            .then(res => {
                if (res.data) {
                    for (const [key, value] of Object.entries(res.data)) {
                       this[value.name]  = value.value;
                    }
                }
            });
    },
    filters: {},
    methods: {
        save_item: function () {

                Vue.axios
                    .post('/savesettings' , this.$data)
                    .then(res => {

                    });

        },
      setImage: function (e) {
        const image = e.target.files[0];
        this.createBase64(image);
      },
      createBase64: function (image) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.logo = e.target.result;
        }

        reader.readAsDataURL(image);
        this.logotext = '';
      },
    },
    components: {
        SideBar,
    },
};
</script>
