<template>
    <ul class="pagination justify-content-center" v-if="shouldPaginate">
        <li class="page-item" v-show="prevUrl">
            <a class="page-link" href="#" tabindex="-1" rel="prev" @click.prevent="page--">Previous</a>
        </li>
        <!--<li class="page-item"><a class="page-link" href="#">1</a></li>-->
        <!--<li class="page-item"><a class="page-link" href="#">2</a></li>-->
        <!--<li class="page-item"><a class="page-link" href="#">3</a></li>-->
        <li class="page-item" v-show="nextUrl">
            <a class="page-link" href="#" rel="next" @click.prevent="page++">Next</a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['dataSet'],
        name: "Paginator",

        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },

        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },

            page() {
                this.broadcast().updateUrl();
            }
        },

        computed: {
            shouldPaginate() {
                return !! this.prevUrl || !! this.nextUrl;
            }
        },

        methods: {
            broadcast() {
                return this.$emit('changed', this.page);
            },

            updateUrl() {
                history.pushState(null, null, '?page=' + this.page);
            }
        }
    }
</script>

<style scoped>

</style>