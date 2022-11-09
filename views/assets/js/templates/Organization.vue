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

          <h1>{{ item.name }}</h1>
          <b-img class="w-100 mb-4 mt-2" :src="item.logo" fluid></b-img>

          <div class="row mb-5">
            <div class="col-md-8">
              <h2 class="mb-3">{{item.title}}</h2>
              <div v-html="item.content"></div>
            </div>

            <div class="col-md-4">

              <div class="row justify-content-center my-4">
                <b-button v-on:click="changeSupport(item.id)" href="#" variant="primary">Select for support</b-button>
              </div>

              <div class="row justify-content-center">
                <b-link v-bind:href="item.url" target="_blank">Website</b-link>
              </div>
            </div>
          </div>

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
      item: {
        name: null,
        url: null,
        description: null,
        email: null,
        logo: null,
        category_id: null,
        slug: null,
        title: null,
        content: null,
      },
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
    }
  },
  mounted() {

    Vue.axios
        .get('/organization/s/'+this.$route.params.slug).then(res => {
      this.item = res.data;
    });

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
