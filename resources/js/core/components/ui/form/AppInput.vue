<script lang="ts">
import { PropType } from 'vue';

export default {
    name: "AppInput",
    props: {
        type: {type: String, required: false, default: 'text'},
        placeholder: {type: String, required: false, default: 'Заполните поле'},
        classList: { type: Array as PropType<string[]>, required: false, default: [] as string[] },
        id: { type: String, required: true },
        disabled: { type: Boolean, required: false, default: false },
        value: { type: String, required: true }
    },
    computed:{
        inputClassString(){
            return this.classList && this.classList.length > 0 ? this.classList.join(' ') : ''
        },
    },
    methods:{

    }
}
</script>

<template>
    <input
        v-on:input="$emit('input', $event)"
        v-on:focusout="$emit('focusout', $event)"
        :value="value"
        :type="type"
        :name="id"
        :id="id"
        :placeholder="placeholder"
        :class="['input', inputClassString]"
        :disabled="disabled"
    >
</template>

<style scoped lang="scss">
@mixin default-border($border-color: rgba(21, 21, 21, 0.08), $border-radius: 12px){
    border: 1px solid $border-color;
    border-radius: $border-radius;
}

.input{
    @include default-border(#151515, 8px);

    font-size: 13px;
    width: 100%;
    padding: 10px 11px;
}
</style>
