<template>
    <div class="dashboard">
        <SideBar></SideBar>
        <div class="container">
            <div class="row inner_row">
                <div class="dashboard-body">
                    <div class="container">
                        
                        <div class="col-xl-12 mt-3 w-100">
                            <b-form @submit.prevent.stop="save_item">
    
    
                                <b-form-group label="First Name:" label-for="input-2">
                                    <b-form-input v-model="item.first_name" placeholder="Enter first name" required></b-form-input>
                                </b-form-group>
                                <b-form-group label="Last Name:" label-for="input-2">
                                    <b-form-input v-model="item.last_name" placeholder="Enter last name" required></b-form-input>
                                </b-form-group>
                                
                                <b-form-group  label="Email:" label-for="input-2">
                                    <b-form-input v-model="item.email" placeholder="Enter email" required></b-form-input>
                                </b-form-group>

                                <b-form-group  label="Password:" label-for="input-2">
                                    <b-form-input v-model="item.password" placeholder="Enter password" type="password"></b-form-input>
                                </b-form-group>
                                
                                <b-form-group id="input-group-3" label="User Role:" label-for="input-3">
                                    <b-form-select
                                        id="input-3"
                                        v-model="item.user_role"
                                        :options="user_roles"
                                        required
                                    ></b-form-select>
                                </b-form-group>

                              <b-form-group v-if="item.id" label="Active:" label-for="input-2">
                                <b-icon v-on:click="toggle_active(item)" font-scale="3" v-if="item.active === 0" icon="toggle-off" variant="danger"></b-icon>
                                <b-icon v-on:click="toggle_active(item)" font-scale="3" v-if="item.active === 1" icon="toggle-on" variant="success"></b-icon>
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
    name: "UserForm",
    data: () => ({
        items: null,
        id: 'user_form',
        number: null,
        edit: false,
        item: {
            first_name: null,
            last_name: null,
            password: null,
            email: null,
            active: null,
            user_role: null,
        },
        user_roles: null,
        show_message: false,
        message: 'Saved'
    }),
    computed: {},
    mounted() {
        
        this.load_roles();
        this.number = this.$route.params.id;
        if (this.$route.params.id) {
            this.edit = true;
            Vue.axios
                .get('/users/'+this.$route.params.id).then(res => {
                    this.item = res.data;
            });
        }
    },
    filters: {},
    methods: {
        reload: function () {
        
        },
        go_back: function () {
            this.$router.push('/usr');
        },
        save_item: function () {
            if (this.edit) {
                this.item.id = this.number;
                Vue.axios
                    .put('/users/' + this.number, this.item)
                    .then(res => (this.reload()));
            } else {
                Vue.axios
                    .post('/users', this.item)
                    .then(res => (this.reload()));
            }
            this.show_message = true;
            setTimeout(() => this.show_message = false, 2000);
        },
        load_roles: function() {
            Vue.axios
                .get('/user-roles')
                .then((res) => {
                    let ret = [];
                    for (const one of res.data) {
                        ret.push({'text': one.name, 'value': one.id})
                    }
                    this.user_roles = ret;
                });
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
    },
    components: {
        SideBar,
    },
};
</script>

