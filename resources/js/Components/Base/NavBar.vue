<script setup>
import {usePage, router} from '@inertiajs/vue3'
import {computed, ref} from 'vue'
import {containerMaxW, darkModeKey, styleKey} from '@/config'
import {useMainStore} from '@/Stores/main.js'
import {useStyleStore} from '@/Stores/style.js'
import {useLayoutStore} from '@/Stores/layout.js'
import {
    mdiForwardburger,
    mdiBackburger,
    mdiClose,
    mdiDotsVertical,
    mdiMenu,
    mdiClockOutline,
    mdiCloud,
    mdiPaletteOutline,
    mdiAccount,
    mdiCogOutline,
    mdiEmail,
    mdiLogout,
    mdiThemeLightDark
} from '@mdi/js'
import NavBarItem from '@/Components/Base/NavBarItem.vue'
import NavBarItemLabel from '@/Components/Base/NavBarItemLabel.vue'
import NavBarMenu from '@/Components/Base/NavBarMenu.vue'
import BaseDivider from '@/Components/Base/BaseDivider.vue'
import UserAvatarCurrentUser from '@/Components/Base/UserAvatarCurrentUser.vue'
import BaseIcon from '@/Components/Base/BaseIcon.vue'
import NavBarSearch from '@/Components/Base/NavBarSearch.vue'

const mainStore = useMainStore()

const userName = computed(() => usePage().props.auth.user.name)

const styleStore = useStyleStore()

const enableStyles = computed(() => styleStore.getStyles())

const isDarkMode = ref(false)

const toggleLightDark = () => {
    isDarkMode.value = !isDarkMode.value
    if(isDarkMode.value) {
        styleStore.setStyle('gray', false)
    } else {
        styleStore.setStyle(localStorage[styleKey], false)
    }
    styleStore.setDarkMode()
}

const layoutStore = useLayoutStore()

const isMenuNavBarActive = ref(false)

const isStylePopupActive = ref(false)

const menuNavBarToggle = () => {
    isMenuNavBarActive.value = !isMenuNavBarActive.value
}

const selectStyle = (style) => {
    isStylePopupActive.value = false
    localStorage[styleKey] = style
    styleStore.setStyle(style)
}

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <nav
        class="top-0 left-0 right-0 fixed bg-gray-100 h-14 z-30 w-screen transition-position xl:pl-60 lg:w-auto dark:bg-slate-800"
    >
        <div
            class="flex lg:items-stretch"
            :class="containerMaxW"
        >
            <div class="flex-1 items-stretch flex h-14">
                <NavBarItem
                    type="flex lg:hidden"
                    @click.prevent="layoutStore.asideMobileToggle()"
                >
                    <BaseIcon
                        :path="layoutStore.isAsideMobileExpanded ? mdiBackburger : mdiForwardburger"
                        size="24"
                    />
                </NavBarItem>
                <NavBarItem>
                    <NavBarSearch/>
                </NavBarItem>
            </div>
            <div class="flex-none items-stretch flex h-14 lg:hidden">
                <NavBarItem @click.prevent="menuNavBarToggle">
                    <BaseIcon
                        :path="isMenuNavBarActive ? mdiClose : mdiDotsVertical"
                        size="24"
                    />
                </NavBarItem>
            </div>
            <div
                class="absolute w-screen top-14 left-0 bg-gray-100 shadow
          lg:w-auto lg:items-stretch lg:flex lg:grow lg:static lg:border-b-0 lg:overflow-visible lg:shadow-none dark:bg-slate-800"
                :class="[isMenuNavBarActive ? 'block' : 'hidden']"
            >
                <div
                    class="max-h-screen-menu overflow-y-auto lg:overflow-visible lg:flex lg:items-stretch lg:justify-end lg:ml-auto"
                >
                    <NavBarMenu>
                        <NavBarItemLabel :label="userName">
                            <UserAvatarCurrentUser class="w-6 h-6 mr-3 inline-flex"/>
                        </NavBarItemLabel>

                        <template #dropdown>
                            <NavBarItem route-name="admin.account.info">
                                <NavBarItemLabel
                                    :icon="mdiAccount"
                                    label="My Profile"
                                />
                            </NavBarItem>
                            <NavBarItem route-name="admin.account.info">
                                <NavBarItemLabel
                                    :icon="mdiCloud"
                                    label="Change Password"
                                />
                            </NavBarItem>
                        </template>
                    </NavBarMenu>
                    <NavBarItem
                        is-desktop-icon-only
                        @click.prevent="toggleLightDark"
                    >
                        <NavBarItemLabel
                            :icon="mdiThemeLightDark"
                            label="Light/Dark"
                            is-desktop-icon-only
                        />
                    </NavBarItem>
                    <NavBarItem
                        is-desktop-icon-only
                        @click.prevent="isStylePopupActive = true"
                    >
                        <NavBarItemLabel
                            :icon="mdiPaletteOutline"
                            label="Switch Style"
                            is-desktop-icon-only
                        />
                    </NavBarItem>
                    <NavBarItem
                        is-desktop-icon-only
                        @click="logout"
                    >
                        <NavBarItemLabel
                            :icon="mdiLogout"
                            label="Log out"
                            is-desktop-icon-only
                        />
                    </NavBarItem>
                </div>
            </div>
        </div>
        <van-popup v-model:show="isStylePopupActive" position="right">
            <div class="w-[140px] h-screen p-3 text-center">
                <div class="w-full text-center text-bold py-2">Select Style</div>
                <div class="m-auto w-[80%]">
                    <div
                        v-for="(style, index) in enableStyles"
                        :class="'my-5 py-8 text-white cursor-pointer border-double border-4 border-white bg-'+style+'-900'"
                        @click="selectStyle(style)"
                    >
                        {{ style }}
                    </div>
                </div>
            </div>
        </van-popup>
    </nav>
</template>
