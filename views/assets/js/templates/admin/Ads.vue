<template>
    <div class="dashboard">
        <SideBar></SideBar>
        <vue-confirm-dialog></vue-confirm-dialog>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Ads</h1>
                </div>
            </div>
            <div class="row">
                <div class="dashboard-body">
                    <div class="container">
                      <b-alert
                          :show="dismissCountDown"
                          dismissible
                          variant="success"
                          @dismissed="dismissCountDown=0"
                          @dismiss-count-down="countDownChanged"
                      >
                        The process of importing merchants has started. This process may take several minutes.
                      </b-alert>

                        <div class="col-xl-12 d-flex justify-content-end">

                          <b-form-input @input="reload" class="mr-3 col-3" id="searchInput" v-model="search" placeholder="Search..." type="text"></b-form-input>

                          <b-button class="mx-2" v-b-modal.importModal>Import</b-button>

                            <b-modal id="importModal" title="Partners import" @ok="partner_import">
                              <b-form @submit.prevent.stop="partner_import">

                                <b-form-group label="Partner:" label-for="input-2">
                                  <b-form-select v-bind:class="{'is-invalid' : !importModel.partner_id}"
                                                 v-model="importModel.partner_id"
                                                 :options="importModelOptions" required></b-form-select>
                                </b-form-group>

                              </b-form>
                            </b-modal>

                            <b-button v-on:click="add_item">New Ad</b-button>
                        </div>
                        
                        <div class="col-xl-12 mt-3 w-100">
                            <div class="dash__wrapper">
                                <div class="dash__header  ">
                                    <div class="header__cell" v-for="cell in fields">
                                        {{ cell }}
                                    </div>
                                </div>
                                <div id="itemList" class="dash__body">
                                    <div class="body__line  text-center" v-for="(item) in items" :key="item.id">
                                        <div class="body__cell">{{ item.id }}</div>
                                        <div class="body__cell">{{ item.title }}</div>
                                        <div class="body__cell">
                                          <span v-if="item.activeDomain">{{ item.activeDomain.name }}</span>
                                          <span v-else>---</span>
                                        </div>
                                        <div class="body__cell">
                                            <img class="logo-preview" v-if="item.image" v-bind:src="item.image" alt="">
                                            <span v-else>---</span>
                                        </div>
                                        <div class="body__cell">
                                            <b-button v-on:click="edit_item(item.id)" pill variant="primary">Edit</b-button>
                                            <b-button v-on:click="remove_item(item.id)" pill variant="danger">Remove</b-button>
                                        </div>
                                        <div class="body__cell">
                                            <b-icon v-on:click="toggle_active(item)" font-scale="3" v-if="item.active === 0" icon="toggle-off" variant="danger"></b-icon>
                                            <b-icon v-on:click="toggle_active(item)" font-scale="3" v-if="item.active === 1" icon="toggle-on" variant="success"></b-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                      <b-pagination-nav
                          v-if="items"
                          v-model="currentPage"
                          :link-gen="linkGen"
                          :number-of-pages="numberOfPages"
                          @page-click="change_page"
                          align="center"
                          use-router
                      ></b-pagination-nav>

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
    name: "Ads",
    data: () => ({
        items: null,
        fields: ['ID', 'Title','Target Domain', 'Image', '', 'Active'],
        importModel: {
            partner_id: null,
            count: 10,
            offset: 0
        },
        importModelOptions: null,
        dismissSecs: 10,
        dismissCountDown: 0,
        importResult: {
          added: 0,
          exists: 0
        },
      currentPage: 1,
      perPage: 20,
      rows: 0,
      itemsForList: [],
      search: null,
      currentUrl: null,
      numberOfPages: 0,
    }),
    computed: {},
    mounted() {
        this.reload();
        this.get_partners()
    },
    filters: {},
    methods: {
        
        reload: function (page = 0) {
          let url = '/links?expand=activeDomain';
          if (this.search) {
            url = '/links?expand=activeDomain&q=' + this.search
          }

          if (page) {
            url += '&page=' + page
          }

            Vue.axios
                .get(url)
                .then(res => {
                  this.currentUrl = res.data._links.self.href;
                  let pagesArray = res.data._links.last.href.split('=')
                  this.numberOfPages = pagesArray[pagesArray.length - 1]
                  this.items = res.data.items
                  this.rows = this.items.length
                });
        },
        add_item: function () {
            this.$router.push('/ads/new');
        },
        toggle_active: function (item) {
            if (item.active === 0) {
                item.active = 1;
            } else {
                item.active = 0;
            }
            Vue.axios
                .put('/links/' + item.id, item)
                .then(res => (this.reload()));
            
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
                                .delete('/links/' + id)
                                .then(res => (this.reload()));
                        }
                    }
                }
            )
        },
        edit_item: function (id) {
            this.$router.push('/ads/edit/'+id);
            
        },

        get_partners: function () {
          Vue.axios
              .get('/partner/get-importers')
              .then(res => (this.importModelOptions = res.data));
        },

        partner_import: function () {
          Vue.axios
              .post('/partner/import', this.importModel)

          this.dismissCountDown = this.dismissSecs

        },

        countDownChanged(dismissCountDown) {
          this.dismissCountDown = dismissCountDown
        },

        linkGen(pageNum) {
          return '#'
        },

      change_page: function (bvEvent, page) {
          console.log(page)
        this.reload(page);
      },


    },
    components: {
        SideBar,
    },
};
</script>
