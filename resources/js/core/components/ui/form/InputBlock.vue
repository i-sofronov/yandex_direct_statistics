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

        <div class="form-group__body">
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
        </div>

        <div class="form-group__footer">
            <transition name="fade">
                <span class="error" v-if="error">{{ error }}</span>
            </transition>
        </div>
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
    error?: string | string[]
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

    &__header{
        margin-bottom: 11px;

        label{
            font-weight: 450;
            font-size: 14px;
        }
    }

    .error{
        color:#FF455C;
        font-size: 13px;
        display: inline-block;
        padding-top: 7px;

    }
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
