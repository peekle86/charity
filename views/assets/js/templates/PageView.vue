<template>
    <main class="home">
      <b-navbar toggleable="lg" type="dark" variant="info">
        <Logo></Logo>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav class="menu_main">
            <b-nav-item v-for="(page) in pages" v-on:click="open_page(page.slug)"  v-bind:href="'/'+ page.slug" v-bind:key="page.id">{{ page.title }}</b-nav-item>
          </b-navbar-nav>

          <Set></Set>
        </b-collapse>
      </b-navbar>
        <div class="container justify-content-center">
            <div class="row">
                <div class="col-12 mt-5 mb-3">
                    <h1>{{title}}</h1>
                </div>
                <div class="col-12 mt-3">
                    <div v-html="content"></div>
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
            content: '',
            title: '',
            seo: '',
            slug: '',
          pages: null
        }
    },
    methods: {
        startSearch () {
            event.preventDefault();
            this.$router.push({ path: 'search', query: { q: this.search } })
        },
      open_page (link) {
        event.preventDefault();

        this.$router.push('/reed/'+link);

      },
    },
    mounted() {
        this.slug = this.$route.params.id;
      Vue.axios.get('/pages').then(res => {
            console.log(res);
            this.pages = res.data;
          }
      );
        Vue.axios.post('/getpage', {slug: this.slug}).then(res => {
                if (res !== null) {
                    console.log(res);
                    this.title = res.data.title;
                    this.content = res.data.content;
                    this.seo = res.data.seo;
                }
            }
        );

    },
  metaInfo() {
    return {
      title: this.title,
      meta: [
        { name: 'description', content:  this.seo},
        { property: 'og:title', content: this.title},
        { property: 'og:site_name', content: this.b_title},
        {property: 'og:type', content: 'website'},
        {name: 'robots', content: 'index,follow'}
      ]
    }
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
