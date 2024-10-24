<template>
    <div>
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
                            <a-range-picker @change="onChange" style="width: 100%"/>

                        </a-col>
                        <a-col :span="6">
                            <div class="new-button" :span="4" style="padding: 0!important;text-align: right">
                                <a-button type="primary" :size="'default'" @click="exportParticipants">Export List</a-button>
                            </div>
                        </a-col>
                        <a-col :span="6">
                            <div class="new-button" :span="4" style="padding: 0!important;text-align: right">
                                <a-button type="primary" :size="'default'" @click="buttonPressed">Export Curve Area</a-button>
                            </div>
                        </a-col>
                    </a-row>
                </a-collapse-panel>
                <a-collapse-panel key="2" header="Settings" :disabled="false">
                    <a-row :gutter="24">
                        <a-col :span="12">
                            <a-input addonBefore="Link to the next part: " v-model="link"
                                     placeholder="http://google.com" style="width: 100%">
                            </a-input>
                        </a-col>
                        <a-col :span="12">
                            <div class="new-button" :span="4" style="padding: 0!important;text-align: right">
                                <a-button type="primary" :size="'default'" @click="saveLink">Save</a-button>
                            </div>
                        </a-col>
                    </a-row>
                </a-collapse-panel>
                <a-collapse-panel key="3" header="Password" :disabled="false">
                    <a-row :gutter="24">
                        <a-col :span="10">
                            <a-input-password addonBefore="Current password: " v-model="oldPassword" style="width: 100%">
                            </a-input-password>
                        </a-col>
                        <a-col :span="10">
                            <a-input-password addonBefore="New password: " v-model="newPassword" style="width: 100%">
                            </a-input-password>
                        </a-col>
                        <a-col :span="4" >
                            <div class="new-button"  :span="6" style="padding: 0!important;text-align: right">
                                <a-button type="primary" :size="'default'" @click="updatePassword">Save</a-button>
                            </div>
                        </a-col>
                    </a-row>
                    <a-row :gutter="24">

                    </a-row>
                </a-collapse-panel>
            </a-collapse>
        </div>
    </div>


</template>

