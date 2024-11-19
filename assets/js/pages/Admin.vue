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
        <div v-if="loading" class="loader">
            <a-spin size="large"/>
        </div>
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
                loading: false,
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
                this.loading=true

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
                this.loading=false
            },

            async getData(params = {}) {
                await this.$store.dispatch("participants/getItems", params);
                this.$axios.get("/api/get/link")
                    .then((res) => {
                        this.link = res.data.link;
                    })
                    .catch((err) => {
                        console.log(err)
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
                this.loading = true
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
                this.loading = false
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
                try {
                    const taskType = Object.keys(this.groupBy(taskAnswers, "Option one")).length  === 1 ? "Option two" : "Option one"
                    const oposite = taskType == "Option one"  ? "Option two" : "Option one"

                    const pointsData = this.groupBy(taskAnswers, taskType);
                    
                    let res = {};
                    let time = 0;
                    let taskRecs = Object.keys(pointsData).map(point => {
                        pointsData[point].forEach(x => time += parseInt(x["Time in milliseconds"]));
                        const {cutoffPoint, consistency} = this.calcPoint(point, pointsData[point], taskType, oposite);
                        const res = {
                            optionOne: pointsData[point][0][taskType], 
                            optionOneAmount: pointsData[point][0]['amountVariableOne'],
                            optionTwoAmount: pointsData[point][0]['amountVariableTwo'],
                            cutoff: cutoffPoint,
                            consistency,
                        }
                        return res;
                    });
                    const area = this.getAreaUnderTheCurve(taskRecs, taskType);
                    taskRecs.forEach(task => {
                        res[taskName + ": cutoff for " + task.optionOne] = task.cutoff;
                        res[taskName + ": consistency for " + task.optionOne] = task.consistency;
                    });
                    res[taskName + ": area under the curve"] = area;
                    res[taskName + ": time"] = time;
                    return res
                } catch(err) {
                    console.log(err)
                }
                
            },

            // for each "option one" need to calc where did the user changed his mind and his consistency level
            calcPoint(point, data, taskType, oposite) {
                const sorted = this.sortPoint(data, oposite);
                
                const res = this.getCutoffPoint(sorted, taskType);

                
                return {cutoffPoint: res.cutoff, consistency: res.consistency}
            },
            filterAnswersAndOrder(answers) {
                if (answers[0]['Test Name'] == 'Deadline' || answers[0]['Test Name'] == 'Rate B') {
                    return answers.sort(({amountTwo:a}, {amountTwo:b}) => b-a).filter(x=>x.amountTwo != 900).reverse();
                }
                else {
                    return answers.sort(({amountTwo:a}, {amountTwo:b}) => b-a).reverse();
                }
            },
            getCutoffPoints(answers) {
                const cutoffs = []
                answers.forEach(x => {
                    cutoffs.push(x.amountTwo + 50)
                });
                
                return [...new Set(cutoffs)];
            },
            getCutoffPoint(answers) {
                let consistentAnswers50 = 0;
                let consistentAnswers100 = 0;
                const filteredAnswers = this.filterAnswersAndOrder(answers)
                const possibleCutoffPoints = this.getCutoffPoints(filteredAnswers)
                answers.forEach(answer => {
                    if (answer.Answer === answer["Option two"]) {
                        consistentAnswers50++;
                    }else if (answer.Answer === answer["Option one"]) {
                        consistentAnswers100++;
                    }
                });

                if (consistentAnswers50 === answers.length) {
                    return {
                        cutoff: 50,
                        consistency: 1
                    }
                }else if (consistentAnswers100 === answers.length) {
                    const cutoff = Math.max(...possibleCutoffPoints)
                    return {
                        cutoff,
                        consistency: 1
                    }
                }
                let indexesResults = [];
                for (let i = 0; i < possibleCutoffPoints.length; i++){
                    const cutoff = possibleCutoffPoints[i]
                    const befores = filteredAnswers.filter(an => an.amountTwo < cutoff)
                    const afters = filteredAnswers.filter(an => an.amountTwo > cutoff)
                    const beforeOptions = [...new Set(befores)]
                    let afterChoice
                    let beforeChoice
                    let afterChanges = 0
                    let beforeChanges = 0
                    if (beforeOptions.length > 0) {
                        if (beforeOptions[beforeOptions.length -1].Answer == answers[0]['Option one']) {
                            afterChoice = answers[0]['Option two']
                            beforeChoice = answers[0]['Option one']
                        } else {
                            afterChoice = answers[0]['Option one']
                            beforeChoice = answers[0]['Option two']
                        }
                        befores.forEach(bf => {
                            if (bf['Answer'] != beforeChoice) {
                                beforeChanges ++
                            }
                        })
                    } else {
                        afterChoice = afters[0].Answer
                    }
                    
                    afters.forEach(af => {
                        if (af['Answer'] != afterChoice) {
                            afterChanges ++
                        }
                    })
                    indexesResults.push ({
                        cutoff,
                        numOfSwitches: beforeChanges + afterChanges
                    })
                }

                // Find the maximum number of correct answers
                const maxChanges = Math.min(...indexesResults.map(result => result.numOfSwitches));


                // Filter to get all cutoff values that have the max number of correct answers
                const bestCutoffs = indexesResults.filter(result => result.numOfSwitches === maxChanges);
                if (bestCutoffs.length == 1) {
                    return {
                        cutoff:  bestCutoffs[0].cutoff,
                        consistency: 1
                    }
                } else {
                    let cutoffSum = 0
                    bestCutoffs.forEach(x => cutoffSum += x.cutoff)

                    const cutoffReturn = cutoffSum / bestCutoffs.length
                    const consistency = 1 - bestCutoffs[0].numOfSwitches * 0.2

                    return {
                        cutoff: cutoffReturn,
                        consistency
                    }

                }
            },

            getAreaUnderTheCurve(points) {
                try {
                    const yFactor = Math.max(...points.map(point => {
                        return parseInt(point.optionOneAmount)
                    }));
                    const xFactor = Math.max(...points.map(point => {
                        return parseInt(point.optionTwoAmount)
                    }));

                    const factoredPoints = this.sortPoint(points.map(point => ({
                        x: parseInt(point.optionOneAmount) / xFactor,
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
                }catch(err) {
                    console.log(err)
                }
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
    .loader {
        background: rgba(0, 0, 0, 0.4);
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 999;

        .ant-spin {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    }

</style>
