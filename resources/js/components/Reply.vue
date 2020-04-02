<template>
    <div :id="'reply-'+id" class="card mt-4 mb-4">
        <div class="card-header">
            <div class="level">
                <h6 class="flex">
                    <a :href="'/profiles/'+data.owner.name"
                    v-text="data.owner.name">
                    </a> said <span v-text="ago"></span>
                </h6>

                <div class="" v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea name="" id="" cols="10" rows="5" class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-sm btn-info" @click="update">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-secondary btn-sm mr-3" @click="editing = true">Edit</button>
            <button class="btn btn-danger btn-sm mr-3" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite';
    import moment from 'moment';

    export default {
        props: ['data'],
        components: { Favorite },
        name: "Reply",
        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
                // return this.data.user_id == window.App.user.id;
            },

            ago() {
                return moment(this.data.created_at).fromNow();
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                });

                this.editing = false;

                flash('Reply updated');
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            }
        }
    }
</script>

<style scoped>

</style>