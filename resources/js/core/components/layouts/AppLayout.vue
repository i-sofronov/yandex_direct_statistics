<script setup lang="ts">

import Notification from '@/core/components/ui/notification/AppNotification.vue'
import AppFlashHandler from '@/core/components/ui/notification/AppFlushHandler.vue'
import { useNotificationProvider } from '@/core/composables/useNotification';
import { useFlashNotifications  } from '@/core/composables/useFlushNotifications'
import { onMounted } from 'vue';

const { notification } = useNotificationProvider()

</script>

<template>
    <div class="wrapper">
        <div class="__container">
            <div class="layout">
                <main class="main">
                    <slot></slot>
                </main>
            </div>
        </div>

        <AppFlashHandler />

        <Notification
            :title="notification.title"
            :text="notification.text"
            :type="notification.type"
            :duration="notification.duration"
            :show="notification.show"
        />
    </div>
</template>

<style scoped lang="scss">
.wrapper{
    width: 100%;
    height: 100%;
    min-height:100vh;
    position: relative;
    background-color: #F0F0F0;
    overflow: hidden;
}
.__container{
    width: 100%;
    max-width: 1460px;
    padding: 10px;
    margin: 0 auto;
}

.layout{
    width: 100%;
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    grid-template-rows: auto 1fr auto;
    gap: 10px;
    min-height: calc(100vh - 20px);
}

.main{
    background-color: #fff;
    padding: 28px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0px 4px 12px rgba(21,21,21, .15);
    grid-column: span 12;
}


</style>
