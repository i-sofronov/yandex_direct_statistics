<script setup lang="ts">
import ModuleTableRow from '@/features/projects/components/layouts/module/ModuleTableRow.vue';
import ModuleTableCell from '@/features/projects/components/layouts/module/ModuleTableCell.vue';
import ModuleTable from '@/features/projects/components/layouts/module/ModuleTable.vue';
import { onMounted, onUpdated, PropType, Ref, ref } from 'vue';
import Account, { AccountRawDataInterface, YandexDirectTotalInterface } from '@/features/projects/models/Account.model';
import DirectTableRow from '@/features/projects/components/accounts/DirectTableRow.vue';

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
    <ModuleTable content-title="Яндекс Директ">
        <template #module-table-head>
            <ModuleTableRow>
                <ModuleTableCell cell-width="65">
                    Опции
                </ModuleTableCell>
                <ModuleTableCell cell-width="65">
                    ID
                </ModuleTableCell>
                <ModuleTableCell cell-width="200">
                    Название
                </ModuleTableCell>
                <ModuleTableCell cell-width="100">
                    Клики
                </ModuleTableCell>
                <ModuleTableCell cell-width="100">
                    Показы
                </ModuleTableCell>
                <ModuleTableCell cell-width="145">
                    Конверсий
                </ModuleTableCell>
                <ModuleTableCell cell-width="95">
                    CTR
                </ModuleTableCell>
                <ModuleTableCell cell-width="145">
                    CPC
                </ModuleTableCell>
                <ModuleTableCell cell-width="145">
                    CPA
                </ModuleTableCell>
                <ModuleTableCell cell-width="65">
                </ModuleTableCell>
            </ModuleTableRow>
        </template>
        <template #module-table-body>
            <DirectTableRow
                v-for="account in accounts"
                :key="account.id"
                :id="account.id"
                :name="account.name"
                :total="account.total as YandexDirectTotalInterface"
            ></DirectTableRow>
        </template>

    </ModuleTable>
</template>

<style scoped lang="scss">

</style>
