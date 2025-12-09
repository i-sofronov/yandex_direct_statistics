<template>
    <Teleport to="body">
        <Transition>
            <div v-if="modelValue" class="modal">
                <div class="modal__content" :class="contentClass">

                    <div class="modal__head" v-if="showHeader">
                        <slot name="header">
                            <div class="modal__title">
                                {{ title }}
                            </div>
                            <div v-if="subtitle" class="modal__subtitle">
                                {{ subtitle }}
                            </div>
                            <div
                                v-if="closable"
                                @click="close"
                                class="modal__close"
                            >
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M0.5 1L19 19.5" stroke="black" />
                                    <path d="M0.5 19.5L19 1" stroke="black" />
                                </svg>
                            </div>
                        </slot>
                    </div>

                    <div class="modal__body">
                        <slot name="body"></slot>
                    </div>

                    <div class="modal__footer" v-if="showFooter">
                        <slot name="footer">
                            <div class="modal__btns">
                                <button
                                    v-if="closable"
                                    @click="close"
                                    class="btn --primary --outline --xxl"
                                >
                                    Закрыть
                                </button>
                            </div>
                        </slot>
                    </div>
                </div>
                <div
                    v-if="overlay"
                    class="modal__overlay"
                    @click="closeOnOverlay && close()"
                ></div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { defineProps, withDefaults, defineEmits } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true
    },
    title: {
        type: String,
        default: ''
    },
    subtitle: {
        type: String,
        default: null
    },
    size: {
        type: String as () => 'sm' | 'md' | 'lg' | 'xl',
        default: 'md'
    },
    closable: {
        type: Boolean,
        default: true
    },
    overlay: {
        type: Boolean,
        default: true
    },
    closeOnOverlay: {
        type: Boolean,
        default: true
    },
    showHeader: {
        type: Boolean,
        default: true
    },
    showFooter: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits<{
    'update:modelValue': [value: boolean]
    'close': []
}>();

const contentClass = computed(() => `--${props.size}`);

const close = () => {
    emit('update:modelValue', false);
    emit('close');
};
</script>

<style scoped lang="scss">
.modal {
    position: fixed;
    width: 100vw;
    height: 100vh;
    left: 0;
    top: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;

    &__overlay {
        background-color: rgba(21, 21, 21, .8);
        backdrop-filter: blur(12px);
        position: absolute;
        left: 0;
        top: 0;
        z-index: 12;
        width: 100%;
        height: 100%;
    }

    &__content {
        display: flex;
        flex-direction: column;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;

        & > * {
            padding: 27px 35px;
        }

        &.--sm { width: 400px; }
        &.--md { width: 678px; }
        &.--lg { width: 900px; }
        &.--xl { width: 1200px; }

        z-index: 13;
        position: relative;
    }

    &__head {
        border-bottom: 1px solid rgba(21, 21, 21, .08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    &__title {
        font-size: 24px;
        font-weight: 550;
    }

    &__subtitle {
        color: #545454;
        font-size: 14px;
        font-weight: 400;
        margin-top: 9px;
    }

    &__close {
        cursor: pointer;
    }

    &__body {
        flex: 1;
    }

    &__footer {
        border-top: 1px solid rgba(21, 21, 21, .08);
    }
}

.v-enter-active,
.v-leave-active {
    transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
