<template>
    <div>
        <input v-if="selected_avatar_colour" type="hidden" id="avatar_colour" name="avatar_colour" :value="selected_avatar_colour" />
        <div class="q-swatch-picker">
            <button
                v-for="swatch in avatar_colours"
                :key="swatch.color"
                type="button"
                class="q-swatch"
                :class="{ 'q-swatch--selected': selected_avatar_colour === swatch.color }"
                :style="{ background: 'color-mix(in srgb, ' + swatch.color + ' 20%, transparent)', color: swatch.color }"
                :title="swatch.label"
                @click="selected_avatar_colour = swatch.color"
            >
                <svg v-if="selected_avatar_colour === swatch.color" class="icon-bs icon-12"><use href="/svg/bootstrap-icons.svg#check"></use></svg>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AvatarColourSelector",

        data() {
            return {
                selected_avatar_colour: this.current_avatar_colour ? this.current_avatar_colour.color : null,
            }
        },

        props: {
            avatar_colours: {
                type: Array,
                default() { return []; }
            },
            current_avatar_colour: {
                type: Object,
                default() { return null; }
            }
        }
    }
</script>
