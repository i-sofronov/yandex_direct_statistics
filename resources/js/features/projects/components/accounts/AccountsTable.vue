<script setup lang="ts">
import { PlusIcon } from '@/core/components/ui/icons';
import CreateAccount from '../modal/CreateAccount.vue';
import { inject, PropType, ref } from 'vue';
import Account from '@/features/projects/models/Account.model';
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    accounts: {
        type: Array as PropType<Account[]>,
        required: false,
        default: []
    }
})
const createModal = ref<{ open: () => void; close: () => void }>()


const projectId: number = inject('projectId') ?? 0
</script>

<template>
    <div class="project-accounts">
        <div class="project-accounts__content">
            <div class="project-accounts__head">
                <div class="project-accounts__title">
                    Список аккаунтов
                </div>
                <div class="project-accounts__add-btn">
                    <button @click="createModal && createModal.open()" class="btn --primary --outline --rounded --md">
                        <PlusIcon></PlusIcon>
                        Создать
                    </button>
                </div>
            </div>
            <div class="project-accounts__body">
                <div
                    v-if="accounts && accounts.length > 0"
                    class="project-accounts__table">
                    <div class="project-accounts__table-head">
                        <div class="project-accounts__table-row">
                            <div class="project-accounts__table-cell" style="width: 65px;">
                                ID
                            </div>
                            <div class="project-accounts__table-cell" style="width: 200px;">
                                Название
                            </div>
                            <div class="project-accounts__table-cell" style="width: 200px;">
                                Дата создания
                            </div>
                            <div class="project-accounts__table-cell" style="width: 75px;">
                                Опции
                            </div>
                        </div>
                    </div>
                    <div class="project-accounts__table-body">
                        <div v-for="account in accounts" :key="account.id" class="project-accounts__table-row">
                            <div class="project-accounts__table-cell" style="width: 65px;">
                                {{ account.id }}
                            </div>
                            <div class="project-accounts__table-cell" style="width: 200px;">
                                {{ account.name }}
                            </div>
                            <div class="project-accounts__table-cell" style="width: 200px;">
                                {{ account.createdAt }}
                            </div>
                            <div class="project-accounts__table-cell" style="width: 75px;">
                                <Link
                                    :href="`/accounts/${account.id}?`"
                                    method="delete"
                                    class="btn btn-danger"
                                    :style="{border: 0, padding: 0, color: 'red'}"
                                >
                                    Удалить
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="project-accounts__table --empty">
                    <p>Список пуст, начните добавлять аккаунты<br> и вы сможете управлять ими отсюда</p>
                </div>
            </div>
        </div>
    </div>

    <CreateAccount
        ref="createModal"
        :projectId="projectId"
    ></CreateAccount>
</template>

<style scoped lang="scss">
.project-accounts{
    padding-top: 36px;

    &__head{
        display: flex;
        margin-bottom: 16px;
        gap: 17px;
        align-items: center;
    }

    &__title{
        font-size: 14px;
        font-weight: 500;
    }

    &__body{
        border: 1px solid rgba(21,21,21, .08);
    }

    &__table{
        &-row{
            display: flex;
        }
        &-cell{
            padding: 10px;
            font-size: 12px;
            color:#8D8D8D;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        &.--empty{
            font-size: 12px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 32px;
            color:#8d8d8d;
        }
    }
}
</style>
