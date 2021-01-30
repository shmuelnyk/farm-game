<template>
  <div >
    <a-page-header
            style="border: 1px solid rgb(235, 237, 240)"
            title="Admin"
    >
      <template slot="extra">
        <a-button @click="logout">
          Logout
        </a-button>
      </template>
    </a-page-header>
    <div class="main-container">
      <a-collapse v-model="activeKey">
        <a-collapse-panel key="1" header="Export">
          <a-row :gutter="24">
            <a-col :span="12">
              <a-range-picker @change="onChange" style="width: 100%" />

            </a-col>
            <a-col :span="12">
              <div class="new-button" :span="4" style="padding: 0!important;text-align: right">
                <a-button type="primary" :size="'default'" @click="exportParticipants">Export</a-button>
              </div>
            </a-col>
          </a-row>
        </a-collapse-panel>
        <a-collapse-panel key="2" header="Settings" :disabled="false">
          <a-row :gutter="24">
            <a-col :span="12">
              <a-input v-model="link" style="width: 100%">
              </a-input>
            </a-col>
            <a-col :span="12">

              <div class="new-button" :span="4" style="padding: 0!important;text-align: right">
                <a-button type="primary" :size="'default'" @click="saveLink">Save</a-button>
              </div>
            </a-col>
          </a-row>
        </a-collapse-panel>
      </a-collapse>
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
        link:'',
        activeKey: ['1'],
        exportRange:[],
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
      logout(){
        this.$store.dispatch("auth/logout");;
        this.$router.push({'name':'login'})
      },
      onChange(date, dateString) {
        this.exportRange = [
            date[0],
            date[1]
        ]
        console.log(date, dateString);
      },
      saveLink(){
        let data = new FormData();
        if(this.link == ''){
          this.$message.error('Link cant be empty.')
          return;
        }
        data.append('link',this.link)
        this.$axios.post("/api/save/link", data)
                .then((res) => {
                  this.getData()
                  this.$message.success('Link saved successfully.')
                })
                .catch((err) => {
                  this.$message.error('Server error please refresh and try again.')
                })

      },
      async exportParticipants(){
        if(this.exportRange.length == 0){
          this.$message.error('Please select a date range.')
          return;
        }
        let params = {};
        params.pagination = Object.assign({}, this.pagination);
        params.search = this.search;
        params.start = this.exportRange[0].format('DD-MM-YYYY');
        params.end = this.exportRange[1].format('DD-MM-YYYY');
        await this.$axios.post("/api/participants/export", JSON.stringify(params))
                .then((res) => {
                  let url = res.data.file;
                  const a = document.createElement('a');
                  a.style.display = 'none';
                  a.href = url;
                  let now = moment()
                  a.download = 'Participants from ' + this.exportRange[0].format('DD-MM-YYYY')+ ' to '+ this.exportRange[1].format('DD-MM-YYYY') +'.xlsx';
                  document.body.appendChild(a);
                  a.click();
                  window.URL.revokeObjectURL(url);
                  this.$message.success(res.data.count + ' rows exported for the selected range.')
                })
                .catch((err) => {
                  this.$message.error(err.response.data.msg)
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
        this.$axios.get("/api/get/link")
                .then((res) => {
                  this.link = res.data.link;
                })
                .catch((err) => {
                  console.log(err);
                })
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
  .main-container{
    width: 50vw;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }

</style>