<template>
    <a-layout id="components-layout-demo-custom-trigger">
        <div v-if="submitting" class="loader">
            <a-spin  size="large" />
        </div>

        <a-layout-content
                :style="{ margin: '24px 16px', padding: '24px', background: '#fff', minHeight: '100vh' }"
        >
            <div>
                <div v-if="!quiz" class="container">
                    <img :src="logo" alt="" style="max-width: 20vw;">

                    <h3 class="align-center">Welcome to the Farmers' decisions study!</h3>
                    <br>
                    <br>
                    The Hebrew University of Jerusalem supports the practice of protection
                    of human participants in research. The following will provide you with
                    information about the experiment to help you decide whether or not you wish
                    to participate.
                    <br>
                    <br>
                    In this study, supervised by Ruth Netzer, we will ask you to participate in
                    a choice game and fill out three short questionnaires.
                    <br>
                    <br>
                    The aim of this study is to understand decision preferences. There are no
                    right/good or wrong/bad answers in the choice game or the questionnaires.
                    Therefore, please be sure you select the answer that is best fitted for you.
                    <br>
                    <br>
                    Your participation in this study will require approximately 30 minutes. All
                    information you provide will remain confidential and will not be associated with
                    your identity. If for any reason during this study you do not feel comfortable,
                    you may leave without receiving credit but with no further penalties. In this
                    case your information will be discarded. No further penalties will be implicated.
                    <br>
                    <br>
                    If you have any further questions concerning this study, please feel free
                    to contact us through email: ruth.n@mail.huji.ac.il
                    <br>
                    If you understand the statements above, and freely consent to
                    participate in the study, click on the "I agree" button to begin the experiment.
                    <br>
                    <br>
                    <a-input v-model="mkTurkId" placeholder="MK Turk ID"/>
                    <br>
                    <br>
                    <div class="align-center">
                        <a-button type="primary" @click="startQuiz">
                            I agree
                        </a-button>
                    </div>

                </div>
                <div v-else>
                    <div v-if="!quizDone" class="container">
                        <a-row v-if="isTutorial">
                            <h3>
                                Hello, Welcome to the farm game, in this game you will be the farmer.
                            </h3>
                            <br>
                            <br>
                            In each situation presented, you will be asked to make a decision which of
                            two different plants you would like to treat at that time.
                            <br>
                            <br>
                            In order to make a decision, information about the plants will be presented
                            to you in the following way:
                            <br>
                            <br>
                                <div>
                                    <a-row :gutter="24" class="questions">

                                        <a-col :span="11" class="flex-col">
                                            <a-card :title=" tutorialOne.veg.title.toUpperCase() + 'CROP'" :bordered="false" >
                                                <div class="align-center">
                                                    <img :src="getImage(tutorialOne.veg.img)" alt="">
                                                </div>

                                            <p v-if="currentTutorial == 1">
                                                
                                                <a-tooltip  :defaultVisible="true">
                                                    <template slot="title">
                                                        Shows how much money you will get for each
                                                        crop.
                                                    </template>
                                                    <b>Value of crops: {{ tutorialOne.amountVariable }}</b>
                                                </a-tooltip>
                                                
                                            </p>
                                            <p v-else>
                                                Value of crops: {{ tutorialOne.amountVariable }}
                                            </p>
                                            <p v-if="currentTutorial == 2">
                                                <a-tooltip  :defaultVisible="true">
                                                    <template slot="title">
                                                        Shows how much time you have to
                                                        irrigate each crop.
                                                    </template>
                                                    <b>Time left in growing season: {{ tutorialOne.timeVariable }}</b>
                                                </a-tooltip>
                                            </p>
                                            <p v-else>
                                                Time left in growing season: {{ tutorialOne.timeVariable }}
                                            </p>
                                            <p v-if="currentTutorial == 3">
                                                <a-tooltip  :defaultVisible="true">
                                                    <template slot="title">
                                                        Sometimes a crop can be damaged by pests and the chance
                                                        of getting the whole value decreases.
                                                    </template>
                                                    <b>The chances of yielding from the plants and receiving the money :{{
                                                        tutorialOne.chanceVariable }}</b>
                                                </a-tooltip>
                                            </p>
                                            <p v-else>
                                                The chances of yielding from the plants and receiving the money :
                                                {{ tutorialOne.chanceVariable }}
                                            </p>
                                            <p v-if="currentTutorial == 4">
                                                <a-tooltip  :defaultVisible="true">
                                                    <template slot="title">
                                                        When money is received.
                                                    </template>
                                                    <b>Time until harvesting: {{ tutorialOne.deadlineVariable  }}</b>
                                                </a-tooltip>

                                            </p>
                                            <p v-else>
                                                Time until harvesting: {{ tutorialOne.deadlineVariable  }}
                                            </p>
                                            <p v-if="currentTutorial == 5">
                                                <a-tooltip  :defaultVisible="true">
                                                    <template slot="title">
                                                        Shows how much
                                                        irrigation is done and what is the amount of irrigation still required
                                                        for the plants to fully grow.
                                                    </template>
                                                    <b>Percentage of work left in growing season: {{ tutorialOne.workVariable }}</b>
                                                </a-tooltip>
                                                <Bar style="max-height:150px"  :chance="tutorialOne.workVariable "></Bar>
                                            </p>
                                            <p v-else>
                                                Percentage of work left in growing season: {{ tutorialOne.workVariable }}
                                                <Bar style="max-height:150px"  :chance="tutorialOne.workVariable "></Bar>
                                            </p>
                                            </a-card>
                                        </a-col>
                                        <a-col :span="11" class="flex-col">
                                            <a-card :title="tutorialTwo.veg.title.toUpperCase()+ 'CROP'" :bordered="false" >
                                                <div class="align-center">
                                                    <img :src="getImage(tutorialTwo.veg.img)" alt="">
                                                </div>
                                            <p >
                                                Value of crops: {{ tutorialTwo.amountVariable }}
                                            </p>
                                            <p >
                                                Time left in growing season: {{ tutorialTwo.timeVariable }}
                                            </p>
                                            <p >
                                                The chances of yielding from the plants and receiving the money :
                                                {{ tutorialTwo.chanceVariable }}
                                            </p>
                                            <p >
                                                Time until harvesting: {{ tutorialTwo.deadlineVariable  }}
                                            </p>
                                            <p >
                                                Percentage of work left in growing season: {{ tutorialTwo.workVariable }}
                                                <Bar style="max-height:150px"  :chance="tutorialTwo.workVariable "></Bar>
                                            </p>
                                            </a-card>
                                        </a-col>
                                    </a-row>
                                </div>
                            <br>
                            <br>
                            <div class="align-center">
                                <a-button type="primary" @click="isTutorial = false" v-if="currentTutorial ==5">
                                    Start
                                </a-button>
                                <a-button type="primary" @click="nextTutorial" v-else>
                                    Next
                                </a-button>
                            </div>
                        </a-row>
                        <a-row v-else :gutter="24">
                            <a-row :gutter="24" class="questions">
                                <a-col :span="11" class="flex-col"
                                       @click="selectAnswer(currentQuestion.option1)">
                                        <a-card :title="currentQuestion.vegOne.title.toUpperCase()+ 'CROP'" :bordered="false" >
                                            <div class="align-center">
                                                <img :src="getImage(currentQuestion.vegOne.img)" alt="">
                                            </div>
                                            <p>
                                                <b v-if="currentQuestion.highlightOne == 'amountVariableOne'">
                                                    Value of crops: {{ currentQuestion.amountVariableOne }}
                                                </b>
                                                <span v-else>
                                            Value of crops: {{ currentQuestion.amountVariableOne }}
                                        </span>
                                            </p>
                                            <p>
                                                <b v-if="currentQuestion.highlightOne == 'timeVariableOne'">
                                                    Time left in growing season: {{ currentQuestion.timeVariableOne }}
                                                </b>
                                                <span v-else>
                                            Time left in growing season: {{ currentQuestion.timeVariableOne }}
                                        </span>
                                            </p>
                                            <p>
                                                <b v-if="currentQuestion.highlightOne == 'chanceVariableOne'">
                                                    The chances of yielding from the plants and receiving the money : {{ currentQuestion.chanceVariableOne == 'for sure' ? '100%' : currentQuestion.chanceVariableOne }}
                                                </b>
                                                <span v-else>
                                                    The chances of yielding from the plants and receiving the money : {{ currentQuestion.chanceVariableOne == 'for sure' ? '100%' : currentQuestion.chanceVariableOne }}
                                                </span>
                                            </p>

                                            <p>
                                                <b v-if="currentQuestion.highlightOne == 'deadlineVariableOne'">
                                                    Time until harvesting: {{ currentQuestion.deadlineVariableOne }}
                                                </b>
                                                <span v-else>
                                            Time until harvesting: {{ currentQuestion.deadlineVariableOne }}
                                        </span>
                                            </p>
                                            <p>

                                                <b v-if="currentQuestion.highlightOne == 'workVariableOne'">
                                                    Percentage of work left in growing season: {{ currentQuestion.workVariableOne }}
                                                </b>
                                                <span v-else>
                                                    Percentage of work left in growing season: {{ currentQuestion.workVariableOne }}
                                                 </span>
                                                <Bar style="max-height:150px"  :chance="currentQuestion.workVariableOne "></Bar>

                                            </p>
                                        </a-card>
                                </a-col>
                                <a-col :span="11" class="flex-col"
                                       @click="selectAnswer(currentQuestion.option2)">
                                    <a-card :title="currentQuestion.vegTwo.title.toUpperCase()+ 'CROP'" :bordered="false" >
                                        <div class="align-center">
                                            <img :src="getImage(currentQuestion.vegTwo.img)" alt="">
                                        </div>
                                        <p>
                                            <b v-if="currentQuestion.highlightTwo == 'amountVariableTwo'">
                                                Value of crops: {{ currentQuestion.amountVariableTwo }}
                                            </b>
                                            <span v-else>
                                            Value of crops: {{ currentQuestion.amountVariableTwo }}
                                        </span>
                                        </p>
                                        <p>
                                            <b v-if="currentQuestion.highlightTwo == 'timeVariableTwo'">
                                                Time left in growing season: {{ currentQuestion.timeVariableTwo }}
                                            </b>
                                            <span v-else>
                                            Time left in growing season: {{ currentQuestion.timeVariableTwo }}
                                        </span>
                                        </p>
                                        <p>
                                            <b v-if="currentQuestion.highlightTwo == 'chanceVariableTwo'">
                                                The chances of yielding from the plants and receiving the money : {{ currentQuestion.chanceVariableTwo == 'for sure' ? '100%' : currentQuestion.chanceVariableTwo }}
                                            </b>
                                            <span v-else>
                                            The chances of yielding from the plants and receiving the money : {{ currentQuestion.chanceVariableTwo == 'for sure' ? '100%' : currentQuestion.chanceVariableTwo }}

                                        </span>
                                        </p>

                                        <p>
                                            <b v-if="currentQuestion.highlightTwo == 'deadlineVariableTwo'">
                                                Time until harvesting: {{ currentQuestion.deadlineVariableTwo }}
                                            </b>
                                            <span v-else>
                                            Time until harvesting: {{ currentQuestion.deadlineVariableTwo }}
                                        </span>
                                        </p>
                                        <p>

                                            <b v-if="currentQuestion.highlightTwo == 'workVariableTwo'">
                                                Percentage of work left in growing season: {{ currentQuestion.workVariableTwo }}
                                            </b>
                                            <span v-else>
                                            Percentage of work left in growing season: {{ currentQuestion.workVariableTwo }}
                                        </span>
                                            <Bar style="max-height:150px"  :chance="currentQuestion.workVariableTwo "></Bar>

                                        </p>
                                    </a-card>
                                </a-col>
                            </a-row>
                        </a-row>
                    </div>
                    <div v-else>
                        <a-row :gutter="24" class="container-absolute">
                            <div class="align-center">
                                <h3>You have completed the farm game.</h3>
                            </div>
                            <br>
                            <br>
                            In order to move on to the next part of the study, in which you will be asked to
                            complete three short questionnaires, please click on the button below:
                            <br><br>
                            <br><br>
                            <div class="align-center">
                                <a-button type="primary" @click="goToLink">
                                    Move to the next part.
                                </a-button>
                            </div>
                        </a-row>
                    </div>
                </div>
            </div>

        </a-layout-content>
    </a-layout>
