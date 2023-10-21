<script setup>
import {router} from '@inertiajs/vue3'
import {mdiLogout, mdiClose, mdiContactlessPayment} from '@mdi/js'
import {computed, reactive} from 'vue'
import {useLayoutStore} from '@/Stores/layout.js'
import {useStyleStore} from '@/Stores/style.js'
import AsideMenuList from '@/Components/Base/AsideMenuList.vue'
import AsideMenuItem from '@/Components/Base/AsideMenuItem.vue'
import BaseIcon from '@/Components/Base/BaseIcon.vue'
import CardBoxModal from '@/Components/Base/CardBoxModal.vue'

defineProps({
    menu: {
        type: Object,
        default: () => {
        }
    }
})

const emit = defineEmits(['menu-click'])

const layoutStore = useLayoutStore()

const styleStore = useStyleStore()

const logoutItem = computed(() => ({
    name: 'Logout',
    icon: mdiLogout,
    color: 'info'
}))

const menuClick = (event, item) => {
    emit('menu-click', event, item)
}

const logout = reactive({
    isModalActive: false,
    confirm: (e) => {
        logout.isModalActive = true
        return false;
    },
    submit: () => {
        router.post(route('logout'))
    }
})
</script>

<template>
    <aside
        id="aside"
        class="lg:py-2 lg:pl-2 w-60 fixed flex z-40 top-0 h-screen transition-position overflow-hidden"
    >
        <CardBoxModal
            v-model="logout.isModalActive"
            large-title="Please confirm"
            button="danger"
            @confirm="logout.submit"
            has-cancel
        >
            <p>Are you sure you want to <b>logout?</b></p>
        </CardBoxModal>
        <div
            :class="styleStore.asideStyle"
            class="lg:rounded-xl flex-1 flex flex-col overflow-hidden dark:bg-slate-900"
        >
            <div
                :class="styleStore.asideBrandStyle"
                class="flex flex-row h-14 items-center justify-between dark:bg-slate-900"
            >
                <div class="flex text-center lg:text-left lg:pl-6 xl:text-center xl:pl-6 text-lg">
                    <BaseIcon
                        :path="mdiContactlessPayment"
                        :size="22"
                        class="relative top-0.5"
                    />
                    <span class="grow font-bold text-ellipsis line-clamp-1">
              AladdinChat
          </span>
                </div>
                <button
                    class="lg:inline-block xl:hidden p-3"
                    @click.prevent="layoutStore.isAsideMobileExpanded = false"
                >
                    <BaseIcon
                        :path="mdiClose"
                    />
                </button>
            </div>
            <div
                :class="styleStore.darkMode ? 'aside-scrollbars-[slate]' : styleStore.asideScrollbarsStyle"
                class="flex-1 overflow-y-auto overflow-x-hidden"
            >
                <AsideMenuList
                    :menu="menu"
                    @menu-click="menuClick"
                />
            </div>

            <ul @click="logout.confirm">
                <AsideMenuItem
                    :item="logoutItem"
                />
            </ul>
        </div>
    </aside>
</template>
