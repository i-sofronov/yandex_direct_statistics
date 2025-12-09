<template>
    <button :class="['btn', buttonClassList, buttonLoading, buttonSuccess, buttonError]">
        <slot></slot>

        <div class="btn__spin">
            <div class="btn__spinner"></div>
        </div>
    </button>
</template>

<script lang="ts" setup>

import { computed, PropType } from 'vue';

const props = defineProps({
    style: {
        type: String as PropType<'--primary'>,
        required: false,
        default: '--primary'
    },
    fill: {
        type: String as PropType<'--filled' | '--outline'>,
        default: '--filled'
    },
    size: {
        type: String as PropType<'--xxl' | '--xl' | '--md'>,
        default: '--md'
    },
    border:{
        type: String as PropType<'--rounded'>,
        default: null
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
    isSuccess: {
        type: Boolean,
        default: false,
    },
    isError: {
        type: Boolean,
        default: false,
    }
});

const buttonClassList = computed(() => {
    return props.style + ' ' + props.fill + ' ' + props.size + ' ' + (props.border || '')
})

const buttonLoading = computed(() => {
    return props.isLoading ? '--loading' : ''
})

const buttonSuccess = computed(() => {
    return props.isSuccess ? '--success' : ''
})

const buttonError = computed(() => {
    return props.isError ? '--error' : ''
})

</script>

<style scoped lang="scss">
.btn {
    outline: none;
    border: 1px solid;
    border-radius: 8px;
    padding: 9px 11px;
    display: flex;
    flex-direction: row;
    gap: 5px;
    align-items: center;
    font-size: 13px;
    transition: all .3s ease-out;
    line-height: 1;
    text-align: center;
    justify-content: center;
    position: relative;
    overflow: hidden;

    &.--primary.--outline {
        background-color: rgba(0, 0, 0, 0);
        border-color: #151515;
        color: #151515;
    }

    &.--primary.--filled {
        background-color: #151515;
        border-color: #151515;
        color: #fff;
    }

    &.--xxl {
        padding: 14px 46px;
    }

    &.--xl {
        padding: 12px 32px;
        width: 100%;
    }

    &.--md {
        padding: 6px 11px;
        width: fit-content;
    }

    &.--rounded {
        border-radius: 35px;
    }

    &__spin {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #fff;

        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    &__spinner {
        width: 17px;
        height: 17px;
        border: 1px solid transparent;
        border-top: 1px solid #151515;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    &.--primary.--filled {
        .btn__spin{
            background-color: #151515;
        }

        .btn__spinner{
            border-top: 2px solid #fff;
        }
    }

    &.--loading {
        .btn__spin {
            opacity: 1;
            visibility: visible;
        }

        :slotted(*) {
            opacity: 0;
            visibility: hidden;
        }
    }

    &.--success{
        background-color: #1DB491!important;
        color:#fff!important;
        border-color: #1DB491!important;
    }

    &.--error{
        background-color: #FF455C!important;
        color:#fff!important;
        border-color: #FF455C!important;
    }

    &.--error :slotted(svg){
        path{
            stroke: #fff!important;
        }
    }

    &.--success :slotted(svg){
        path{
            stroke: #fff!important;
        }
    }
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
