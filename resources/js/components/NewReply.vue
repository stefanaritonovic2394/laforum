<template>
    <div>
        <div v-if="signedIn">
            <div class="mt-4">
                <div class="form-group">
                    <textarea name="body"
                              id="body"
                              cols="10"
                              rows="5"
                              class="form-control"
                              placeholder="Have something to say?"
                              required
                              v-model="body"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block" @click="addReply">Post</button>
            </div>
        </div>
        <p class="text-center mt-2" v-else>
            Please <a href="/login">login</a> to participate in this discussion
        </p>
    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';

    export default {
        name: "NewReply",

        data() {
            return {
                body: ''
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/api/users", {name: query}, function (usernames) {
                            callback(usernames)
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', { body: this.body })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                    .then(({data}) => {
                       this.body = '';

                       flash('Your reply has been posted');

                       this.$emit('created', data);
                    });
            }
        }
    }
</script>

<style scoped>

</style>
