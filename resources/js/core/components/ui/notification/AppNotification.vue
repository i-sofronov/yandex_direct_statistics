<template>
    <div class="notification" :class="[`--${type}`, { '--visible': show }]">
        <div class="notification__content">
            <div class="notification__body">
                <div class="notification__left">
                    <div class="notification__icon">
                        <svg v-if="type === 'error'" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10.5" cy="10.5" r="10.5" fill="#FF2D4C"/>
                            <path d="M6 6L10.5 10.5M15 15L10.5 10.5M10.5 10.5L15 6M10.5 10.5L6 15" stroke="white" stroke-width="2"/>
                        </svg>

                        <svg v-else width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10.5" cy="10.5" r="10.5" fill="#36CE68"/>
                            <path d="M6 9L10 14L14.6188 6" stroke="white" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
                <div class="notification__right">
                    <div class="notification__title heading">{{ title }}</div>
                    <div class="notification__text"> {{ text }} </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useNotification } from '@/core/composables/useNotification.js';

const { notification } = useNotification()

const show = computed(() => notification.value.show)
const title = computed(() => notification.value.title)
const text = computed(() => notification.value.text)
const type = computed(() => notification.value.type)
</script>

<style scoped lang="scss">
.notification{
    position: fixed;
    right:15px;
    bottom: 15px;
    background-color: rgba(241, 241, 241, 0.67);
    backdrop-filter: blur(43px);
    border-radius: 15px;
    padding: 21px;
    z-index: 9999;
    width: 100%;
    min-width:290px;
    max-width:480px;
    transform: translateY(120%);
    transition: transform 0.5s ease, opacity 0.5s ease;
    opacity: 0;

    &__body{
        display: flex;
        gap: 11px;
    }

    &.--visible {
        transform: translateY(0);
        opacity: 1;
    }

    &__title{
        font-size: 18px;
        margin-bottom: 12px;
    }

    &__text{
        margin-bottom: 24px;
    }
}

@media (max-width: 599px) {
    .notification{
        max-width:calc(100% - 30px)
    }
}

@media (max-width: 475px) {
    .notification{
        &__text{
            font-size: 13px;
        }
    }
}

@media (max-width: 399px) {
    .notification{
        padding: 16px;

        &__title{
            font-size: 17px;
        }

        &__text{
            font-size: 12px;
        }
    }
}
</style>