</template>

<script>


    import {mapGetters} from 'vuex';
    import moment from "moment";
    import Bar from '../components/Bar'

    export default {
        components: {
            Bar
        },
        computed: {
            ...mapGetters({
                getUser: 'auth/getUser',
            })
        },

        data() {
            return {
                moment,
                form: {},
                logo: require("~/images/logo.png"),
                tomatoes: require("~/images/tomato.jpg"),
                carrot: require("~/images/carrot.png"),
                corn: require("~/images/corn.png"),
                potato: require("~/images/potato.png"),
                wheat: require("~/images/wheat.png"),
                link: undefined,
                mkTurkId: undefined,
                collapsed: false,
                submitting: false,
                quiz: false,
                quizDone: false,
                isTutorial: false,
                currentTutorial: 0,
                tutorialOne: {
                    veg: {
                        title:'Carrot',
                        img:'carrot',
                    },
                    amountVariable: '1000',
                    timeVariable: '6 month',
                    deadlineVariable: '6 months',
                    chanceVariable: 'for sure',
                    workVariable: '50%',
                },
                tutorialTwo: {
                    veg: {
                        title:'Corn',
                        img:'corn',
                    },
                    amountVariable: '300',
                    timeVariable: '6 month',
                    deadlineVariable: '6 months',
                    chanceVariable: 'for sure',
                    workVariable: '20%',
                },
                quizOptions: [
                    {
                        name: 'Distance to goal - a',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 month',
                            deadlineVariable: '6 months',
                            chanceVariable: 'for sure',
                        },
                        firstOptions: [
                            '25%',
                            '50%',
                            '75%',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '6 months',
                            chanceVariable: 'for sure',
                            workVariable: '20%',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Distance to goal - b',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 month',
                            deadlineVariable: '4 months',
                            chanceVariable: 'for sure',
                        },
                        firstOptions: [
                            '50%',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '2 weeks',
                            chanceVariable: 'for sure',
                            workVariable: '20%',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Distance to goal - b',
                        firstOptionText: 'amountVariable in timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            deadlineVariable: '4 months',
                            chanceVariable: 'for sure',
                        },
                        firstOptions: [
                            '75%',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '3 weeks',
                            chanceVariable: 'for sure',
                            workVariable: '20%',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Distance to goal - b',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 month',
                            deadlineVariable: '4 months',
                            chanceVariable: 'for sure',
                        },
                        firstOptions: [
                            '100%',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '4 weeks',
                            chanceVariable: 'for sure',
                            workVariable: '20%',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Deadline',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, workVariable work left deadline in mainVariable month',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            workVariable: '20%',
                            chanceVariable: 'for sure',
                        },
                        firstOptions: [
                            '1 month',
                            '3 month',
                            '6 month',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: 'a week',
                            chanceVariable: 'for sure',
                            workVariable: '20%',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Deadline',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, workVariable work left deadline in mainVariable month',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 'for sure',
                            workVariable: '40%'
                        },
                        firstOptions: [
                            '4 weeks',
                        ],
                        secondOptionText: 'mainVariable in timeVariable months chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '2 weeks',
                            chanceVariable: 'for sure',
                            workVariable: '20%',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Deadline',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable,workVariable work left deadline in mainVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 'for sure',
                            workVariable: '60%'
                        },
                        firstOptions: [
                            '6 weeks',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '2 weeks',
                            chanceVariable: 'for sure',
                            workVariable: '20%',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Deadline',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable,workVariable work left deadline in mainVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 'for sure',
                            workVariable: '80%'
                        },
                        firstOptions: [
                            '8 weeks',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '2 weeks',
                            chanceVariable: 'for sure',
                            workVariable: '20%',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Expectancy',
                        firstOptionText: 'amountVariable in a timeVariable mainVariable,workVariable work left deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            workVariable: '{to be defined}',
                            deadlineVariable: '{to be defined}',
                        },
                        firstOptions: [
                            '75% chance',
                            '50% chance',
                            '25% chance',

                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '{to be defined}',
                            chanceVariable: 'for sure',
                            workVariable: '{to be defined}',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },

                    {
                        name: 'Delay',
                        firstOptionText: 'amountVariable in a mainVariable chanceVariable, workVariable work left deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            deadlineVariable: '{to be defined}',
                            workVariable: '{to be defined}',
                            chanceVariable: '{chance to be defined}',
                        },
                        firstOptions: [
                            '1 month',
                            '6 month',
                            '12 month',

                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable weeks, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '1 week ',
                            deadlineVariable: '{to be defined}',
                            chanceVariable: '{chance to be defined}',
                            workVariable: '{to be defined}',
                        },
                        secondOptions: [
                            100,
                            200,
                            300,
                            400,
                            500,
                            600,
                            700,
                            800,
                            900,
                        ]
                    },
                ],
                currentQuestion: undefined,
                tutorialTimout: undefined,
                currentPart: undefined,
                currentQuizLength: undefined,
                currentQuizQuestion: undefined,
                quizQuestions: undefined,
                questionStart: undefined,
                questionEnd: undefined,
                datacollection: undefined,
                vegetables: [
                    {
                        title:'Tomato',
                        img: "tomatoes"
                    },
                    {
                        title:'Carrot',
                        img: "carrot"
                    },
                    {
                        title:'Corn',
                        img: "corn"
                    },
                    {
                        title:'Potato',
                        img: "potato"
                    },
                    {
                        title:'Wheat',
                        img: "wheat"
                    },
                ]
            };
        },
        mounted() {
            this.generateQuestions()
        },
        methods: {
            fillData () {
                this.datacollection = {
                    labels: [this.getRandomInt(), this.getRandomInt()],
                    datasets: [
                        {
                            label: 'Data One',
                            backgroundColor: '#f87979',
                            data: [this.getRandomInt(), this.getRandomInt()]
                        }, {
                            label: 'Data One',
                            backgroundColor: '#f87979',
                            data: [this.getRandomInt(), this.getRandomInt()]
                        }
                    ]
                }
            },
            getImage(path){
              return this[path]
            },
            nextTutorial(){
                clearTimeout(this.tutorialTimout)
                if(this.currentTutorial == 5){
                    return;
                }
                this.currentTutorial++

                this.tutorialTimout = setTimeout(()=>{
                    this.nextTutorial()
                },3000)
            },
            onChange(a, b, c) {
                this.currentTutorial++
                if(this.currentTutorial == 5){
                    this.isTutorial = false
                }

            },
            getVegetables(n) {
                let result = new Array(n),
                    len = this.vegetables.length,
                    taken = new Array(len);
                while (n--) {
                    let x = Math.floor(Math.random() * len);
                    result[n] = this.vegetables[x in taken ? taken[x] : x];
                    taken[x] = --len in taken ? taken[len] : len;
                }
                return result;
            },
            goToLink() {
                if (this.link) {
                    window.open(this.link,
                        '_blank');
                }
            },
            shuffleArray(array) {
                var currentIndex = array.length, temporaryValue, randomIndex;

                // While there remain elements to shuffle...
                while (0 !== currentIndex) {
                    // Pick a remaining element...
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;

                    // And swap it with the current element.
                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }

                return array;
            },
            async selectAnswer(answer) {
                if(!this.questionStart){
                  this.questionStart= moment()
                }else {
                    let diff = await this.getQuestionTime()
                    this.currentQuestion.time = diff
                }
                this.quizQuestions[this.currentPart][this.currentQuizQuestion].answer = answer
                let quizAnswer = this.quizQuestions[this.currentPart][this.currentQuizQuestion]
                if (!this.form[this.currentPart]) {
                    this.form[this.currentPart] = [];
                }
                this.form[this.currentPart].push(quizAnswer)
                if (this.currentQuizQuestion < (this.currentQuizLength - 1)) {
                    this.currentQuizQuestion++;
                } else if (this.currentQuizQuestion === (this.currentQuizLength - 1)) {
                    this.currentPart++;
                    if (!this.quizQuestions[this.currentPart] || this.quizQuestions[this.currentPart].length == 0) {
                        this.submitQuiz();
                        return;
                    }
                    this.currentQuizLength = this.quizQuestions[this.currentPart].length;
                    this.currentQuizQuestion = 0;

                }
                this.getQuestion();

            },
            async submitQuiz() {
                this.submitting = true;
                let data = new FormData()
                data.append('form', JSON.stringify(this.form))
                data.append('mkturk', this.mkTurkId)
                let res = await this.$axios.post('/api/submit/quiz', data)
                if (res.status == 200) {
                    this.link = res.data.link
                    this.quizDone = true;
                    this.submitting = false;

                } else {
                    this.$message.error('Something went wrong please try again')
                    this.submitting = false;
                }
            },
            createOptions(element, qOne, qTwo) {
                let firstVariables = element.optionOneVariables
                let secondVariables = element.optionTwoVariables
                let highlightOne = '';
                let highlightTwo = '';
                let option = {
                    quizName: '',
                    firstOptionText: '',
                    secondOptionText: '',
                    option1: '',
                    option2: '',
                    amountVariableOne: '',
                    amountVariableTwo: '',
                    timeVariableOne: '',
                    timeVariableTwo: '',
                    deadlineVariableOne: '',
                    deadlineVariableTwo: '',
                    chanceVariableOne: '',
                    chanceVariableTwo: '',
                    workVariableOne: '',
                    workVariableTwo: '',
                    highlightOne: '',
                    highlightTwo: '',
                    time: undefined,
                    answer: undefined,

                }
                let firstString = element.firstOptionText;
                let secondString = element.secondOptionText;
                if (firstVariables['amountVariable'] == undefined) {
                    highlightOne = 'amountVariableOne'
                    firstString = firstString.replaceAll('mainVariable', qOne)
                } else {
                    option.amountVariableOne = firstVariables.amountVariable
                    firstString = firstString.replaceAll('amountVariable', firstVariables.amountVariable)
                }
                if (firstVariables['timeVariable'] == undefined) {
                    highlightOne = 'timeVariableOne'
                    firstString = firstString.replaceAll('mainVariable', qOne)
                } else {
                    option.timeVariableOne = firstVariables.timeVariable
                    firstString = firstString.replaceAll('timeVariable', firstVariables.timeVariable)
                }
                if (firstVariables['deadlineVariable'] == undefined) {
                    highlightOne = 'deadlineVariableOne'
                    firstString = firstString.replaceAll('mainVariable', qOne)

                } else {
                    option.deadlineVariableOne = firstVariables.deadlineVariable
                    firstString = firstString.replaceAll('deadlineVariable', firstVariables.deadlineVariable)
                }
                if (firstVariables['chanceVariable'] == undefined) {
                    highlightOne = 'chanceVariableOne'
                    firstString = firstString.replaceAll('mainVariable', qOne)
                } else {
                    option.chanceVariableOne = firstVariables.chanceVariable
                    firstString = firstString.replaceAll('chanceVariable', firstVariables.chanceVariable)
                }
                if (firstVariables['workVariable'] == undefined) {
                    highlightOne = 'workVariableOne'
                    firstString = firstString.replaceAll('mainVariable', qOne)
                } else {
                    option.workVariableOne = firstVariables.workVariable
                    firstString = firstString.replaceAll('workVariable', firstVariables.workVariable)

                }
                if (secondVariables['amountVariable'] == undefined) {
                    highlightTwo = 'amountVariableTwo'
                    secondString = secondString.replaceAll('mainVariable', qTwo)
                } else {
                    option.amountVariableTwo = secondVariables.amountVariable
                    secondString = secondString.replaceAll('amountVariable', secondVariables.amountVariable)
                }
                if (secondVariables['timeVariable'] == undefined) {
                    highlightTwo = 'timeVariableTwo'
                    secondString = secondString.replaceAll('mainVariable', qTwo)
                } else {
                    option.timeVariableTwo = secondVariables.timeVariable
                    secondString = secondString.replaceAll('timeVariable', secondVariables.timeVariable)
                }
                if (secondVariables['deadlineVariable'] == undefined) {
                    highlightTwo = 'deadlineVariableTwo'
                    secondString = secondString.replaceAll('mainVariable', qTwo)
                } else {
                    option.deadlineVariableTwo = secondVariables.deadlineVariable
                    secondString = secondString.replaceAll('deadlineVariable', secondVariables.deadlineVariable)

                }
                if (secondVariables['chanceVariable'] == undefined) {
                    highlightTwo = 'chanceVariableTwo'
                    secondString = secondString.replace('mainVariable', qTwo)
                } else {
                    option.chanceVariableTwo = secondVariables.chanceVariable
                    secondString = secondString.replace('chanceVariable', secondVariables.chanceVariable)

                }
                if (secondVariables['workVariable'] == undefined) {
                    highlightTwo = 'workVariableTwo'
                    secondString = secondString.replace('mainVariable', qTwo)
                } else {
                    option.workVariableTwo = secondVariables.workVariable
                    secondString = secondString.replace('workVariable', secondVariables.workVariable)

                }
                option.highlightOne = highlightOne
                option.highlightTwo = highlightTwo
                option.quizName = element.name
                option[highlightOne] = qOne
                option[highlightTwo] = qTwo
                option.option1 = qOne
                option.option2 = qTwo
                option.firstOptionText = firstString
                option.secondOptionText = secondString
                option.time = undefined
                let veg = this.getVegetables(2)
                option.vegOne = veg[0]
                option.vegTwo = veg[1]
                return option

            },
            generateQuestions() {
                let quiz = []
                this.quizOptions.forEach((element, index) => {
                    let testQuestions = []
                    element.firstOptions.forEach(firstOption => {
                        element.secondOptions.forEach(secondOption => {
                            let option = this.createOptions(element, firstOption, secondOption)
                            testQuestions.push(option)
                        })
                    })
                    quiz[index + 1] = this.shuffleArray(testQuestions);
                })
                console.log(quiz)
                this.quizQuestions = quiz
            },
             getQuestionTime() {
                let start = this.questionStart;
                let end = moment()
                let ms = end.diff(start);
                this.questionStart = moment();
                return ms;
            },
            async getQuestion() {
                if (!this.questionStart) {
                    this.questionStart = moment()
                }
                if (!this.quizQuestions) {
                    this.generateQuestions()
                }
                if (!this.currentPart) {
                    this.currentPart = 1;
                }
                if (!this.currentQuizLength) {
                    this.currentQuizLength = this.quizQuestions[this.currentPart].length;
                }
                if (!this.currentQuizQuestion) {
                    this.currentQuizQuestion = 0;
                }
                this.currentQuestion = this.quizQuestions[this.currentPart][this.currentQuizQuestion];

            },
            startQuiz() {
                if (!this.mkTurkId) {
                    this.$message.error('MK turk ID is required');
                    return;
                }
                this.getQuestion();
                this.quiz = true;
                this.isTutorial = true;
                this.currentTutorial = 1
                setTimeout(()=>{
                    this.nextTutorial()
                },3000)
            }

        }
    };
