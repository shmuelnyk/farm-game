<template>
  <div style="padding: 10px">
    <a-row type="flex" justify="start" class="inner-header">
      <a-col class="new-button" :span="4" style="padding: 0!important;">
        <a-button type="primary" :size="'default'" @click="exportParticipants">Export</a-button>
      </a-col>
    </a-row>
    <div class="main-container">
      <div class="card">
        <a-table
                :rowKey="(record, index) => { return record.id }"
                bordered
                :pagination="false"
                :data-source="data"
                @change="handleTableChange"
                :columns="columns"
        >
          <template slot="date" slot-scope="text, record">{{ formatDate(text) }}</template>
        </a-table>
        <a-row type="flex" justify="center" style="margin-top: 40px">
          <CustomPagination :pagination="pagination" :change="handleTableChange" :size-change="onShowSizeChange"></CustomPagination>
        </a-row>
      </div>
    </div>
  </div>



</template>

<script>
  import { mapState, mapGetters } from "vuex";
  import CustomPagination from "@/components/CustomPagination";
  import moment from "moment";
  export default {
    components: {CustomPagination },
    computed: {
      ...mapState({
        pagination: (state) => state.participants.pagination,
      }),
      ...mapGetters({
        getUser: "auth/getUser",
        data: "participants/getList",
        pagination: "participants/getPagination",
      }),
    },

    data() {
      return {
        columns: [
          {
            title: "Quiz ID",
            dataIndex: "id",
            sorter: function (a, b) {
              if (a.id < b.id) {
                return -1;
              }
              if (a.id > b.id) {
                return 1;
              }
              return 0;
            },
            sortDirections: ["descend", "ascend"],
          },
        ],
      };
    },
    watch: {

    },
    mounted() {
      this.getData();
    },
    methods: {
      async exportParticipants(){
        let params = {};
        params.pagination = Object.assign({}, this.pagination);
        params.search = this.search;
        await this.$axios.post("/api/participants/export", JSON.stringify(params))
                .then((res) => {
                  let url = res.data.file;
                  const a = document.createElement('a');
                  a.style.display = 'none';
                  a.href = url;
                  let now = moment()
                  a.download = 'Participants ' + now.format('DD MMM YYYY')+'.xlsx';
                  document.body.appendChild(a);
                  a.click();
                  window.URL.revokeObjectURL(url);
                })
                .catch((err) => {
                  console.log(err);
                })
      },
      onShowSizeChange(current, pageSize) {
        let params = {};
        params.pagination = Object.assign({}, this.pagination);
        params.pagination.pageSize = pageSize;
        this.getData(params);
      },
      async getData(params = {}) {
        await this.$store.dispatch("participants/getItems", params);
      },
      handleTableChange(page, filters, sorter) {
        if (JSON.stringify(page) == JSON.stringify({})) {
          return;
        }
        let params = {};
        params.pagination = Object.assign({}, this.pagination);
        params.pagination.current = page;
        this.getData(params);

      },
    },
  };
</script>

<style>
  .inner-header{
    padding: 40px;
    justify-content: flex-end;
  }
</style>