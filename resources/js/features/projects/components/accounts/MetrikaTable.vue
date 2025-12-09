<script setup lang="ts">

import ModuleTableRow from '@/features/projects/components/layouts/module/ModuleTableRow.vue';
import ModuleTableCell from '@/features/projects/components/layouts/module/ModuleTableCell.vue';
import ModuleTable from '@/features/projects/components/layouts/module/ModuleTable.vue';
import { onMounted, onUpdated, PropType, Ref, ref } from 'vue';
import Account, {
    AccountRawDataInterface,
    YandexMetrikaTotalInterface
} from '@/features/projects/models/Account.model';
import MetrikaTableRow from '@/features/projects/components/accounts/MetrikaTableRow.vue';

const accounts: Ref<Account[]> = ref([])

const props = defineProps({
    initialAccounts: {
        type: Array as PropType<AccountRawDataInterface[]>,
        required: true
    }
});

onMounted(() => {
    accounts.value = props.initialAccounts.map(account => new Account(account))
})

onUpdated(() => {
    accounts.value = props.initialAccounts.map(account => new Account(account))
})
</script>

<template>
    <ModuleTable :content-title="'Яндекс Метрика'">
        <template #module-table-head>
            <ModuleTableRow>
                <ModuleTableCell cell-width="65">
                    Опции
                </ModuleTableCell>
                <ModuleTableCell cell-width="65">
                    ID
                </ModuleTableCell>
                <ModuleTableCell cell-width="200">
                    Счетчик
                </ModuleTableCell>
                <ModuleTableCell cell-width="125">
                    Просмотры
                </ModuleTableCell>
                <ModuleTableCell cell-width="100">
                    Визиты
                </ModuleTableCell>
                <ModuleTableCell cell-width="125">
                    Посетители
                </ModuleTableCell>
                <ModuleTableCell cell-width="150">
                    Время
                </ModuleTableCell>
                <ModuleTableCell cell-width="150">
                    Глубина
                </ModuleTableCell>
                <ModuleTableCell cell-width="150">
                    Отказы
                </ModuleTableCell>

                <ModuleTableCell cell-width="65">
                </ModuleTableCell>
            </ModuleTableRow>
        </template>

        <template #module-table-body>
            <MetrikaTableRow
                v-for="account in accounts"
                :key="account.id"
                :id="account.id"
                :name="account.name"
                :total="account.total as YandexMetrikaTotalInterface"
            ></MetrikaTableRow>
        </template>
    </ModuleTable>
</template>

<style scoped lang="scss">

</style>
