<template>
    <main class="search">
      <b-navbar toggleable="lg" type="dark" variant="info">
        <Logo></Logo>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>


          <!-- Right aligned nav items -->
          <b-navbar-nav class="m-auto">
            <b-nav-form @submit="startSearch">
              <b-form-input v-model="search" size="lg" class="mr-sm-2" placeholder="Search"></b-form-input>
            </b-nav-form>
          </b-navbar-nav>
          <b-navbar-nav class="menu_main">
            <b-nav-item v-for="(page) in pages" v-on:click="open_page(page.slug)"  v-bind:href="'/'+ page.slug">{{ page.title }}</b-nav-item>
          </b-navbar-nav>
        </b-collapse>
      </b-navbar>

        <div class="container search_input_container">
            <div class="row">
                <div class="col-12">
                    <div v-if="this.ad">
                        <b-card v-bind:img-src="this.ad.image" v-bind:img-alt="this.ad.title" img-left v-bind:title="this.ad.title">
                            <b-card-text>
                                {{this.ad.text}}
                            </b-card-text>
                            <b-link target="_blank" v-bind:href="'/goto/'+this.ad.name" class="card-link">{{ this.ad.call_to_action }}</b-link>
                        </b-card>
                    </div>
                    <div v-for="(item) in result" class="row result_row">
                        <p><a target="_blank" v-bind:href= item.link ><h4>{{ item.title }}</h4></a></p>
                        <p>{{ item.snippet }}</p>
                        <p><a target="_blank" v-bind:href= item.link >{{ item.link }}</a></p>
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
            search: '',
            result: {},
            ad: null,
          pages: {},
            total_support: 0
        }
    },
    methods: {
      open_page (link) {
        event.preventDefault();

        this.$router.push('/reed/'+link);

      },
        startSearch() {
            event.preventDefault();
            this.$router.push({path: 'search', query: {q: this.search}})

            const query = new URLSearchParams(window.location.search);
            const s = Object.fromEntries(query.entries());

            this.search = s.q;

            Vue.axios.post('/search_req', {q: this.search}).then(res => {

                    this.result = res.data.results.items;
                    this.ad = res.data.ads;
                }
            );
        },
    },
    mounted() {
        const query = new URLSearchParams(window.location.search);
        const s = Object.fromEntries(query.entries());

        this.search = s.q;

        Vue.axios.post('/search_req', {q: this.search}).then(res => {

                this.result = res.data.results.items;
                this.ad = res.data.ads;
                if (this.$cookie.get('total_support')) {
                    this.$cookie.set('total_support', (+this.$cookie.get('total_support').toFixed(2) + +0.04).toFixed(2), 999);
                } else {
                    this.$cookie.set('total_support', 0.04, 999);
                }
                this.total_support = this.$cookie.get('total_support');

            }
        );
      Vue.axios.get('/pages').then(res => {
            console.log(res);
            this.pages = res.data;
          }
      );
    },
    computed: {},
    destroyed: function () {

    },
    components: {Logo, Set},

}

</script>
