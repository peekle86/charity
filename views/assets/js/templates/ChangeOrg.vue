<template>
    <main class="home">
      <b-navbar toggleable="lg" type="dark" variant="info">

        <Logo></Logo>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav class="menu_main">
            <b-nav-item v-for="(page) in pages" v-on:click="open_page(page.slug)"  v-bind:href="'/'+ page.slug" v-bind:key="page.id">{{ page.title }}</b-nav-item>
          </b-navbar-nav>

          <!-- Right aligned nav items -->
          <Set></Set>
        </b-collapse>
      </b-navbar>
        <div class="container justify-content-center">
            <div class="row">
                <div class="col-md-12 ">
                    <b-tabs content-class="mt-3" align="center">
                        <b-tab v-for="(cat) in cats" v-bind:title="cat.name" v-bind:key="cat.id" active>
                          <div  v-if="org.category_id === cat.id" v-for="(org) in support_list" v-bind:key="org.id"
                                class="col-md-3 px-0 pb-1 pl-1">
                            <b-card
                                v-bind:title="org.name"
                                v-bind:img-src="org.logo"
                                v-bind:img-alt="org.name"
                                img-top
                                tag="article"
                                class="org_card w-100"
                            >
                                <b-card-text v-html="org.description">
                                </b-card-text>
                              <p class="text-center">
                                <b-button v-on:click="changeSupport(org.id)" href="#" variant="primary">Select for support</b-button>
                              </p>
                              <p class="text-center mb-0">
                                <b-link v-bind:href="'/organization/v/'+org.slug">More info</b-link>
                              </p>
                            </b-card>
                          </div>
                        </b-tab>
                    </b-tabs>

                </div>
            </div>

        </div>
    </main>
</template>
<script>
import Logo from "@ad/Logo.vue";
import Set from "@ad/Set.vue";
export default {
    data() {
        return {
            change: false,
            selected: null,
            lang_list: {},
            search: '',
            lang:'',
            code: '',
            pages: {},
            support: null,
            change_support: false,
            support_list: {},
            total_support: 0,
            cats: {}
        }
    },
    methods: {

        startSearch () {
            event.preventDefault();
            this.$router.push({ path: 'search', query: { q: this.search } })
        },

        changeLang () {
            event.preventDefault();
            this.change = true;
        },
        open_page (link) {
            event.preventDefault();

            this.$router.push('/reed/'+link);

        },
        set_lang () {
            this.change = false;
            this.$cookie.set('lang', this.selected, 999);
            for (const one of this.lang_list) {
                if (one.value === this.selected) {
                    this.lang = one.text;
                }
            }
        },
        changeSupport (id) {
            event.preventDefault();
            this.support = id;
            this.$cookie.set('support', id, 999);
            this.$router.push('/');

        },
        set_support() {
            this.change_support = false;
            this.$cookie.set('support', this.support, 999);
            for (const one of this.support_list) {
                if (one.value === this.support) {
                    this.support = one.text;
                }
            }
        },

    },
    mounted() {

        Vue.axios
            .get('/categories')
            .then(res => (this.cats = res.data));

        Vue.axios
            .get('/organizations?sort=sort')
            .then(res => {
                let already_selected = this.$cookie.get('support');
                console.log(res.data);
                this.support_list = res.data;
            });
      Vue.axios.get('/pages').then(res => {
            console.log(res);
            this.pages = res.data;
          }
      );


    },
    computed: {

    },
    destroyed: function () {

    },
    components: {
      Logo, Set
    },

}

</script>
