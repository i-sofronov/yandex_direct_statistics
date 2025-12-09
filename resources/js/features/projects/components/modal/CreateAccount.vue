<script setup lang="ts">
import { defineExpose, inject, onMounted, PropType, reactive, ref } from 'vue';
import AppModal from "@/core/components/ui/modal/AppModal.vue"
import { useModal } from '@/core/composables/useModal'

const { isOpen, open, close } = useModal(false);

type callplaceType = 'accounts' | 'projects'


defineProps({
    callplace: {
        type: String as PropType<callplaceType>,
        required: false,
        default: null
    }
})

const onClose = () => {};

defineExpose({
    open: open,
    close: close
});

const projectId = inject('projectId', 0);
</script>

<template>
    <AppModal
        v-model="isOpen"
        title="Создать аккаунт"
        size="md"
        @close="onClose"
    >

        <template #body>
            <div class="account-data">
                <div class="account-data__platforms">
                    <div class="account-data__platforms-items">
                        <a :href="'/projects/' + projectId + '/accounts/connect?service_type=yandex_direct&callplace=' + callplace" class="account-data__platforms-item platform-item">
                            <div class="platform-item__content">
                                <div class="platform-item__head">
                                    <div class="platform-item__title">
                                        Яндекс Директ
                                    </div>
                                </div>
                                <div class="platform-item__body">
                                    <div class="platform-item__text">
                                        Смотрите статистику по кампаниям
                                    </div>
                                </div>
                                <div class="platform-item__footer">
                                    <div class="platform-item__arrow">
                                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 7.73145H15M15 7.73145L7.5 0.731445M15 7.73145L7.5 14.7314" stroke="#151515" stroke-width="2"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                     </div>
                </div>
            </div>
        </template>
    </AppModal>
</template>

<style scoped lang="scss">
.account-data{
    &__platforms{
        &-title{
            font-size: 14px;
            color: #151515;
            margin-bottom: 17px;
        }

        &-items{
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        &-item{
            width: calc(100% / 2 - 5px);
        }
    }
}

@mixin default-border($border-color: rgba(21, 21, 21, 0.08), $border-radius: 12px){
    border: 1px solid $border-color;
    border-radius: $border-radius;
}

.platform-item{
    @include default-border;
    padding: 15px;
    color: #151515;
    transition: border-color .55s ease-in-out;
    &__content{
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    &__head{
        margin-bottom: 11px;
    }

    &__body{
        margin-bottom: 32px;
    }

    &__footer{
        display: flex;
        flex-direction: row-reverse;
        margin-top: auto;
    }


    &__title{
        font-size: 17px;
        font-weight: 500;
    }

    &__text{
        font-size: 12px;
        font-weight: 400;
    }

    &__arrow{
        width: 15px;
        overflow: hidden;
    }

    &:hover{
        border-color:#151515;

        .platform-item__arrow{
            svg{
                animation: pushArrow .55s ease-in-out forwards;
            }
        }

    }
}

@keyframes pushArrow {
    0% {
        transform: none;
    }
    50% {
        transform: translateX(100%);
    }
    50.1% {
        transform: translateX(-100%);
    }
    to {
        transform: none;
    }
}
</style>
