<template>
    <div class="dashboard">
        <vue-confirm-dialog></vue-confirm-dialog>
    
        <SideBar></SideBar>
        <div class="container">
            
            <div class="row inner_row">
                <div class="row">
                    <div class="col-12">
                        <h1>Organizations</h1>
                    </div>
                </div>
                <div class="dashboard-body">
                    <div class="container">
                        <div class="col-xl-12 d-flex justify-content-between">
                            <b-form-select class="col-md-3" v-model="category" :options="options" @change="reload()"></b-form-select>
                            <b-button v-on:click="add_item">New Organization</b-button>
                        </div>
                        
                        <div class="col-xl-12 mt-3 w-100">
                            <div class="dash__wrapper">
                                <div class="dash__header  ">
                                    <div class="header__cell" v-for="cell in fields">
                                        {{ cell }}
                                    </div>
                                </div>
                                <div class="dash__body">
                                  <draggable v-model="items" v-bind="dragOptions" @end="on_end_drag">
                                      <transition-group>
                                          <div class="body__line  text-center" v-for="(item) in items" :key="item.id">
                                              <div class="body__cell">{{ item.name }}</div>
                                              <div class="body__cell">{{ item.url }}</div>
                                              <div class="body__cell">{{ item.description }}</div>
                                              <div class="body__cell">{{ item.email }}</div>
                                              <div class="body__cell">
                                                  <img class="logo-preview" v-if="item.logo" v-bind:src="item.logo" alt="">
                                                  <span v-else>---</span>
                                              </div>
                                              <div class="body__cell">
                                                  <b-button v-on:click="edit_item(item.id)" pill variant="primary">Edit</b-button>
                                                  <b-button v-on:click="remove_item(item.id)" pill variant="danger">Remove</b-button>
                                              </div>

                                          </div>
                                      </transition-group>
                                  </draggable>
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
import draggable from 'vuedraggable';

export default {
    name: "Organization",
    data: () => ({
        items: null,
        fields: ['name', 'url', 'description', 'email', 'logo', 'edit'],
        id: 'organizations',
        category: null,
        selected: null,
        options: [
          { value: null, text: 'Please select a category' },
        ]
    }),
    computed: {
      dragOptions() {
        return {
          animation: 200,
          disabled: !this.category,
          ghostClass: "ghost"
        };
      }
    },
    mounted() {
        this.reload();
        Vue.axios
          .get('/categories')
          .then(res => {
            let options = []
            $.each(res.data, function (i, item) {
              options.push({
                value: item.id,
                text: item.name
              })
            })
            this.options = this.options.concat(options)
        });
    },
    filters: {},
    
    methods: {

        reload: function () {
          console.log(this.category)
          if (this.category) {
            Vue.axios
                .get('/organizations?sort=sort&filter[category_id]='+this.category)
                .then(res => (this.items = res.data));
          } else {
            Vue.axios
                .get('/organizations')
                .then(res => (this.items = res.data));
          }
        },
        add_item: function () {
            this.$router.push('/orgs/new');
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
                                .delete('/organizations/' + id)
                                .then(res => (this.reload()));
                        }
                    }
                }
            )
        },
        edit_item: function (id) {
            this.$router.push('/orgs/edit/'+id);
    
        },

        on_end_drag: function (item) {
          let array = [];
          this.items.forEach(item => {
            array.push(item.id)
          })

          Vue.axios
              .post('/organization/sort', array);
          console.log(this.items)
          console.log(array)

        }
    },
    components: {
        SideBar,
        draggable,
    },
};
</script>