<script>
    import {mapState, mapGetters} from "vuex";
    import CustomPagination from "@/components/CustomPagination";
    import moment from "moment";

    export default {
        components: {CustomPagination},
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
                link: '',
                newPassword: '',
                oldPassword: '',
                activeKey: ['1'],
                exportRange: [],
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
        watch: {},
        mounted() {
            this.getData();
        },
        methods: {
            updatePassword(){
                if (this.newPassword == '' || this.oldPassword == '') {
                    this.$message.error('New or current password cant be empty.')
                    return;
                }
                let data = new FormData();

                data.append('password', this.newPassword)
                data.append('old_password', this.oldPassword)
                this.$axios.post("/api/update/password", data)
                    .then((res) => {
                        this.$message.success('Password updated successfully.')
                    })
                    .catch((err) => {
                        console.log(err)
                        this.$message.error(err.response.data.error)
                    })
            },
            logout() {
                this.$store.dispatch("auth/logout");
                ;
                this.$router.push({'name': 'login'})
            },
            onChange(date, dateString) {
                this.exportRange = [
                    date[0],
                    date[1]
                ]

            },
            saveLink() {
                let data = new FormData();
                if (this.link == '') {
                    this.$message.error('Link cant be empty.')
                    return;
                }
                data.append('link', this.link)
                this.$axios.post("/api/save/link", data)
                    .then((res) => {
                        this.getData()
                        this.$message.success('Link saved successfully.')
                    })
                    .catch((err) => {
                        this.$message.error('Server error please refresh and try again.')
                    })

            },
            async exportParticipants() {
                if (this.exportRange.length == 0) {
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
                        a.download = 'Participants from ' + this.exportRange[0].format('DD-MM-YYYY') + ' to ' + this.exportRange[1].format('DD-MM-YYYY') + '.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        this.$message.success(res.data.participants + ' rows exported for the selected range.')
                    })
                    .catch((err) => {
                        this.$message.error(err.response.data.msg)
                    })
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
            groupBy(xs, key) {
                return xs.reduce(function (rv, x) {
                    (rv[x[key]] = rv[x[key]] || []).push(x);
                    return rv;
                }, {});
            },

            exportData(data, name) {
                let filename = name + '.xlsx';
                let ws = XLSX.utils.json_to_sheet(data);
                let wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "People");
                XLSX.writeFile(wb, filename);
            },

            sortPoint(pointObj, fieldName) {
                return Object.entries(pointObj).sort(([, a], [, b]) => {
                    return parseInt(a[fieldName]) - parseInt(b[fieldName])
                }).map(x => x[1]);
            },

            buttonPressed() {
                if (this.exportRange.length == 0) {
                    this.$message.error('Please select a date range.')
                    return;
                }
                let params = {};
                params.start = this.exportRange[0].format('DD-MM-YYYY');
                params.end = this.exportRange[1].format('DD-MM-YYYY');
                this.$axios.post("/api/participants/export/curve", JSON.stringify(params))
                    .then((res) => {
                        this.doStuffWithData(res.data.json)
                        this.$message.success(res.data.participants + ' rows exported for the selected range.')

                    })
                    .catch((err) => {
                        this.$message.error(err)
                    })
            },

            doStuffWithData(answers) {
                answers = answers.filter(x => !!x["MTurk ID"]);
                const personsAnswers = this.groupBy(answers, "MTurk ID");
                let resultJson = [];
                Object.keys(personsAnswers).forEach(id => {
                    resultJson.push(this.forOnePerson(id, personsAnswers[id]))
                });
                this.exportData(resultJson, 'Curve area from ' + this.exportRange[0].format('DD-MM-YYYY') + ' to ' + this.exportRange[1].format('DD-MM-YYYY'))
            },

            // first need to do everything separated for each person
            forOnePerson(id, personAnswers) {
                const tasksAnswers = this.groupBy(personAnswers, "Test Name");
                let res = {};
                Object.keys(tasksAnswers).forEach(task => {
                    res = {...res, ...this.calcTask(task, tasksAnswers[task])}
                });
                return {id, ...res}
            },

            calcTask(taskName, taskAnswers) {
                const taskType = Object.keys(this.groupBy(taskAnswers, "Option one")).length  === 1 ? "Option two" : "Option one"
                const pointsData = this.groupBy(taskAnswers, taskType);
                
                let res = {};
                let time = 0;
                let taskRecs = Object.keys(pointsData).map(point => {
                    pointsData[point].forEach(x => time += parseInt(x["Time in milliseconds"]));
                    const {cutoffPoint, consistency} = this.calcPoint(point, pointsData[point], taskType);
                    return {
                        optionOne: this.getNumberFromOptionOne(pointsData[point][0][taskType]),
                        cutoff: cutoffPoint,
                        consistency,
                    };
                });
                const area = this.getAreaUnderTheCurve(taskRecs);
                taskRecs.forEach(task => {
                    res[taskName + ": cutoff for " + task.optionOne] = task.cutoff;
                    res[taskName + ": consistency for " + task.optionOne] = task.consistency;
                });
                res[taskName + ": area under the curve"] = area;
                res[taskName + ": time"] = time;
                return res
            },

            // for each "option one" need to calc where did the user changed his mind and his consistency level
            calcPoint(point, data, taskType) {
                const sorted = this.sortPoint(data, taskType);
                const cutoffPoint = this.getCutoffPoint(sorted, taskType);
                const consistency = this.getConsistency(sorted, cutoffPoint, taskType);

                return {cutoffPoint, consistency}
            },

            getCutoffPoint(answers, taskType) {
                let changingPoints;
                const oposite = taskType == "Option one"  ? "Option two" : "Option one"
                if (answers.every(answer => answer["Answer"] === answer[taskType])) {
                    // this is an edge case, just solving it hard coded
                    changingPoints = [950]
                } else {
                    // trying to find the best index to put a cutoff point -> counting all consistent answers for every index
                    // consistent answer is when answer is option1 and option2 is less than cutoff, or choosing option2 when it's more than cutoff
                    // add all cutoff points that gives maximum consistent answers.
                    let indexesResults = [];
                    for (let changingIndex = 0; changingIndex < answers.length; changingIndex++) {
                        let numOfCorrects = 0;
                        let shouldEqual = false;
                        answers.forEach((answer, currIndex) => {
                            if (currIndex === changingIndex) {
                                shouldEqual = true
                            }
                            if ((answer["Answer"] === answer[oposite]) === shouldEqual) {
                                numOfCorrects++
                            }
                        });
                        indexesResults.push({numOfCorrects, value: parseInt(answers[changingIndex][oposite])})
                    }
                    const maxResult = Math.max(...indexesResults.map(y => y.numOfCorrects));
                    changingPoints = indexesResults.filter(x => x.numOfCorrects === maxResult).map(x => x.value - 50);
                }
                const allTogether = changingPoints.reduce((a, b) => a * b);
                let cutoff = Math.pow(allTogether, 1 / changingPoints.length);
                return cutoff
            },
            getConsistency(answers, cutoff, taskType) {
                // counting consistent answers. consistent answer is choosing option1 when option2 is less than cutoff and choosing option2 when it's more than cutoff
                const oposite = taskType == "Option one"  ? "Option two" : "Option one"
                const numOfConsistencies = answers.filter(answer => {
                    return (answer["Answer"] === answer[taskType] && parseInt(answer[oposite]) < cutoff) ||
                        (answer["Answer"] === answer[taskType] && parseInt(answer[oposite]) >= cutoff)
                }).length;

                // consistency number is consistent answers num / all answers num
                return numOfConsistencies / answers.length
            },

            getNumberFromOptionOne(optionOne) {
                return parseInt(optionOne.match(/\d+/)[0])
            },

            getAreaUnderTheCurve(points) {
                const yFactor = 1000;
                const xFactor = Math.max(...points.map(point => point.optionOne));

                const factoredPoints = this.sortPoint(points.map(point => ({
                    x: point.optionOne / xFactor,
                    y: point.cutoff / yFactor,
                })), 'x');

                let leftPoint = {
                    x: 0,
                    y: 1,
                };
                let area = 0;

                factoredPoints.forEach(point => {
                    area += ((leftPoint.y + point.y) * (point.x - leftPoint.x)) / 2;
                    leftPoint = point;
                });
                return area;
            }

        },
    };
</script>

<style>
    .main-container {
        width: 50vw;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

</style>
