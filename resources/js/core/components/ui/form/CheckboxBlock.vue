<template>
    <div
        :class="['form-group', inputWrapClassString]">
        <div
            v-if="label"
            class="form-group__header">
            <label
                v-if="label"
                :for="inputID"
            >
                {{ label }}
            </label>
        </div>
        <input-component
            v-bind:value="modelValue"
            v-on:input="updateValue"
            v-on:focusout="$emit('focusout', $event)"
            :type="inputType"
            :name="inputID"
            :id="inputID"
            :placeholder="inputPlaceholder"
            :class="inputClassList"
            :disabled="disabled"
        ></input-component>

        <span class="error" v-if="error">{{ error }}</span>
    </div>
</template>
<script lang="ts" setup>
import { computed } from 'vue';
import InputComponent from "./AppInput.vue";

interface Props {
    label?: string
    inputType?: string
    inputPlaceholder?: string
    inputClassList?: string[]
    wrapClassList?: string[]
    inputID: string
    error?: string | string[]  // объединение типов
    disabled?: boolean
    modelValue: string
}

const props = withDefaults(defineProps<Props>(), {
    inputType: 'text',
    inputPlaceholder: 'Заполните поле',
    inputClassList: () => [],
    wrapClassList: () => [],
    disabled: false
})

const emit = defineEmits(['update:modelValue', 'focusout'])

const inputWrapClassString = computed(() => {
    return props.wrapClassList && props.wrapClassList.length > 0 ? props.wrapClassList.join(' ') : ''
})

function updateValue(event:Event){
    emit('update:modelValue', (event.target as HTMLInputElement).value)
}
</script>
<style scoped lang="scss">
.form-group{
    &:not(:last-child){
        margin-bottom: 17px;
    }
}
</style>
