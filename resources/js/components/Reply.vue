<template>
    <div :id="'reply-'+id" class="card mt-4 mb-4" :class="isBest ? 'border-success' : ''">
        <div class="card-header">
            <div class="level">
                <h6 class="flex">
                    <a :href="'/profiles/' + reply.owner.name"
                    v-text="reply.owner.name">
                    </a> said <span v-text="ago"></span>
                </h6>

                <div class="" v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form action="" @submit="update">
                    <div class="form-group">
                        <textarea name="" id="" cols="10" rows="5" class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-info">Update</button>
                    <button class="btn btn-sm btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-secondary btn-sm mr-3" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm mr-3" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-info btn-sm ml-auto" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply</button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite';
    import moment from 'moment';

    export default {
        props: ['reply'],
        components: { Favorite },
        name: "Reply",

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            }
        },

        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow();
            }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id, {
                    body: this.body
                }).catch(error => {
                    flash(error.response.data, 'danger');
                });

                this.editing = false;

                flash('Reply updated');
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);
            },

            markBestReply() {
                axios.post('/replies/'+ this.id +'/best');

                window.events.$emit('best-reply-selected', this.id);
            }
        }
    }
</script>

<style scoped>

</style>
