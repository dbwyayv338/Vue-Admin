<script setup>
import { reactive, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useLayoutStore } from '@/Stores/layout.js'
//import menu from '@/menu.js'
import AsideMenuLayer from '@/Components/AsideMenuLayer.vue'
import OverlayLayer from '@/Components/OverlayLayer.vue'

let menu = reactive({})
menu = computed(() => usePage().props.navigation.menu)

const layoutStore = useLayoutStore()

</script>

<template>
  <AsideMenuLayer
    v-if="Object.keys(menu).length"
    :menu="menu"
    :class="[layoutStore.isAsideMobileExpanded ? 'left-0' : '-left-60 lg:left-0', {}]"
  />
</template>
