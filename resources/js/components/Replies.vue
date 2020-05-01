<template>
    <div>
        <div v-for="(reply, index) in items" v-bind:key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <p class="mt-3" v-if="$parent.locked">
            This thread has been locked. You can't leave more replies.
        </p>
        <new-reply @created="add" v-else></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply';
    import NewReply from "./NewReply";
    import collection from '../mixins/collection';

    export default {
        name: "Replies",
        components: { Reply, NewReply },

        mixins: [collection],

        data() {
            return {
                dataSet: false
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },

            url(page) {
                if (! page) {
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }

                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            }
        }
    }
</script>

<style scoped>

</style>
