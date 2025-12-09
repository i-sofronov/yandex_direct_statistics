<script setup lang="ts">
import ModuleTableRow from '@/features/projects/components/layouts/module/ModuleTableRow.vue';
import ModuleTableCell from '@/features/projects/components/layouts/module/ModuleTableCell.vue';
import ModuleTable from '@/features/projects/components/layouts/module/ModuleTable.vue';
import { onMounted, onUpdated, PropType, Ref, ref } from 'vue';
import Account, { AccountRawDataInterface, YandexDirectTotalInterface } from '@/features/projects/models/Account.model';
import BusinessTableRow from '@/features/projects/components/accounts/BusinessTableRow.vue';

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
    <ModuleTable content-title="Яндекс Бизнес">
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
                <ModuleTableCell cell-width="100">
                    Отзывы
                </ModuleTableCell>
                <ModuleTableCell cell-width="100">
                    Рейтинг
                </ModuleTableCell>
                <ModuleTableCell cell-width="95">
                    Звонков
                </ModuleTableCell>
                <ModuleTableCell cell-width="195">
                    Маршрутов
                </ModuleTableCell>
                <ModuleTableCell cell-width="145">
                    Действий
                </ModuleTableCell>
                <ModuleTableCell cell-width="145">
                    Переходов
                </ModuleTableCell>
                <ModuleTableCell cell-width="65">
                </ModuleTableCell>
            </ModuleTableRow>
        </template>
        <template #module-table-body>
            <BusinessTableRow
                v-for="account in accounts"
                :key="account.id"
                :id="account.id"
                :name="account.name"
                :total="account.total"
            ></BusinessTableRow>
        </template>

    </ModuleTable>
</template>

<style scoped lang="scss">

</style>
