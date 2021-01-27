<template>
    <a-layout id="components-layout-demo-custom-trigger">
            <a-layout-content
                    :style="{ margin: '24px 16px', padding: '24px', background: '#fff', minHeight: '100vh' }"
            >
                <div class="container">
                    <div v-if="!quiz">
                        <h1 class="align-center">Tutorial</h1>
                        Hello, Welcome to o farm game, in this game you will be the farmer.
                        You will need to make a decision to treat one of two different plants in each
                        situation presented to you.
                        In order to make a decision you will have information about the plants, that will be
                        shown in the following way:

                        <ul>
                            <li>Value of crops – shows how much money you will get for each crop.</li>
                            <li>Time left in growing season – shows how much time you have to irrigate
                                each crop
                            </li>
                            <li>The chances of yielding from the plants and receiving the money –
                                Sometimes a crop can be damaged by pests and the chance of getting the
                                whole value goes down.
                            </li>
                            <li>
                                Time until money is received – money is sometimes payed with delays.
                            </li>
                            <li>
                                Percentage of work required until harvesting – shows how much irrigation is
                                done and what is the amount of irrigation needed for the plants to fully grow.
                            </li>
                        </ul>
                        <div class="align-center">
                            <a-button type="primary" @click="startQuiz">
                                Start quiz
                            </a-button>
                        </div>

                    </div>
                    <div v-else>

                        <div v-if="!quizDone">
                            <a-row :gutter="24">
                                <h1 class="align-center">{{currentQuestion.quizName}}</h1>
                                <a-col :span="12" class="flex-col">
                                    <p>
                                        {{ currentQuestion.firstOptionText }}
                                    </p>
                                    <div class="align-center">
                                        <a-button type="primary" @click="selectAnswer(currentQuestion.option1)">
                                            {{ currentQuestion.option1 }}
                                        </a-button>
                                    </div>
                                </a-col>
                                <a-col :span="12" class="flex-col">
                                    <p>
                                        {{ currentQuestion.secondOptionText }}
                                    </p>
                                    <div class="align-center">
                                        <a-button type="primary" @click="selectAnswer(currentQuestion.option2)">
                                            {{ currentQuestion.option2 }}
                                        </a-button>
                                    </div>

                                </a-col>
                            </a-row>
                        </div>
                        <div v-else>
                            <a-row :gutter="24">
                                <h1 class="align-center">Thank you for participating!</h1>
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
                collapsed: false,
                quiz: false,
                quizDone: false,
                quizOptions: [
                    {
                        name: 'Distance to goal - a',
                        firstOptionText:'1000 in a 6 month for sure, variable work left, Deadline in 6 months',
                        firstOptions: [
                            '25%',
                            '50%',
                            '75%',
                        ],
                        secondOptionText:'variable in 6 months for sure, Deadline in 6 months, 20% work left.',
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
                        firstOptionText:'1000 in a 6 month, deadline in variable 20% work left',
                        secondOptionText:'variable in 6 months for sure, Deadline in 6 months, 20% work left.',
                        firstOptions: [
                            '1 month',
                            '3 month',
                            '6 month',
                        ],
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
                    }
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
                    this.quizDone = true;
                } else {
                    this.$message.error('Something went wrong please try again')
                }
            },
            generateQuestions() {
                let quiz = []
                this.quizOptions.forEach((element, index) => {
                    let testQuestions = []
                    element.firstOptions.forEach(firstOption => {
                        element.secondOptions.forEach(secondOption => {
                            let option = {
                                quizName: element.name,
                                firstOptionText: element.firstOptionText.replace('variable',firstOption),
                                secondOptionText: element.secondOptionText.replace('variable',secondOption),
                                option1: firstOption,
                                option2: secondOption,
                                answer: undefined,
                            }
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
    .container{
        position: absolute;
        min-width: 60vw;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        border: 1px solid grey;
        min-height: 300px;
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

    .flex-col{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 200px;
    }
</style>
