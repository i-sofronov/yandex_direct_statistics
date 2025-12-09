<script setup lang="ts">
import { computed, inject, PropType, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ModuleTableCell from '@/features/projects/components/layouts/module/ModuleTableCell.vue';
import AppTooltipItem from '@/core/components/ui/tooltip/AppTooltipItem.vue';
import AppTooltip from '@/core/components/ui/tooltip/AppTooltip.vue';
import TableIndicator from '@/features/projects/components/accounts/TableIndicator.vue';
import ModuleTableRow from '@/features/projects/components/layouts/module/ModuleTableRow.vue';
import DotsIcon from '@/core/components/ui/icons/DotsIcon.vue';
import { useTooltip } from '@/features/projects/composables/useTooltip';
import { EnterIcon } from '@/core/components/ui/icons';
import Account, { YandexDirectTotalInterface } from '@/features/projects/models/Account.model';

const props = defineProps({
    id: {
        type: Number,
        required: true
    },
    name: {
        type: String,
        required: true
    },
    total: {
        type: Object as PropType<YandexDirectTotalInterface>,
        required: true
    },
});

const tooltipId = computed(() => {
    return 'direct-table-row-tooltip-' + props.id
})

const menuButtonRef = ref<HTMLElement>();
const menuRef = ref<HTMLElement>();

const { isOpen, floatingStyles, toggle } = useTooltip(
    false,
    menuButtonRef,
    menuRef,
    tooltipId.value
)

const projectId = inject('projectId', 0)
</script>

<template>
    <ModuleTableRow>
        <ModuleTableCell cell-width="65">
            <div :id="tooltipId" style="width: 100%; cursor: pointer" @click="toggle" ref="menuButtonRef" class="module-opts">
                <DotsIcon style="pointer-events: none"></DotsIcon>
            </div>

            <AppTooltip
                v-show="isOpen"
                ref="menuRef"
                class="menu"
                :style="floatingStyles"
            >
                <AppTooltipItem :class="'--danger'">
                    <AppTooltipItem :class="'--danger'">
                        <Link
                            :href="`/accounts/${id}`"
                            method="delete"
                            class="btn btn-danger"
                            :style="{border: 0, padding: 0}"
                            preserve-scroll
                        >
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.8125 4.8125C4.92853 4.8125 5.03981 4.85859 5.12186 4.94064C5.20391 5.02269 5.25 5.13397 5.25 5.25V10.5C5.25 10.616 5.20391 10.7273 5.12186 10.8094C5.03981 10.8914 4.92853 10.9375 4.8125 10.9375C4.69647 10.9375 4.58519 10.8914 4.50314 10.8094C4.42109 10.7273 4.375 10.616 4.375 10.5V5.25C4.375 5.13397 4.42109 5.02269 4.50314 4.94064C4.58519 4.85859 4.69647 4.8125 4.8125 4.8125ZM7 4.8125C7.11603 4.8125 7.22731 4.85859 7.30936 4.94064C7.39141 5.02269 7.4375 5.13397 7.4375 5.25V10.5C7.4375 10.616 7.39141 10.7273 7.30936 10.8094C7.22731 10.8914 7.11603 10.9375 7 10.9375C6.88397 10.9375 6.77269 10.8914 6.69064 10.8094C6.60859 10.7273 6.5625 10.616 6.5625 10.5V5.25C6.5625 5.13397 6.60859 5.02269 6.69064 4.94064C6.77269 4.85859 6.88397 4.8125 7 4.8125ZM9.625 5.25C9.625 5.13397 9.57891 5.02269 9.49686 4.94064C9.41481 4.85859 9.30353 4.8125 9.1875 4.8125C9.07147 4.8125 8.96019 4.85859 8.87814 4.94064C8.79609 5.02269 8.75 5.13397 8.75 5.25V10.5C8.75 10.616 8.79609 10.7273 8.87814 10.8094C8.96019 10.8914 9.07147 10.9375 9.1875 10.9375C9.30353 10.9375 9.41481 10.8914 9.49686 10.8094C9.57891 10.7273 9.625 10.616 9.625 10.5V5.25Z"
                                    fill="#EC4427" />
                                <path
                                    d="M12.6875 2.625C12.6875 2.85706 12.5953 3.07962 12.4312 3.24372C12.2671 3.40781 12.0446 3.5 11.8125 3.5H11.375V11.375C11.375 11.8391 11.1906 12.2842 10.8624 12.6124C10.5342 12.9406 10.0891 13.125 9.625 13.125H4.375C3.91087 13.125 3.46575 12.9406 3.13756 12.6124C2.80937 12.2842 2.625 11.8391 2.625 11.375V3.5H2.1875C1.95544 3.5 1.73288 3.40781 1.56878 3.24372C1.40469 3.07962 1.3125 2.85706 1.3125 2.625V1.75C1.3125 1.51794 1.40469 1.29538 1.56878 1.13128C1.73288 0.967187 1.95544 0.875 2.1875 0.875H5.25C5.25 0.642936 5.34219 0.420376 5.50628 0.256282C5.67038 0.0921872 5.89294 0 6.125 0L7.875 0C8.10706 0 8.32962 0.0921872 8.49372 0.256282C8.65781 0.420376 8.75 0.642936 8.75 0.875H11.8125C12.0446 0.875 12.2671 0.967187 12.4312 1.13128C12.5953 1.29538 12.6875 1.51794 12.6875 1.75V2.625ZM3.60325 3.5L3.5 3.55163V11.375C3.5 11.6071 3.59219 11.8296 3.75628 11.9937C3.92038 12.1578 4.14294 12.25 4.375 12.25H9.625C9.85706 12.25 10.0796 12.1578 10.2437 11.9937C10.4078 11.8296 10.5 11.6071 10.5 11.375V3.55163L10.3967 3.5H3.60325ZM2.1875 2.625H11.8125V1.75H2.1875V2.625Z"
                                    fill="#EC4427" />
                            </svg>

                            Удалить
                        </Link>
                    </AppTooltipItem>
                </AppTooltipItem>
            </AppTooltip>
        </ModuleTableCell>

        <ModuleTableCell cell-width="65">
            {{ id }}
        </ModuleTableCell>

        <ModuleTableCell cell-width="200">
            {{ name }}
        </ModuleTableCell>

        <ModuleTableCell cell-width="100">
            <TableIndicator
                :indicator="total?.clicks"
                :dynamic="total.dynamic.clicks"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="100">
            <TableIndicator
                :indicator="total.impressions"
                :dynamic="total.dynamic.impressions"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="145">
            <TableIndicator
                :indicator="total.conversions"
                :dynamic="total.dynamic.conversions"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="95">
            <TableIndicator
                :indicator="total.ctr.toFixed(2)"
                :dynamic="total.dynamic.ctr.toFixed(2)"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="145">
            <TableIndicator
                :indicator="total.cpc.toFixed(2)"
                :dynamic="total.dynamic.cpc.toFixed(2)"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="145">
            <TableIndicator
                :indicator="total.cpa.toFixed(2)"
                :dynamic="total.dynamic.cpa.toFixed(2)"
            ></TableIndicator>
        </ModuleTableCell>

        <ModuleTableCell cell-width="65">
            <Link :href="'/projects/' + projectId + '/accounts/' + id">
                <EnterIcon></EnterIcon>
            </Link>
        </ModuleTableCell>
    </ModuleTableRow>
</template>

<style scoped lang="scss">

</style>
