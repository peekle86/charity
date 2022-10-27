<template>
  <b-navbar-brand href="/">
    <img v-bind:src="logo" class="d-inline-block align-top logo-image" v-bind:alt="b_title">
    {{ b_title }}
  </b-navbar-brand>
</template>
<script>

export default {
  name: "Logo",
  props: {
    navs: Object,
  },
  data() {
    return {
      logo: '',
      b_title: '',

    };
  },
  metaInfo() {
    return {
      title: this.title,
      meta: [
        { name: 'description', content:  this.desc},
        { property: 'og:title', content: this.title},
        { property: 'og:site_name', content: this.b_title},
        {property: 'og:type', content: 'website'},
        {name: 'robots', content: 'index,follow'}
      ]
    }
  },
  methods: {


  },
  mounted() {
    Vue.axios.post('/logo').then(res => {
      console.log(res.data);
          this.logo = res.data.logo;
          this.b_title = res.data.b_title;
          this.title = res.data.home_title;
          this.desc = res.data.home_desc;
        }
    );
  },
  components: {
  }
};
</script>
