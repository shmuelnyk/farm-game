<script>
    import { Doughnut } from 'vue-chartjs'

    export default {
        extends: Doughnut,
        props: ['chance'],
        data() {
            return {
            characterData: {
                labels: false,
                datasets: []
            },
            options: {

                responsive: true,
                maintainAspectRatio: false
            },
            myNumber:0
            }
        }
        ,
        watch:{
            chance: {
                handler(val){
                    this.renderMyChart()
                },
                deep: true
            }
        },
        methods: {
            renderMyChart(){
                let number;

                if(this.chance == 'for sure' ){
                    number = 100
                }else{
                    number =  this.chance.replace(/\D/g,'');
                }
                this.myNumber++;
                let numberTwo = 100 - number;
                this.characterData.labels = ['Left','Done']
                this.characterData .datasets =
                    [
                        {
                            label: [number+'%',numberTwo+'%'],
                            data: [number,numberTwo],
                            backgroundColor: ['#ff0b00','#2dff1a'],
                        },
                    ];
                this.renderChart(this.characterData, this.options)
            }
        },
        mounted () {
            this.renderMyChart()
        }
    }
</script>