</script>

<style lang="scss" scoped>
    .question {
        border: 1px solid grey;
        padding: 15px;
        border-radius: 10px;

        &:hover {
            h4 {
                color: white;
            }

            background-color: #40a9ff;
            color: white;
            border-color: #1890ff;
            cursor: pointer;
        }
    }

    .container-absolute {
        width: 35vw;
        /*border: 1px solid grey;*/
        padding: 50px;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .container {

        width: 40vw;
        /*border: 1px solid grey;*/
        padding: 50px;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .align-center {
        text-align: center;
        flex-direction: column;
        justify-content: space-between;
    }

    .ant-layout-content {
        display: flex;
        justify-content: center;
    }

    .flex-col {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 200px;
    }
    .loader{
        background: rgba(0,0,0,0.4);
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 999;
        .ant-spin{
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    }
    .questions {
        display: flex;
        justify-content: space-between;
        h3{
            text-align: center;
        }
        img{
            margin-bottom: 40px;
            margin-top: 20px;
            max-width: 100px;
            min-width: 100px;
            min-height: 100px;
            max-height: 100px;
            border-radius: 100%;
        }

    }
    .ant-card{
        padding: 5px;
        border-radius: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        .ant-card-body{

            p{
                font-weight: 600;

                padding-bottom: 10px;
                &:last-child{
                    border-bottom: 0px solid #e8e8e8;
                }

                border-bottom: 1px solid #e8e8e8;
            }
        }
        &:hover{
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 #40a9ff;
            cursor: pointer;
        }
    }
</style>

