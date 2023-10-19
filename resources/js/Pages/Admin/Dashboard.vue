<script setup>
import {Head, useForm, usePage} from '@inertiajs/vue3'
import {computed, ref, onMounted, reactive} from 'vue'
import {useMainStore} from '@/Stores/main'
import {
    mdiAlertBoxOutline,
    mdiAccountMultiple,
    mdiCartOutline,
    mdiChartTimelineVariant,
    mdiFinance,
    mdiReload,
    mdiChartPie
} from '@mdi/js'
import * as chartConfig from '@/Components/Charts/chart.config.js'
import LineChart from '@/Components/Charts/LineChart.vue'
import SectionMain from '@/Components/Base/SectionMain.vue'
import CardBoxWidget from '@/Components/Base/CardBoxWidget.vue'
import CardBox from '@/Components/Base/CardBox.vue'
import NotificationBar from '@/Components/Base/NotificationBar.vue'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/Base/SectionTitleLineWithButton.vue'
import SwitchLanguage from '@/Components/Admin/SwitchLanguage.vue'
import {currentLanguage} from "@/config";

const chartData = ref(null)

const fillChartData = () => {
    chartData.value = chartConfig.sampleChartData()
}

const mainStore = useMainStore()
const user = computed(() => usePage().props.auth.user)
mainStore.setUser(user.value)

//切换为用户默认语言
const defaultLanguage = () => {
    let lang = localStorage[currentLanguage] ?? 'en_US'
    if(lang !== user.value.lang){
        localStorage[currentLanguage] = user.value.lang
        location.reload()
    }
}

onMounted(() => {
    fillChartData()
    defaultLanguage()
})

</script>

<template>
    <LayoutAuthenticated>
        <Head :title="$t('welcome')" />
        <SectionMain>
            <NotificationBar
                :key="Date.now()"
                v-if="$page.props.flash.message"
                color="success"
                :icon="mdiAlertBoxOutline"
            >
                {{ $page.props.flash.message }}
            </NotificationBar>
            <SectionTitleLineWithButton
                :icon="mdiChartTimelineVariant"
                :title="$t('overview')"
                main
            >
                <SwitchLanguage/>
            </SectionTitleLineWithButton>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
                <CardBoxWidget
                    trend="12%"
                    trend-type="up"
                    color="text-emerald-500"
                    :icon="mdiAccountMultiple"
                    :number="512"
                    label="Clients"
                />
                <CardBoxWidget
                    trend="12%"
                    trend-type="down"
                    color="text-blue-500"
                    :icon="mdiCartOutline"
                    :number="7770"
                    prefix="$"
                    label="Sales"
                />
                <CardBoxWidget
                    trend="Overflow"
                    trend-type="alert"
                    color="text-red-500"
                    :icon="mdiChartTimelineVariant"
                    :number="256"
                    suffix="%"
                    label="Performance"
                />
            </div>

            <SectionTitleLineWithButton
                :icon="mdiChartPie"
                :title="$t('trends_overview')"
            />

            <CardBox
                title="Performance"
                :icon="mdiFinance"
                :header-icon="mdiReload"
                class="mb-6"
                @header-icon-click="fillChartData"
            >
                <div v-if="chartData">
                    <line-chart
                        :data="chartData"
                        class="h-96"
                    />
                </div>
            </CardBox>

        </SectionMain>
    </LayoutAuthenticated>
</template>
