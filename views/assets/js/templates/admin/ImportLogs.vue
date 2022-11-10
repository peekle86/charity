<template>
  <div class="dashboard">
    <SideBar></SideBar>
    <vue-confirm-dialog></vue-confirm-dialog>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h1>Cron import Logs</h1>
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

              <b-button class="mx-2" v-b-modal.importModal @click="partner_import">Import</b-button>

            </div>

            <div class="col-xl-12 mt-3 w-100">
              <div class="dash__wrapper">
                <div class="dash__header  ">
                  <div class="header__cell" v-for="cell in fields">
                    {{ cell }}
                  </div>
                </div>
                <div class="dash__body">
                  <div class="body__line  text-center" v-for="(item) in items" :key="item.id">
                    <div class="body__cell">{{ item.cron_started }}</div>
                    <div class="body__cell">{{ item.cron_finished }}</div>
                    <div class="body__cell">{{ item.status }}</div>
                    <div class="body__cell">{{ item.time }}</div>
                    <div class="body__cell">{{ item.partners }}</div>
                    <div class="body__cell">{{ item.new_merchants }}</div>
                    <div class="body__cell">{{ item.new_domains }}</div>
                  </div>
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

export default {
  name: "Import Logs",
  data: () => ({
    items: null,
    fields: ['Cron started', 'Cron finished', 'Status', 'Time spent', 'Partners', 'New merchants count',
      'New domains count',],
    id: 'import_logs',
    dismissSecs: 10,
    dismissCountDown: 0,
  }),
  computed: {},
  mounted() {
    this.reload();
  },
  filters: {},
  methods: {

    reload: function () {
      Vue.axios
          .get('/import-logs?sort=-id')
          .then(res => (this.items = res.data));
    },

    partner_import: function () {
      if (confirm('Are you sure?')) {
        Vue.axios
            .post('/partner/import')

        this.dismissCountDown = this.dismissSecs
        this.reload()
      }
    },

    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },

  },
  components: {
    SideBar,
  },
};
</script>
