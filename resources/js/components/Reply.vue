<template>
    
</template>

<script>
    export default {
        props: ['attributes'],
        name: "Reply",
        data() {
            return {
                editing: false,
                body: this.attributes.body
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });

                this.editing = false;

                flash('Reply updated');
            }
        }
    }
</script>

<style scoped>

</style>