<script setup>
import {useLayoutStore} from '@/Stores/layout.js'
import {useStyleStore} from '@/Stores/style.js'
import NavBar from '@/Components/Base/NavBar.vue'
import AsideMenu from '@/Components/Base/AsideMenu.vue'
import FooterBar from '@/Components/Base/FooterBar.vue'

const styleStore = useStyleStore()

const layoutStore = useLayoutStore()
</script>

<template>
    <div
        :class="{ 'dark': styleStore.darkMode, 'overflow-hidden lg:overflow-visible': layoutStore.isAsideMobileExpanded }">
        <div
            :class="{ 'ml-60 lg:ml-0': layoutStore.isAsideMobileExpanded }"
            class="pt-14 xl:pl-60 w-screen transition-position lg:w-auto lg:h-screen bg-gray-50 dark:bg-slate-800 dark:text-slate-100"
        >
            <NavBar :class="{ 'ml-60 lg:ml-0': layoutStore.isAsideMobileExpanded }"/>
            <AsideMenu/>
            <slot/>
            <FooterBar/>
        </div>
        <van-back-top/>
    </div>
</template>
