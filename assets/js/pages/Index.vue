<template>
    <a-layout id="components-layout-demo-custom-trigger">
            <a-layout-content
                    :style="{ margin: '24px 16px', padding: '24px', background: '#fff', minHeight: '100vh' }"
            >
                <div >
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
                        <div class="align-center">
                            <a-button type="primary" @click="startQuiz">
                                I agree
                            </a-button>
                        </div>

                    </div>
                    <div v-else >
                        <div v-if="!quizDone" class="container">
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
                            <ul>
                                <li><b>Value of crops</b> – shows how much money you will get for each crop.</li>
                                <li><b>Time left in growing season</b> – shows how much time you have to irrigate
                                    each crop
                                </li>
                                <li><b>The chances of yielding from the plants and receiving the money</b> –
                                    Sometimes a crop can be damaged by pests and the chance of getting the
                                    whole value goes down.
                                </li>
                                <li>
                                    <b>Time until money is received</b> – money is sometimes payed with delays.
                                </li>
                                <li>
                                    <b>Percentage of work required until harvesting</b> – shows how much irrigation is
                                    done and what is the amount of irrigation needed for the plants to fully grow.
                                </li>
                            </ul>
                            <a-row :gutter="24">
                                <h1 class="align-center">{{currentQuestion.quizName}}</h1>
                                <br>
                                <a-row :gutter="24" class="questions">
                                    <a-col :span="11" class="flex-col question" @click="selectAnswer(currentQuestion.option1)">
                                        <h4>CORN CROP</h4>
                                        <p>
                                            Amount {{ currentQuestion.amountVariableOne }}
                                        </p>
                                        <p>
                                            in {{ currentQuestion.timeVariableOne }}
                                        </p>
                                        <p>
                                            {{ currentQuestion.chanceVariableOne }}
                                        </p>
                                        <p>
                                            {{ currentQuestion.workVariableOne }} work left
                                        </p>
                                        <p>
                                            Deadline in {{ currentQuestion.deadlineVariableOne }}
                                        </p>

                                    </a-col>
                                    <a-col :span="11" class="flex-col question"  @click="selectAnswer(currentQuestion.option2)">
                                        <h4>BARLEY CROP</h4>
                                        <p>
                                            Amount {{ currentQuestion.amountVariableTwo }}
                                        </p>
                                        <p>
                                            in {{ currentQuestion.timeVariableTwo }}
                                        </p>
                                        <p>
                                            {{ currentQuestion.chanceVariableTwo }}
                                        </p>
                                        <p>
                                            {{ currentQuestion.workVariableTwo }} work left
                                        </p>
                                        <p>
                                            Deadline in {{ currentQuestion.deadlineVariableTwo }}
                                        </p>
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

    export default {
        components: {
            // agile: VueAgile

        },
        computed: {
            ...mapGetters({
                getUser: 'auth/getUser',
            })
        },

        data() {
            return {
                form: {},
                logo: require("~/images/logo.png"),
                link: undefined,
                collapsed: false,
                quiz: false,
                quizDone: false,
                quizOptions: [
                    {
                        name: 'Distance to goal - a',
                        firstOptionText:'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables:{
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
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables:{
                            amountVariable: '1000',
                            timeVariable: '6 month',
                            deadlineVariable: '4 months',
                            chanceVariable: 'for sure',
                        },
                        firstOptions: [
                            '50%',
                        ],
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables:{
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            deadlineVariable: '4 months',
                            chanceVariable: 'for sure',
                        },
                        firstOptions: [
                            '75%',
                        ],
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables:{
                            amountVariable: '1000',
                            timeVariable: '6 month',
                            deadlineVariable: '4 months',
                            chanceVariable: 'for sure',
                        },
                        firstOptions: [
                            '100%',
                        ],
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in a timeVariable chanceVariable, workVariable work left deadline in mainVariable month',
                        optionOneVariables:{
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
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in a timeVariable chanceVariable, workVariable work left deadline in mainVariable month',
                        optionOneVariables:{
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 'for sure',
                            workVariable: '40%'
                        },
                        firstOptions: [
                            '4 weeks',
                        ],
                        secondOptionText:'mainVariable in timeVariable months chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in a timeVariable chanceVariable,workVariable work left deadline in mainVariable',
                        optionOneVariables:{
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 'for sure',
                            workVariable: '60%'
                        },
                        firstOptions: [
                            '6 weeks',
                        ],
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in a timeVariable chanceVariable,workVariable work left deadline in mainVariable',
                        optionOneVariables:{
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 'for sure',
                            workVariable: '80%'
                        },
                        firstOptions: [
                            '8 weeks',
                        ],
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in a timeVariable mainVariable,workVariable work left deadline in deadlineVariable',
                        optionOneVariables:{
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
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables:{
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
                        firstOptionText:'amountVariable in a mainVariable chanceVariable, workVariable work left deadline in deadlineVariable',
                        optionOneVariables:{
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
                        secondOptionText:'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable weeks, workVariable work left.',
                        optionTwoVariables:{
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
                currentPart: undefined,
                currentQuizLength: undefined,
                currentQuizQuestion: undefined,
                quizQuestions: undefined,
            };
        },
        mounted() {
        },
        methods: {
            getImage(question){
                console.log(question)
            },
            goToLink(){
              if(this.link){
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
            selectAnswer(answer) {
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
                let data = new FormData()
                data.append('form', JSON.stringify(this.form))
                let res = await this.$axios.post('/api/submit/quiz', data)
                if (res.status == 200) {
                    this.link = res.data.link
                    this.quizDone = true;
                } else {
                    this.$message.error('Something went wrong please try again')
                }
            },
            createOptions(element,qOne,qTwo){
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
                        answer: undefined,

                }
                let firstString = element.firstOptionText;
                let secondString = element.secondOptionText;
                if(firstVariables['amountVariable'] == undefined){
                    highlightOne = 'amountVariableOne'
                    firstString = firstString.replaceAll('mainVariable',qOne)
                }else{
                    option.amountVariableOne = firstVariables.amountVariable
                    firstString =firstString.replaceAll('amountVariable',firstVariables.amountVariable)
                }
                if(firstVariables['timeVariable'] == undefined){
                    highlightOne = 'timeVariableOne'
                    firstString = firstString.replaceAll('mainVariable',qOne)
                }else{
                    option.timeVariableOne = firstVariables.timeVariable
                    firstString =firstString.replaceAll('timeVariable',firstVariables.timeVariable)
                }
                if(firstVariables['deadlineVariable'] == undefined){
                    highlightOne = 'deadlineVariableOne'
                    firstString = firstString.replaceAll('mainVariable',qOne)

                }else{
                    option.deadlineVariableOne = firstVariables.deadlineVariable
                    firstString = firstString.replaceAll('deadlineVariable',firstVariables.deadlineVariable)
                }
                if(firstVariables['chanceVariable'] == undefined){
                    highlightOne = 'chanceVariableOne'
                    firstString = firstString.replaceAll('mainVariable',qOne)
                }else{
                    option.chanceVariableOne = firstVariables.chanceVariable
                    firstString = firstString.replaceAll('chanceVariable',firstVariables.chanceVariable)
                }
                if(firstVariables['workVariable'] == undefined){
                    highlightOne = 'workVariableOne'
                    firstString = firstString.replaceAll('mainVariable',qOne)
                }else{
                    option.workVariableOne = firstVariables.workVariable
                    firstString = firstString.replaceAll('workVariable',firstVariables.workVariable)

                }
                if(secondVariables['amountVariable'] == undefined){
                    highlightTwo = 'amountVariableTwo'
                    secondString = secondString.replaceAll('mainVariable',qTwo)
                }else{
                    option.amountVariableTwo = secondVariables.amountVariable
                    secondString = secondString.replaceAll('amountVariable',secondVariables.amountVariable)
                }
                if(secondVariables['timeVariable'] == undefined){
                    highlightTwo = 'timeVariableTwo'
                    secondString = secondString.replaceAll('mainVariable',qTwo)
                }else{
                    option.timeVariableTwo = secondVariables.timeVariable
                    secondString = secondString.replaceAll('timeVariable',secondVariables.timeVariable)
                }
                if(secondVariables['deadlineVariable'] == undefined){
                    highlightTwo = 'deadlineVariableTwo'
                    secondString = secondString.replaceAll('mainVariable',qTwo)
                }else{
                    option.deadlineVariableTwo = secondVariables.deadlineVariable
                    secondString = secondString.replaceAll('deadlineVariable',secondVariables.deadlineVariable)

                }
                if(secondVariables['chanceVariable'] == undefined){
                    highlightTwo = 'chanceVariableTwo'
                    secondString = secondString.replace('mainVariable',qTwo)
                }else{
                    option.chanceVariableTwo = secondVariables.chanceVariable
                    secondString = secondString.replace('chanceVariable',secondVariables.chanceVariable)

                }
                if(secondVariables['workVariable'] == undefined){
                    highlightTwo = 'workVariableTwo'
                    secondString = secondString.replace('mainVariable',qTwo)
                }else{
                    option.workVariableTwo = secondVariables.workVariable
                    secondString = secondString.replace('workVariable',secondVariables.workVariable)

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
                return option

            },
            generateQuestions() {
                let quiz = []
                this.quizOptions.forEach((element, index) => {
                    let testQuestions = []
                    element.firstOptions.forEach(firstOption => {
                        element.secondOptions.forEach(secondOption => {
                            let option = this.createOptions(element,firstOption,secondOption)
                            testQuestions.push(option)
                        })
                    })
                    quiz[index + 1] = this.shuffleArray(testQuestions);
                })
                this.quizQuestions = quiz
            },
            getQuestion() {
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
                this.getQuestion();
                this.quiz = true;
            }

        }
    };
</script>

<style lang="scss" scoped>
    .question{
        border: 1px solid grey;
        padding: 15px;
        border-radius: 10px;
        &:hover{
            h4{
                color: white;
            }
            background-color: #40a9ff;
            color: white;
            border-color: #1890ff;
            cursor: pointer;
        }
    }
    .container-absolute{
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
        transform: translate(-50%,-50%);
    }
    .container{

        width: 35vw;
        /*border: 1px solid grey;*/
        padding: 50px;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .align-center{
        text-align: center;
        flex-direction: column;
        justify-content: space-between;
    }
    .ant-layout-content{
         display: flex;
         justify-content: center;
     }

    .flex-col{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 200px;
    }
    .questions{
        display: flex;
        justify-content: space-between;
    }
</style>

