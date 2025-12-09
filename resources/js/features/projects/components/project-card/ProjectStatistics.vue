<template>
    <div class="project-card__statistics">
        <div class="project-card__statistics-head">
            <div class="project-card__statistics-title">
                Статистика по каналам
            </div>
        </div>
        <div v-if="isAccountsNotEmpty" class="project-card__statistics-body project-channels">
            <div
                v-for="(value, key) in accountsTotal"
                :key="key"
                class="project-channels__body"
            >
                <ProjectStatisticsDirect v-if="key === 'yandexDirect' && !value.is_empty" :statistics="value"></ProjectStatisticsDirect>
           </div>
        </div>
        <div v-else class="project-card__statistics-body project-channels --empty" style="opacity: .67; font-size: 12px; padding: 12px">
            Ни одного аккаунта пока не подключено к проекту
        </div>
        <div v-if="isAccountsLengthMoreThenThree" @click="toggleList" class="project-card__statistics-footer">
            Развернуть
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, defineProps, onMounted, PropType, watch } from 'vue';
import {
    ProjectDirectTotalInterface
} from '@/features/projects/models/Project.model';
import ProjectStatisticsDirect from "./ProjectStatisticsDirect.vue"

const props = defineProps({
    accountsTotal: {
        type: Object as PropType<{
            yandexDirect?: ProjectDirectTotalInterface
        }>
    }
})

onMounted(() => {
    initializeStatistics()
})

watch(() => props.accountsTotal, () => {
    initializeStatistics()
})

const initializeStatistics = () => {
    const statisticsWrap = document.querySelectorAll('.project-card__statistics') as HTMLElement[];

    statisticsWrap.forEach(statistics => {
        const statisticsItems = statistics.querySelectorAll('.project-channels__item');

        if(statisticsItems.length > 3) {
            closeList(statistics as HTMLElement)
        }
    })
}

function toggleList(event: Event){
    const listWrap = (event.target as HTMLElement).closest('.project-card__statistics') as HTMLElement;

    if(!listWrap) return

    const projectCard = listWrap.closest('.project-card') as HTMLElement;

    if(!projectCard) return

    const isOpened = listWrap.classList.contains('--opened');
    const projectCardsList = listWrap.closest('.module__body');

    if(!projectCardsList) return

    let scrollTopOffset:null|number = null, footerTitle = null

    if(isOpened){
        closeList(listWrap)
        scrollTopOffset = projectCard.offsetTop;
        footerTitle = 'Развернуть'
    }
    else {
        openList(listWrap)
        scrollTopOffset = listWrap.offsetTop;
        footerTitle = 'Свернуть'
    }

    projectCardsList.scrollTo({behavior: 'smooth', top: scrollTopOffset})
    projectCard.querySelector('.project-card__statistics-footer').innerHTML = footerTitle
}
function closeList(listWrap: HTMLElement){
    const closedListHeight = Array.from(listWrap.querySelectorAll('.project-channels__item'))
        .slice(0, 4).map(listItem => listItem.getBoundingClientRect().height).reduce((a, b) => a + b)

    listWrap.classList.remove('--opened')
    listWrap.style.height = `${closedListHeight + 20}px`
}
function openList(listWrap: HTMLElement){
    listWrap.classList.add('--opened')
    listWrap.style.height = `100%`
}

const isAccountsLengthMoreThenThree = computed(() => {
    const accountsTotal = props.accountsTotal

    let total = 0;

    Object.keys(accountsTotal).forEach(key => {
        if(!accountsTotal[key].is_empty) total++
    })

    return total > 3
})
const isAccountsNotEmpty = computed(() => {
    const accountsTotal = props.accountsTotal

    let isNotEmpty = false;

    for(const key in accountsTotal){
        if(!accountsTotal[key].is_empty){
            isNotEmpty = true
        }
    }

    return isNotEmpty
})
</script>

<style lang="scss" scoped>
@mixin default-border($border-color: rgba(21, 21, 21, 0.08), $border-radius: 12px){
    border: 1px solid $border-color;
    border-radius: $border-radius;
}

.project-channels{
    &__body{
        display: flex;
        flex-direction: column;
    }
}

:deep(.project-channels__item){
    padding: 12px 5px;
    display: flex;
    justify-content: space-between;

    :deep(.project-channels-body){
        display: flex;
        justify-content: space-between;
    }

    :deep(.project-channels:not(:last-child)){
        border-bottom: 1px solid rgba(21,21,21, .08);
    }
}

.project-card__statistics{
    @include default-border(rgba(21,21,21, .08), 11px);

    position: relative;
    width: 100%;
    padding: 12px 15px;
    overflow: hidden;

    &-head{
        padding-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(21,21,21, .08);
    }

    &-title{
        font-size: 13px;
        color:#545454;
    }

    &-footer{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 10px;
        font-size: 12px;
        font-weight: 300;
        color:#545454;
        background-color: rgba(255,255,255, .10);
        backdrop-filter: blur(12px);
        position: absolute;
        bottom:0;
        left:0;
        border-radius: 10px;
        cursor: pointer;
    }

    &.--opened{
        .project-card__statistics-footer{
            position: relative;
            backdrop-filter: none;
            background-color: none;
        }
    }
}
</style>
