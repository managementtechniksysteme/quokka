<template>
    <div>
        <input v-if="selected_avatar_colour" type="hidden" id="avatar_colour" name="avatar_colour" :value="selected_avatar_colour" />
        <v-swatches v-model="selected_avatar_colour" :swatches="avatar_colours" inline></v-swatches>
    </div>
</template>

<script>
    import VSwatches from 'vue3-swatches'
    import 'vue3-swatches/dist/style.css'

    export default {
        name: "AvatarColourSelector",

        components: { VSwatches },

        data() {
            return {
                selected_avatar_colour: this.current_avatar_colour ? this.current_avatar_colour.color : null,
            }
        },

        props: {
            avatar_colours: {
                type: Array,
                default() {
                    return [];
                }
            },

            current_avatar_colour: {
                type: Object,
                default() {
                    return null;
                }
            }
        }

    }
</script>
