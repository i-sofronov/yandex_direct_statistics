<script setup lang="ts">
import { computed, PropType } from 'vue';

const props = defineProps({
    id: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    classList: {
        type: Array as PropType<string[]>,
        required: false,
        default: []
    },
    value: {
        type: Boolean,
        required: true
    }
})

const emit = defineEmits(['update:modelValue', 'change']);

const checkboxClassList = computed(() => {
    return ['checkbox__checkbox', ...props.classList].join(' ')
})

const handleChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    emit('change', target.checked);
}

</script>

<template>
    <div class="checkbox">
        <input
            type="checkbox"
            :class="checkboxClassList"
            :id="id"
            :checked="value"
            @change="handleChange">
        <label :for="id">{{ label }}</label>
    </div>
</template>

<style scoped lang="scss">
.checkbox{
    &__checkbox{
        display: none
    }

    &__checkbox + label{
        position: relative;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        display:flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
    }

    &__checkbox + label::before{
        content: '';
        position: relative;
        left: 0;
        top: 0;
        width: 17px;
        height: 17px;
        border: 1px solid #000;
        background-color: rgba(0,0,0,0);
        border-radius: 5px;
    }

    &__checkbox:checked + label::before{
        content: "✓";
        color: #151515;
        font-size: 11px;
        font-weight: bold;
        line-height: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }


}
</style>
