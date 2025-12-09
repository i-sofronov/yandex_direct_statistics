<script setup lang="ts">
import ModuleTableRow from '@/features/projects/components/layouts/module/ModuleTableRow.vue';
import ModuleTableCell from '@/features/projects/components/layouts/module/ModuleTableCell.vue';
import ModuleTable from '@/features/projects/components/layouts/module/ModuleTable.vue';
import { onMounted, onUpdated, PropType, Ref, ref } from 'vue';
import Account, { AccountRawDataInterface, YandexDirectTotalInterface } from '@/features/projects/models/Account.model';
import SocialsTableRow from '@/features/projects/components/accounts/SocialsTableRow.vue';

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
    <ModuleTable content-title="Социальные сети">
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
                <ModuleTableCell cell-width="125">
                    Подписчики
                </ModuleTableCell>
                <ModuleTableCell cell-width="100">
                    Просмотр.
                </ModuleTableCell>
                <ModuleTableCell cell-width="100">
                    Лайки
                </ModuleTableCell>
                <ModuleTableCell cell-width="100">
                    Коммент.
                </ModuleTableCell>
                <ModuleTableCell cell-width="75">
                    Посты
                </ModuleTableCell>
                <ModuleTableCell cell-width="75">
                    Репосты
                </ModuleTableCell>
                <ModuleTableCell cell-width="85">
                    ER
                </ModuleTableCell>
                <ModuleTableCell cell-width="65">
                </ModuleTableCell>
            </ModuleTableRow>
        </template>
        <template #module-table-body>
            <SocialsTableRow
                v-for="account in accounts"
                :key="account.id"
                :id="account.id"
                :name="account.name"
                :metadata="account.metadata"
                :total="account.total"
            ></SocialsTableRow>
        </template>

    </ModuleTable>
</template>

<style scoped lang="scss">

</style>
