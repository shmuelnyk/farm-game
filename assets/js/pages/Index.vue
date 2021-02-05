<template>
    <a-layout id="components-layout-demo-custom-trigger">
        <div v-if="submitting" class="loader">
            <a-spin size="large"/>
        </div>

        <a-layout-content
                :style="{ margin: '24px 16px', padding: '24px', background: '#fff', minHeight: '100vh' }"
        >
            <div>
                <div v-if="!quiz">
                    <div class="container" style="max-width: 70vw">
                        <a-row>
                            <div class="align-center">
                                <img :src="logo" alt="" style="max-width: 35vw;">
                            </div>

                            <h1 class="align-center" style="font-size: 1.6em;">Welcome to the Farmers' decisions study!</h1>
                            <p style="font-size: 1.3em;">
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
                            </p>
                            <div class="align-center">
                                <a-input style="max-width: 30vw" v-model="mkTurkId" placeholder="Please enter MTurk ID"/>
                            </div>

                            <br>
                            <br>
                            <div class="align-center">
                                <a-button type="primary" @click="startQuiz">
                                    I agree
                                </a-button>
                            </div>
                        </a-row>
                    </div>
                </div>
                <div v-else>
                    <div v-if="!quizDone" class="container">
                        <a-row v-if="isTutorial">
                            <h1 class="align-center" style="font-size: 1.6em;">
                                Hello, Welcome to the farm game, in this game you will be the farmer.
                            </h1>
                            <br>
                            <br>
                            <div style="padding-left:24px;padding-right: 24px;font-size: 1.3em;">
                                In each situation presented, you will be asked to make a decision which of
                                two different plants you would like to treat at that time.
                                <br>
                                <br>
                                In order to make a decision, information about the plants will be presented
                                to you in the following way:
                            </div>

                            <br>
                            <br>
                            <br>
                            <br>
                            <div>
                                <a-row :gutter="24" class="questions">

                                    <a-col :span="11" class="flex-col question-one">
                                        <a-card :headStyle="{backgroundColor: '#75ab50',borderRadius: '10px'}"
                                                :title=" tutorialOne.veg.title.toUpperCase() + ' CROP'"
                                                :bordered="false">
                                            <div class="align-center">
                                                <img :src="getImage(tutorialOne.veg.img)" alt="">
                                            </div>

                                            <div class="question-one" v-if="currentTutorial == 1">
                                                <div class="crops">
                                                    <a-tooltip :defaultVisible="true">
                                                        <template slot="title">
                                                            Shows how much money you will get for each
                                                            crop.
                                                        </template>
                                                        <b>
                                                            <span class="amount">
                                                                {{ tutorialOne.amountVariable }}
                                                            </span>
                                                            <br>
                                                            Value of crops
                                                        </b>
                                                    </a-tooltip>
                                                </div>
                                            </div>
                                            <div class="question-one" v-else>
                                                <div class="crops">
                                                <span class="amount">
                                                                {{ tutorialOne.amountVariable }}
                                                            </span>
                                                    <br>
                                                    Value of crops
                                                </div>
                                            </div>
                                            <p class="question-one" v-if="currentTutorial == 2">
                                                <a-tooltip :defaultVisible="true">
                                                    <template slot="title">
                                                        Shows how much time you have to
                                                        irrigate each crop.
                                                    </template>
                                                    <b>Time left in growing season: {{ tutorialOne.timeVariable }}</b>
                                                </a-tooltip>
                                            </p>
                                            <p class="question-one" v-else>
                                                Time left in growing season: {{ tutorialOne.timeVariable }}
                                            </p>
                                            <p class="question-one" v-if="currentTutorial == 3">
                                                <a-tooltip :defaultVisible="true">
                                                    <template slot="title">
                                                        Sometimes a crop can be damaged by pests and the chance
                                                        of getting the whole value decreases.
                                                    </template>
                                                    <b>The chances of yielding from the plants and receiving the money:
                                                        <a-progress status="active"strokeColor="#75ab50" :format="(percent) => percent+'%'"
                                                                    :percent="tutorialOne.chanceVariable "/>
                                                        <br>
                                                        {{ tutorialOne.chanceVariable + '%' }}
                                                    </b>

                                                </a-tooltip>
                                            </p>
                                            <p class="question-one" v-else>
                                                The chances of yielding from the plants and receiving the money:
                                                <br>

                                                <a-progress status="active"strokeColor="#75ab50" :format="(percent) => percent+'%'"
                                                            :percent="tutorialOne.chanceVariable "/>
                                                <br>
                                                {{ tutorialOne.chanceVariable + '%' }}
                                            </p>
                                            <p class="question-one" v-if="currentTutorial == 4">
                                                <a-tooltip :defaultVisible="true">
                                                    <template slot="title">
                                                        When money is received.
                                                    </template>
                                                    <b>Time until harvesting: {{ tutorialOne.deadlineVariable }}</b>
                                                </a-tooltip>

                                            </p>
                                            <p class="question-one" v-else>
                                                Time until harvesting: {{ tutorialOne.deadlineVariable }}
                                            </p>
                                            <p class="question-one" v-if="currentTutorial == 5">
                                                <a-tooltip :defaultVisible="true">
                                                    <template slot="title">
                                                        Shows how much
                                                        irrigation is done and what is the amount of irrigation still
                                                        required
                                                        for the plants to fully grow.
                                                    </template>
                                                    <b>Percentage of work left in growing season: {{
                                                        tutorialOne.workVariable }}</b>
                                                </a-tooltip>
                                                <Bar style="max-height:150px" :chance="tutorialOne.workVariable "></Bar>
                                            </p>
                                            <p class="question-one" v-else>
                                                Percentage of work left in growing season: {{ tutorialOne.workVariable
                                                }}
                                                <Bar style="max-height:150px" :chance="tutorialOne.workVariable "></Bar>
                                            </p>
                                        </a-card>
                                    </a-col>
                                    <a-col :span="11" class="flex-col question-two">
                                        <a-card :headStyle="{backgroundColor: '#609dd2',borderRadius: '10px'}"
                                                :title="tutorialTwo.veg.title.toUpperCase()+ ' CROP'" :bordered="false">
                                            <div class="align-center">
                                                <img :src="getImage(tutorialTwo.veg.img)" alt="">
                                            </div>
                                            <div class="question-two" v-if="currentTutorial == 1">
                                                <div class="crops">
                                                <span class="amount">
                                                    <b>{{ tutorialTwo.amountVariable }}</b>
                                                        </span>
                                                    <br>
                                                    <b>Value of crops</b>
                                                </div>
                                            </div>
                                            <div class="question-two" v-else>
                                                <div class="crops">
                                                <span class="amount">
                                                            {{ tutorialTwo.amountVariable }}
                                                        </span>
                                                    <br>
                                                    Value of crops
                                                </div>

                                            </div>
                                            <p class="question-two" v-if="currentTutorial == 2">
                                                <b>Time left in growing season: {{ tutorialTwo.timeVariable }}</b>

                                            </p>
                                            <p class="question-two" v-else>
                                                Time left in growing season: {{ tutorialTwo.timeVariable }}
                                            </p>
                                            <p class="question-two" v-if="currentTutorial == 3">
                                                <b>The chances of yielding from the plants and receiving the money:
                                                    <a-progress status="active"strokeColor="#609dd2" :format="(percent) => percent+'%'"
                                                                :percent="tutorialTwo.chanceVariable "/>
                                                    <br>
                                                    {{ tutorialTwo.chanceVariable + '%' }}
                                                </b>
                                            </p>
                                            <p class="question-two" v-else>
                                                The chances of yielding from the plants and receiving the money:
                                                <br>

                                                <a-progress status="active"strokeColor="#609dd2" :format="(percent) => percent+'%'"
                                                            :percent="tutorialTwo.chanceVariable "/>
                                                <br>
                                                {{ tutorialTwo.chanceVariable + '%' }}
                                            </p>
                                            <p class="question-two" v-if="currentTutorial == 4">
                                                <b>Time until harvesting: {{ tutorialTwo.deadlineVariable }}</b>
                                            </p>
                                            <p class="question-two" v-else>
                                                Time until harvesting: {{ tutorialTwo.deadlineVariable }}
                                            </p>
                                            <p class="question-two" v-if="currentTutorial == 5">
                                                <b>Percentage of work left in growing season: {{
                                                    tutorialTwo.workVariable }}</b>
                                                <Bar style="max-height:150px" :chance="tutorialTwo.workVariable "></Bar>
                                            </p>
                                            <p class="question-two" v-else>
                                                Percentage of work left in growing season: {{ tutorialTwo.workVariable
                                                }}
                                                <Bar style="max-height:150px" :chance="tutorialTwo.workVariable "></Bar>
                                            </p>
                                        </a-card>
                                    </a-col>
                                </a-row>
                            </div>
                            <br>
                            <br>
                            <div class="align-center">
                                <div v-if="currentTutorial ==5">
                                    <a-button type="primary" :disabled="currentTutorial == 1" @click="prevTutorial">
                                        Prev
                                    </a-button>
                                    <a-button type="primary" @click="startGame">
                                        Start The Game
                                    </a-button>
                                </div>

                                <div v-else>
                                    <a-button type="primary" @click="nextTutorial" v-if="currentTutorial == 0">
                                        Start tutorial
                                    </a-button>
                                    <div v-else>
                                        <a-button type="primary" :disabled="currentTutorial == 1" @click="prevTutorial">
                                            Prev
                                        </a-button>
                                        <a-button type="primary" @click="nextTutorial">
                                            Next
                                        </a-button>
                                    </div>

                                </div>

                            </div>
                        </a-row>
                        <a-row v-else :gutter="24">
                            <a-row :gutter="24" class="questions">
                                <a-col :span="11" class="flex-col question-one">
                                    <a-card :headStyle="{backgroundColor: '#75ab50',borderRadius: '10px'}"
                                            :title="currentQuestion.vegOne.title.toUpperCase()+ ' CROP'"
                                            :bordered="false">
                                        <transition name="fade" mode="out-in">
                                            <div :key="currentQuestion.id">
                                                <div class="align-center">
                                                    <img :src="getImage(currentQuestion.vegOne.img)" alt="">
                                                </div>
                                                <div class="question-one"
                                                     v-if="currentQuestion.highlightOne == 'amountVariableOne' || currentQuestion.highlightTwo == 'amountVariableTwo'">
                                                    <div class="crops">
                                                            <span class="amount">
                                                                <b>{{ currentQuestion.amountVariableOne }}</b>
                                                            </span>
                                                        <br>
                                                        <b>Value of crops</b>
                                                    </div>
                                                </div>
                                                <div class="question-one" v-else>
                                                    <div class="crops">
                                                    <span class="amount">
                                                                {{ currentQuestion.amountVariableOne }}
                                                            </span>
                                                        <br>
                                                        Value of crops
                                                    </div>

                                                </div>
                                                <p class="question-one">
                                                    <b v-if="currentQuestion.highlightOne == 'timeVariableOne' || currentQuestion.highlightTwo == 'timeVariableTwo'">
                                                        Time left in growing season: {{ currentQuestion.timeVariableOne
                                                        }}
                                                    </b>
                                                    <span v-else>
                                            Time left in growing season: {{ currentQuestion.timeVariableOne }}
                                        </span>
                                                </p>
                                                <p class="question-one">
                                                    <b v-if="currentQuestion.highlightOne == 'chanceVariableOne'|| currentQuestion.highlightTwo == 'chanceVariableTwo'">
                                                        The chances of yielding from the plants and receiving the money:
                                                        <a-progress status="active"strokeColor="#75ab50" :format="(percent) => percent+'%'"
                                                                    :percent="currentQuestion.chanceVariableOne"/>
                                                        <br>
                                                        {{ currentQuestion.chanceVariableOne + '%' }}

                                                    </b>
                                                    <span v-else>
                                                    The chances of yielding from the plants and receiving the money:
                                                        <a-progress status="active"strokeColor="#75ab50" :format="(percent) => percent+'%'"
                                                                    :percent="currentQuestion.chanceVariableOne"/>
                                                        <br>
                                                {{ currentQuestion.chanceVariableOne+ '%' }}
                                                </span>
                                                </p>

                                                <p class="question-one">
                                                    <b v-if="currentQuestion.highlightOne == 'deadlineVariableOne' || currentQuestion.highlightTwo == 'deadlineVariableTwo'">
                                                        Time until harvesting: {{ currentQuestion.deadlineVariableOne }}
                                                    </b>
                                                    <span v-else>
                                            Time until harvesting: {{ currentQuestion.deadlineVariableOne }}
                                        </span>
                                                </p>
                                                <p class="question-one">

                                                    <b v-if="currentQuestion.highlightOne == 'workVariableOne' || currentQuestion.highlightTwo == 'workVariableTwo'">
                                                        Percentage of work left in growing season: {{
                                                        currentQuestion.workVariableOne }}
                                                    </b>
                                                    <span v-else>
                                                    Percentage of work left in growing season: {{ currentQuestion.workVariableOne }}
                                                 </span>
                                                    <Bar style="max-height:150px"
                                                         :chance="currentQuestion.workVariableOne "></Bar>

                                                </p>
                                                <br>
                                                <a-button style="width: 100%" type="primary"
                                                          @click="selectAnswer(currentQuestion.option1)"><b>Select {{
                                                    currentQuestion.vegOne.title }}</b></a-button>
                                            </div>

                                        </transition>

                                    </a-card>

                                </a-col>
                                <a-col :span="11" class="flex-col question-two">
                                    <a-card :headStyle="{backgroundColor: '#609dd2',borderRadius: '10px'}"
                                            :title="currentQuestion.vegTwo.title.toUpperCase()+ ' CROP'"
                                            :bordered="false">
                                        <transition name="fade" mode="out-in">
                                            <div :key="currentQuestion.id">
                                                <div class="align-center">
                                                    <img :src="getImage(currentQuestion.vegTwo.img)" alt="">
                                                </div>
                                                <div class="question-two"
                                                     v-if="currentQuestion.highlightTwo == 'amountVariableTwo' || currentQuestion.highlightOne == 'amountVariableOne'">
                                                    <div class="crops">
                                                    <span class="amount">
                                                        <b>{{ currentQuestion.amountVariableTwo }}</b>
                                                            </span>
                                                        <br>
                                                        <b>Value of crops</b>
                                                    </div>
                                                </div>
                                                <div class="question-two" v-else>
                                                    <div class="crops">
                                                    <span class="amount">
                                                                {{ currentQuestion.amountVariableTwoe }}
                                                            </span>
                                                        <br>
                                                        Value of crops
                                                    </div>

                                                </div>
                                                <p class="question-two">
                                                    <b v-if="currentQuestion.highlightTwo == 'timeVariableTwo' || currentQuestion.highlightOne == 'timeVariableOne'">
                                                        Time left in growing season: {{ currentQuestion.timeVariableTwo
                                                        }}
                                                    </b>
                                                    <span v-else>
                                            Time left in growing season: {{ currentQuestion.timeVariableTwo }}
                                        </span>
                                                </p>
                                                <p class="question-two">
                                                    <b v-if="currentQuestion.highlightTwo == 'chanceVariableTwo'|| currentQuestion.highlightOne == 'chanceVariableOne'">
                                                        The chances of yielding from the plants and receiving the money:
                                                        <a-progress status="active"strokeColor="#609dd2" :format="(percent) => percent+'%'"
                                                                    :percent="currentQuestion.chanceVariableTwo"/>
                                                        <br>
                                                        {{ currentQuestion.chanceVariableTwo + '%' }}
                                                    </b>
                                                    <span v-else>
                                            The chances of yielding from the plants and receiving the money:
                                            <a-progress status="active"strokeColor="#609dd2" :format="(percent) => percent+'%'"
                                                        :percent="currentQuestion.chanceVariableTwo"/>
                                                         <br>
                                                        {{ currentQuestion.chanceVariableTwo + '%' }}

                                        </span>
                                                </p>

                                                <p class="question-two">
                                                    <b v-if="currentQuestion.highlightTwo == 'deadlineVariableTwo' || currentQuestion.highlightOne == 'deadlineVariableOne'">
                                                        Time until harvesting: {{ currentQuestion.deadlineVariableTwo }}
                                                    </b>
                                                    <span v-else>
                                            Time until harvesting: {{ currentQuestion.deadlineVariableTwo }}
                                        </span>
                                                </p>
                                                <p class="question-two">

                                                    <b v-if="currentQuestion.highlightTwo == 'workVariableTwo' || currentQuestion.highlightOne == 'workVariableOne'">
                                                        Percentage of work left in growing season: {{
                                                        currentQuestion.workVariableTwo }}
                                                    </b>
                                                    <span v-else>
                                            Percentage of work left in growing season: {{ currentQuestion.workVariableTwo }}
                                        </span>
                                                    <Bar style="max-height:150px"
                                                         :chance="currentQuestion.workVariableTwo "></Bar>

                                                </p>
                                                <br>
                                                <a-button style="width: 100%" type="primary"
                                                          @click="selectAnswer(currentQuestion.option2)"><b>Select {{
                                                    currentQuestion.vegTwo.title}}</b></a-button>
                                            </div>

                                        </transition>
                                    </a-card>


                                </a-col>
                            </a-row>
                        </a-row>
                    </div>
                    <div v-else>
                        <a-row :gutter="24" class="container-absolute">
                            <div class="align-center">
                                <h1 style="font-size: 1.6em">You have completed the farm game.</h1>
                            </div>
                            <br>
                            <br>
                            <p style="font-size: 1.3em;">
                                In order to move on to the next part of the study, in which you will be asked to
                                complete three short questionnaires, please click on the button below:
                            </p>

                            <br><br>
                            <br><br>
                            <div class="align-center" v-if="link">
                                <a-button type="primary" @click="goToLink">
                                    <b>Move to the next part.</b>
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
                currentTaskName: false,
                currentTutorial: 0,
                tutorialOne: {
                    veg: {
                        title: 'Carrot',
                        img: 'carrot',
                    },
                    amountVariable: '1000',
                    timeVariable: '6 month',
                    deadlineVariable: '6 months',
                    chanceVariable: 100,
                    workVariable: '50%',
                },
                tutorialTwo: {
                    veg: {
                        title: 'Corn',
                        img: 'corn',
                    },
                    amountVariable: '300',
                    timeVariable: '6 month',
                    deadlineVariable: '6 months',
                    chanceVariable: 75,
                    workVariable: '20%',
                },
                quizOptions: [
                    {
                        task: 1,
                        name: 'Distance to goal - a',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 month',
                            deadlineVariable: '6 months',
                            chanceVariable: 100,
                        },
                        firstOptions: [
                            '50%',
                            '75%',
                            '100%',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '6 months',
                            chanceVariable: 100,
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
                        task: 2,
                        name: 'Distance to goal - b',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 month',
                            deadlineVariable: '4 months',
                            chanceVariable: 100,
                        },
                        firstOptions: [
                            '40%',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '4 weeks',
                            chanceVariable: 100,
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
                        task: 2,
                        name: 'Distance to goal - b',
                        firstOptionText: 'amountVariable in timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            deadlineVariable: '4 months',
                            chanceVariable: 100,
                        },
                        firstOptions: [
                            '60%',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '6 weeks',
                            chanceVariable: 100,
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
                        task: 2,
                        name: 'Distance to goal - b',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, mainVariable work left, Deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 month',
                            deadlineVariable: '4 months',
                            chanceVariable: 100,
                        },
                        firstOptions: [
                            '80%',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '8 weeks',
                            chanceVariable: 100,
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
                        task: 3,
                        name: 'Deadline',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, workVariable work left deadline in mainVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            workVariable: '20%',
                            chanceVariable: 100
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
                            chanceVariable: 100,
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
                        task: 3,
                        name: 'Deadline - b',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable, workVariable work left deadline in mainVariable month',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 100,
                            workVariable: '40%'
                        },
                        firstOptions: [
                            '4 weeks',
                        ],
                        secondOptionText: 'mainVariable in timeVariable months chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '2 weeks',
                            chanceVariable: 100,
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
                        task: 3,
                        name: 'Deadline - b',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable,workVariable work left deadline in mainVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 100,
                            workVariable: '60%'
                        },
                        firstOptions: [
                            '6 weeks',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '2 weeks',
                            chanceVariable: 100,
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
                        task: 3,
                        name: 'Deadline - b',
                        firstOptionText: 'amountVariable in a timeVariable chanceVariable,workVariable work left deadline in mainVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            chanceVariable: 100,
                            workVariable: '80%'
                        },
                        firstOptions: [
                            '8 weeks',
                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '2 weeks',
                            chanceVariable: 100,
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
                        task: 4,
                        name: 'Expectancy',
                        firstOptionText: 'amountVariable in a timeVariable mainVariable,workVariable work left deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            timeVariable: '6 months',
                            workVariable: '80%',
                            deadlineVariable: '25 weeks',

                        },
                        firstOptions: [
                            75,
                            50,
                            25,

                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '6 months',
                            deadlineVariable: '25 weeks',
                            chanceVariable: 100,
                            workVariable: '100%',
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
                        task: 5,
                        name: 'Delay',
                        firstOptionText: 'amountVariable in a mainVariable chanceVariable, workVariable work left deadline in deadlineVariable',
                        optionOneVariables: {
                            amountVariable: '1000',
                            deadlineVariable: '25 weeks',
                            workVariable: '100%',
                            chanceVariable: 100,
                        },
                        firstOptions: [
                            '1 month',
                            '6 month',
                            '12 month',

                        ],
                        secondOptionText: 'mainVariable in timeVariable chanceVariable, Deadline in deadlineVariable weeks, workVariable work left.',
                        optionTwoVariables: {
                            timeVariable: '1 week ',
                            deadlineVariable: '25 weeks',
                            chanceVariable: 100,
                            workVariable: '100%',
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
                currentQuizTask: undefined,
                vegetables: [
                    {
                        title: 'Tomato',
                        img: "tomatoes"
                    },
                    {
                        title: 'Carrot',
                        img: "carrot"
                    },
                    {
                        title: 'Corn',
                        img: "corn"
                    },
                    {
                        title: 'Potato',
                        img: "potato"
                    },
                    {
                        title: 'Wheat',
                        img: "wheat"
                    },
                ]
            };
        },
        mounted() {
            this.generateQuestions()
        },
        methods: {
            startGame(){
                this.scrollToTop()
                this.isTutorial = false;
            },

            getImage(path) {
                return this[path]
            },
            prevTutorial() {
                if (this.currentTutorial <= 1) {
                    clearTimeout(this.tutorialTimout)
                    return;
                } else {
                    clearTimeout(this.tutorialTimout)
                    this.currentTutorial--;
                }
            },
            nextTutorial() {
                clearTimeout(this.tutorialTimout)
                if (this.currentTutorial == 5) {
                    clearTimeout(this.tutorialTimout)
                    return;
                }
                this.currentTutorial++

                this.tutorialTimout = setTimeout(() => {
                    this.nextTutorial()
                }, 3000)
            },
            onChange(a, b, c) {
                this.currentTutorial++
                if (this.currentTutorial == 5) {
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
                if (!this.questionStart) {
                    this.questionStart = moment()
                } else {
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
                    this.getQuestion();

                } else if (this.currentQuizQuestion === (this.currentQuizLength - 1)) {
                    this.currentPart++;
                    if (!this.quizQuestions[this.currentPart] || this.quizQuestions[this.currentPart].length == 0) {
                        this.submitQuiz();
                        return;
                    }
                    this.currentQuizLength = this.quizQuestions[this.currentPart].length;
                    this.currentQuizQuestion = 0;
                    if(this.quizQuestions[this.currentPart][this.currentQuizQuestion].quizName != this.currentQuestion.quizName){
                        this.showConfirm()
                    }else{
                        this.getQuestion();
                    }

                }

            },
            showConfirm() {
                let self = this
                this.$success({
                    title: 'Good job',
                    centered: true,
                    okText: 'Next task',
                    content: 'You have completed a task.',
                    onOk() {
                        self.getQuestion();
                    }
                });
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
            createOptions(element, qOne, qTwo, veg,id) {
                let firstVariables = element.optionOneVariables
                let secondVariables = element.optionTwoVariables
                let highlightOne = '';
                let highlightTwo = '';
                let option = {
                    id: id,
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
                option.vegOne = veg[0]
                option.vegTwo = veg[1]
                return option

            },
            generateQuestions() {
                let quiz = []
                let vegetables;
                let idCount = 1;
                this.quizOptions.forEach((element, index) => {
                    let testQuestions = []
                    if (!this.currentQuizTask) {
                        this.currentQuizTask = element.task;
                        vegetables = this.getVegetables(2)
                    } else if (this.currentQuizTask != element.task) {
                        this.currentQuizTask = element.task;
                        vegetables = this.getVegetables(2)
                    }
                    element.firstOptions.forEach(firstOption => {
                        element.secondOptions.forEach(secondOption => {
                            let option = this.createOptions(element, firstOption, secondOption, vegetables,idCount)
                            testQuestions.push(option)
                            idCount++;
                        })
                    })
                    quiz[index + 1] = this.shuffleArray(testQuestions);
                })
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
                this.currentTaskName = this.quizQuestions[this.currentPart][this.currentQuizQuestion].quizName;

            },
            scrollToTop(){
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            },
            startQuiz() {
                this.scrollToTop()
                if (!this.mkTurkId) {
                    this.$message.error('MTurk ID is required');
                    return;
                }
                this.getQuestion();
                this.quiz = true;
                this.isTutorial = true;
                this.currentTutorial = 0
                setTimeout(() => {
                    this.nextTutorial()
                }, 10000)
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

    .questions {
        text-align: center;
        display: flex;
        justify-content: space-between;

        h3 {
            text-align: center;
        }

        img {
            margin-bottom: 40px;
            margin-top: 20px;
            max-width: 100px;
            min-width: 100px;
            min-height: 100px;
            max-height: 100px;
            border-radius: 100%;
        }

    }

    .crops {
        .amount {
            font-size: 30px;

        }

        padding: 10px;
        margin: auto;
        width: 50%;
        border: 3px solid;
        font-size: 17px;
        margin-bottom: 20px;
    }

    .question-one {
        color: #75ab50 !important;
        .ant-progress-text{
            color: #75ab50 !important;
        }
    }

    .question-two {

        color: #609dd2 !important;
        .ant-progress-text{
            color: #609dd2!important;
        }
    }

    .ant-card {
        padding: 5px;
        border-radius: 5px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);

        .ant-card-body {

            p {
                font-weight: 600;

                padding-bottom: 10px;

                &:last-child {
                    border-bottom: 0px solid #e8e8e8;
                }

                border-bottom: 1px solid #e8e8e8;
            }
        }

        &:hover {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 #40a9ff;
            cursor: pointer;
        }
    }

    .fade-enter-active {
        transition: opacity 0.2s;
    }

    .fade-leave-active {
        transition: opacity 0.4s;
    }

    .fade-enter, .fade-leave-to, .fade-leave-active {
        opacity: 0;
    }

</style>

