<template>
  <b-navbar-nav class="ml-auto">

    <b-nav-text right>
      <span v-if="!change">Your language is <a v-on:click="changeLang" href="#">{{lang}}</a> ?</span>
      <b-form-select @input="set_lang()" v-if="change" v-model="selected" :options="lang_list">
        <template #first>
          <b-form-select-option :value="null" disabled>-- Please select --</b-form-select-option>
        </template>
      </b-form-select>
    </b-nav-text>

    <b-nav-text right>
      <span id="org_desc" v-if="!change_support">You support:  <a v-on:click="changeSupport" href="#">{{support}}</a> Your total support: <b>${{total_support}} </b></span>
    </b-nav-text>
  </b-navbar-nav>
</template>
<script>

export default {
  name: "Set",
  props: {
    navs: Object,
  },
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
      total_support: 0

    };
  },
  methods: {
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
    changeSupport () {
      event.preventDefault();
      // this.change_support = true;
      this.$router.push('/change');
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
    let already_selected = this.$cookie.get('lang');

    if (already_selected === null) {
      Vue.axios.get('/getuserlang').then(res => {
            this.lang = res.data.name;
            this.code = res.data.code;
          }
      );
    }
    Vue.axios
        .get('/organizations')
        .then(res => {
          let already_selected = this.$cookie.get('support');
          console.log(already_selected);
          let ret = [];
          for (const one of res.data) {
            if (one.id === 1) {

              this.support = one.name;
            } else if (already_selected !== null) {

              if (already_selected == one.id) {
                this.support = one.name;
              }
            }
            ret.push({'text': one.name , 'value': one.id})
          }
          this.support_list = ret;
        });

    Vue.axios.get('/langs').then(res => {
          let ret = [];
          for (const one of res.data) {
            if ( already_selected !== null && one.lang_code === already_selected) {
              this.lang = one.lang_name;
            }
            ret.push({'text': one.lang_name , 'value': one.lang_code})
          }
          this.lang_list = ret;
        }
    );

    if (this.$cookie.get('total_support')) {
      this.total_support = this.$cookie.get('total_support');

    }
  },
  components: {



  }
};
</script>
