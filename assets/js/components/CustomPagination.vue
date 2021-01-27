<template>
    <div>
        <ul unselectable="unselectable" class="ant-pagination">
            <li v-if="pagination.current == 1"  aria-disabled="true" class="ant-pagination-prev ant-pagination-disabled">
                <a class="ant-pagination-item-link">
                    <a-icon type="double-left" />
                </a>
            </li>
            <li v-else   @click="change(1)" class="ant-pagination-prev">
                <a class="ant-pagination-item-link">
                    <a-icon type="double-left" />
                </a>
            </li>
            <li v-if="pagination.current == 1"  aria-disabled="true" class="ant-pagination-disabled ant-pagination-prev">
                <a class="ant-pagination-item-link"><a-icon type="left" /></a>
            </li>
            <li v-else  @click="change(pagination.current - 1)" aria-disabled="true" class=" ant-pagination-prev">
                <a class="ant-pagination-item-link"><a-icon type="left" /></a>
            </li>
            <li v-for="page in pages" @click="change(page.number)" class="ant-pagination-item" :class="page.active ? 'ant-pagination-item-active' : ''">
                <a>{{page.number}}</a>
            </li>
            <li  v-if="pagination.current != Math.ceil(pagination.total / pagination.pageSize)" @click="change(pagination.current + 1)" class="ant-pagination-prev">
                <a class="ant-pagination-item-link">
                    <a-icon type="right" />
                </a>
            </li>
            <li v-else  class="ant-pagination-disabled ant-pagination-prev" >
                <a class="ant-pagination-item-link">
                    <a-icon type="right" />
                </a>
            </li>
            <li  v-if="pagination.current == Math.ceil(pagination.total / pagination.pageSize)" class="ant-pagination-disabled ant-pagination-prev">
                <a class="ant-pagination-item-link">
                    <a-icon type="double-right" />
                </a>
            </li>
            <li v-else  @click="change(Math.ceil(pagination.total / pagination.pageSize))" class="ant-pagination-prev">
                <a class="ant-pagination-item-link">
                    <a-icon type="double-right" />
                </a>
            </li>

            <li class="ant-pagination-prev">
                <a-select
                        :filter-option="false"
                        :placeholder="pageSize +' / page'"
                        style="width: 140px"
                        @change="sizeChange(pagination.current,$event)">
                    <a-select-option
                            v-for="(size, index) in pageSizes"
                            :key="index"
                            :value="size"
                    >{{ size }} / page</a-select-option>
                </a-select>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        components: {},
        props: ['pagination', 'change', 'sizeChange'],
        data() {
            return {
                pages:[],
                pageSizes:[
                    10,
                    50,
                    100,
                ],
                pageSize:10,
            };
        },
        mounted() {
            this.createPagination()
        },
        methods: {
            createPagination(){
                if(this.pagination.current){
                    this.pageSize = this.pagination.pageSize
                    this.pages = []
                    let current = this.pagination.current
                    let last = Math.ceil(this.pagination.total / this.pagination.pageSize) ;
                    let start = 1;
                    if(current >= 4 && last > (current + 2) && last > 5){
                        start = current - 2
                        last = current + 2
                    }else if(current + 2 >= last  && last > 5){
                        start = last - 5
                    }else{
                        start = 1
                        if(last > 5){
                            last = 5
                        }
                    }
                    while(start <= last){

                        let active = false;
                        if(this.pagination.current == start){
                            active = true;
                        }
                        this.pages.push(
                            {
                                number:start,
                                active:active,
                            }
                        )
                        start++;
                    }
                }
            },
        },
        watch: {
            pagination: function () {
                this.createPagination()
            }
        },
        computed: {}

    };
</script>