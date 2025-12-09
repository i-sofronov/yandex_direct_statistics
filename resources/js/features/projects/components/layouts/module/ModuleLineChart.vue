<template>
    <div class="chart-container">
        <div class="chart-wrapper">
            <canvas ref="lineChart" width="779" height="247"></canvas>
        </div>

        <div class="chart-checkboxes">
            <div class="chart-checkboxes__content">
                <div class="chart-checkboxes__head">
                </div>
                <div class="chart-checkboxes__body">
                    <div
                        v-for="(dataset, index) in datasetsConfig"
                        :key="index"
                        class="checkbox-item"
                    >
                        <label class="checkbox">
                            <input
                                type="checkbox"
                                v-model="dataset.visible"
                                @change="toggleDataset(index)"
                            >
                            <span class="checkbox__indicator" :style="{
                                borderColor: dataset.borderColor,
                                backgroundColor: dataset.visible ? dataset.borderColor : 'transparent'
                            }"></span>
                            <span class="checkbox__label">
                                {{ dataset.label }} <span>{{ dataset.totalValue }}</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref, reactive, PropType, watch, onBeforeMount } from 'vue';
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

interface DatasetConfig {
    label: string;
    data: number[];
    borderColor: string;
    backgroundColor: string;
    fill: boolean;
    visible: boolean;
    totalValue?: string | number;
    key: string;
}

const lineChart = ref<HTMLCanvasElement | null>(null);
let chart: Chart | null = null;

const props = defineProps({
    datasetsConfig: {
        type: Array as PropType<DatasetConfig[]>,
        required: true
    },
    labels: {
        type: Array as PropType<string[]>,
        required: true
    }
})

onMounted(() => initChart());
watch(() => props.datasetsConfig, () => {
    if(!chart) return

    chart.data = {
        labels: props.labels,
        datasets: props.datasetsConfig.map(dataset => ({
            label: dataset.label,
            data: dataset.data,
            borderColor: dataset.borderColor,
            backgroundColor: dataset.backgroundColor,
            fill: dataset.fill
        }))
    }

    chart.update()

    calculateTotals();
})

function initChart(){
    const canvas = lineChart.value;
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    calculateTotals();

    chart = new Chart(ctx, {
        type: "line",
        data: {
            labels: props.labels,
            datasets: props.datasetsConfig.map(dataset => ({
                label: dataset.label,
                data: dataset.data,
                borderColor: dataset.borderColor,
                backgroundColor: dataset.backgroundColor,
                fill: dataset.fill
            }))
        },
        options: {
            indexAxis: 'x',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            elements: {
                point: {
                    radius: 0
                }
            }
        }
    });
}

const calculateTotals = () => {
    props.datasetsConfig.forEach(dataset => {
        const total = dataset.data.reduce((sum, value) => sum + value, 0);

        if(total % 1 > 0){
            dataset.totalValue = total.toFixed(2);
        } else {
            dataset.totalValue = total;
        }
    });

    const clicks = props.datasetsConfig.find(item => item.key === 'clicks')?.totalValue;
    const conversions = props.datasetsConfig.find(item => item.key === 'conversions')?.totalValue;
    const cost = props.datasetsConfig.find(item => item.key === 'cost')?.totalValue;

    const avgCpcDataset = props.datasetsConfig.find(item => item.key === 'avg_cpc');
    const cpcDataset = props.datasetsConfig.find(item => item.key === 'cpc');

    if (avgCpcDataset && clicks > 0) {
        avgCpcDataset.totalValue = (parseFloat(cost) / parseFloat(clicks)).toFixed(2);
    }

    if (cpcDataset && conversions > 0) {
        cpcDataset.totalValue = (parseFloat(cost) / parseFloat(conversions)).toFixed(2);
    }
};

const toggleDataset = (index: number) => {
    if (chart) {
        const meta = chart.getDatasetMeta(index);
        meta.hidden = !props.datasetsConfig[index].visible;
        chart.update();
    }
};
</script>

<style scoped>
.chart-container {
    margin: 0 auto;
}

canvas {
    max-width: calc(100% - 100px);
    height: 220px;
}

.chart-checkboxes {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.chart-checkboxes__content {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.chart-checkboxes__body {
    gap: 13px 21px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.checkbox-item {
    display: flex;
    align-items: center;
}

.checkbox {
    display: flex;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    font-size: 14px;
    color: #333;
}

.checkbox input[type="checkbox"] {
    display: none;
}

.checkbox__indicator {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    border: 2px solid;
    position: relative;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.checkbox input[type="checkbox"]:checked + .checkbox__indicator::after {
    content: '✓';
    color: white;
    font-size: 12px;
    font-weight: bold;
    line-height: 1;
}

.checkbox__label {
    font-size: 12px;
    display: flex;
    gap: 10px;

    span{
        font-weight: 500;
    }
}
</style>
