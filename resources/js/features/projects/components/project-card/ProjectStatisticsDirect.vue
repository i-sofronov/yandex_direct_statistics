<template>
    <div class="project-channels__item">
        <div class="project-channels__item-info channels-info">
            <div class="channels-info__title">
                <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_314_2413)">
                        <mask id="mask0_314_2413" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="15" height="14">
                            <path d="M7.52957 14C11.5598 14 14.8269 10.866 14.8269 7C14.8269 3.13401 11.5598 0 7.52957 0C3.49938 0 0.232269 3.13401 0.232269 7C0.232269 10.866 3.49938 14 7.52957 14Z" fill="white"/>
                        </mask>
                        <g mask="url(#mask0_314_2413)">
                            <path d="M14.8269 0H0.232269V14H14.8269V0Z" fill="#315BEA"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.7875 9.10441L10.5446 12.6541C10.1996 13.6381 10.7554 14.6934 11.7855 15.0112C12.8156 15.329 13.9301 14.789 14.2747 13.8051L17.9574 3.29091C17.9712 3.25503 17.9842 3.21872 17.9955 3.18202C18.3271 2.20331 17.7717 1.15908 16.7481 0.843217C16.2928 0.702804 15.8213 0.729821 15.407 0.888581L4.39375 3.45601C3.34212 3.70114 2.68577 4.72004 2.9278 5.73177C3.16983 6.74354 4.21821 7.36499 5.26983 7.11987L9.02591 6.24421L-8.23013 21.4356L-5.48217 24.3079L11.7875 9.10441Z" fill="url(#paint0_linear_314_2413)"/>
                        </g>
                    </g>
                    <defs>
                        <linearGradient id="paint0_linear_314_2413" x1="-5.07636" y1="13.8018" x2="23.9631" y2="0.670004" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#FFF01E"/>
                            <stop offset="1" stop-color="#FFD21E"/>
                        </linearGradient>
                        <clipPath id="clip0_314_2413">
                            <rect width="15" height="14" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>

                Яндекс Директ
            </div>

            <div
                v-if="isGood"
                class="channels-info__mark">
                <div class="channels-info__mark-icon">
                    <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.302032" width="8.81584" height="8.81584" rx="3" fill="#1DB491"/>
                        <path d="M2.33655 3.72998L4.0319 6.10347L7.08354 2.71277" stroke="white"/>
                    </svg>
                </div>
                <div class="channels-info__mark-title">
                    Все хорошо
                </div>
            </div>

            <div
                v-else-if="isCouldBeBetter"
                class="channels-info__mark">
                <div class="channels-info__mark-icon">
                    <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="9" height="9" rx="3" fill="#FFD21E"/>
                        <line x1="2" y1="4.5" x2="7" y2="4.5" stroke="white"/>
                    </svg>
                </div>
                <div class="channels-info__mark-title">
                    Можно улучшить
                </div>
            </div>

            <div
                v-else-if="isBad"
                class="channels-info__mark">
                <div class="channels-info__mark-icon">
                    <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="9" height="9" rx="3" fill="#FF455C"/>
                        <path d="M3 3L4.5 4.5M6 6L4.5 4.5M4.5 4.5L3 6L6 3" stroke="white"/>
                    </svg>
                </div>
                <div class="channels-info__mark-title">
                    Требует исправления
                </div>
            </div>

            <div
                v-else-if="isEmpty"
                class="channels-info__mark">
                <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="9" height="9" rx="3" fill="#545454" fill-opacity="0.61"/>
                </svg>
                <div class="channels-info__mark-title">
                    Статистика отсутствует
                </div>
            </div>
        </div>
        <div class="project-channels__item-stats channels-stats">
            <div class="channels-stats__item">
                <div class="channels-stats__title">
                    Конверсии
                </div>
                <div class="channels-stats__stats">
                    <span class="channels-stats__stats-digit">
                        {{ statistics.conversions.current }}
                    </span>
                    <div
                        v-show="statistics.conversions.dynamic !== 0"
                        :class="['channels-stats__stats-dynamic', (statistics.conversions.dynamic < 0 ? '--negative' : '--positive')]">
                        {{ statistics.conversions.dynamic }}
                    </div>
                </div>
            </div>
            <div class="channels-stats__item">
                <div class="channels-stats__title">
                    CPA
                </div>
                <div class="channels-stats__stats">
                    <span class="channels-stats__stats-digit">
                        {{ statistics.cpa.current.toFixed(2) }}
                    </span>
                    <div
                        v-show="statistics.cpa.dynamic !== 0"
                        :class="['channels-stats__stats-dynamic', (statistics.cpa.dynamic < 0 ? '--negative' : '--positive')]">
                        {{ statistics.cpa.dynamic.toFixed(2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>

import { computed, PropType } from 'vue';

interface Statistics{
    converstions: {
        current: number,
        dynamic: number
    },
    cpa: {
        current: number,
        dynamic: number
    }
}

const props = defineProps({
    statistics: {
        type: Object as PropType<Statistics>,
        required: true
    }
})


const isGood = computed(() => {
    return props.statistics.conversions.dynamic > 0 && props.statistics.cpa.dynamic > 0
})
const isCouldBeBetter = computed(() => {
    console.log(props.statistics.conversions.dynamic, props.statistics.cpa.dynamic);
    return props.statistics.conversions.dynamic <= 0 && props.statistics.cpa.dynamic > 0
        || props.statistics.conversions.dynamic > 0 && props.statistics.cpa.dynamic <= 0
})
const isBad = computed(() => {
    return props.statistics.conversions.dynamic <= 0 && props.statistics.cpa.dynamic < 0 ||
        props.statistics.conversions.dynamic < 0 && props.statistics.cpa.dynamic <= 0 ||
        props.statistics.conversions.dynamic < 0 && props.statistics.cpa.dynamic < 0;
})
const isEmpty = computed(() => {
    return props.statistics.conversions.dynamic === 0 && props.statistics.cpa.dynamic === 0
        && props.statistics.conversions.current === 0 && props.statistics.cpa.current === 0
})

</script>

<style scoped>

</style>
