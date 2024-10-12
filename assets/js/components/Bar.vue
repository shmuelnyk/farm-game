<script>
    import { HorizontalBar } from 'vue-chartjs'

    export default {
        extends: HorizontalBar,
        props: ['time','color'],
        data: () => ({
            characterData: {
                labels: ['Weeks'],
                datasets: [

                ]
            },
            options: {
                scales: {
                    yAxes:[{
                        left: 0, // left edge of the scale bounding box
                        right: 25, // right edge of the bounding box
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false
                },
                responsive: true,
                maintainAspectRatio: false
            }

        }),
        methods: {
            renderMyChart(){
                let isMonths = this.time.includes("month");
                let months;
                let weeks;
                let label;
                if(isMonths){
                    months = this.time.replace(/\D/g,'');
                    weeks = months * 4;
                    label = weeks+' weeks'
                }else{
                    if(this.time == 'a week'){
                        weeks = 1;
                        label = 'a week'
                    }else{
                        weeks = this.time.replace(/\D/g,'');
                        label = weeks+' weeks'
                    }

                }
                this.characterData=
                    {
                        labels: [label],
                        datasets: [
                            {
                                label: '',
                                backgroundColor: this.color,
                                data: [weeks,0,6]
                            }
                        ]
                    }
                    ;
                this.renderChart(this.characterData, this.options)
            }
        },
        mounted () {
            this.renderMyChart()
        }
    }
</script>
