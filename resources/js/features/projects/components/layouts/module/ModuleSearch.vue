<script setup lang="ts">
import SearchIcon from '../../../../../core/components/ui/icons/SearchIcon.vue';
import AppInput from '@/core/components/ui/form/AppInput.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    placeholder: {
        type: String,
        required: false,
        default: 'Поиск по модулю'
    }
})
const emit = defineEmits(['input']);

const searchValue = ref('');

watch(searchValue, () => {
    emit('input', searchValue.value)
})
</script>

<template>
    <div class="module-search input-block">
        <SearchIcon :class="'module-search__icon'"></SearchIcon>
        <AppInput
            :id="'search-input'"
            :placeholder="placeholder"
            :value="searchValue"
            v-model="searchValue"
            @input="searchValue = $event.target.value"
        ></AppInput>
    </div>
</template>

<style scoped lang="scss">
.module-search{
    position: relative;
    max-width:381px;
    width: 100%;

    &__icon{
        position: absolute;
        left: 11px;
        top:12px;

        :deep(path){
            transition: all .3s ease-out;
        }
    }

    .input{
        padding-left: 31px;
        transition: box-shadow .3s ease-out;

        &:not(:placeholder-shown) {
            box-shadow: 0 0 17px 6px rgba(21, 21, 21, 0.1);
        }
    }
}

.module-search:has(.input:not(:placeholder-shown)){
    .module-search__icon{
        :deep(path){
            stroke:#151515!important;
        }
    }
}
</style>
