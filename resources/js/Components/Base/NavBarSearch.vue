<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const menus = computed(() => usePage().props.navigation.menu);
const keyword = ref('');
const search_result = ref([]);

const search = () => {
    const menu = menus.value;
    const searchMenu = (menu) => {
        for (const item of menu) {
            if (item.children) {
                searchMenu(item.children);
            } else {
                if (item.link.toLowerCase().includes(keyword.value.toLowerCase())) {
                    search_result.value.push(item);
                }
            }
        }
    };
    if (keyword.value.length > 0) {
        search_result.value = [];
        searchMenu(menu);
    } else {
        search_result.value = [];
    }
}

const enter = () => {
    if (search_result.value.length > 0) {
        window.location.href = search_result.value[0].link;
    }
    return false;
}

const clear = () => {
    setTimeout(() => {
        search_result.value = [];
        keyword.value = '';
    }, 200);
}

const inputEl = ref(null);

const fieldFocusHook = e => {
    if (e.ctrlKey && e.key === 'k') {
        e.preventDefault()
        inputEl.value.focus()
    } else if (e.key === 'Escape') {
        inputEl.value.blur()
    }
}

onMounted(() => {
    window.addEventListener('keydown', fieldFocusHook)
})
</script>

<template>
    <form @submit.prevent="enter">
        <div class="relative">
            <div
                v-show="search_result.length > 0"
                class="absolute w-screen lg:w-[400px] left-[-60px] lg:left-[0px] top-[50px] rounded-md
                   border-indigo-600
                   ring
                   ring-indigo-200
                   ring-opacity-50
                "
            >
                <van-cell-group>
                    <van-cell
                        v-for="item in search_result"
                        :title="$t(item.name)"
                        :value="item.link"
                        :url="item.link"
                        is-link/>
                </van-cell-group>
            </div>
            <input class="
                   rounded-md
                   shadow-sm
                   border-gray-100
                   bg-gray-100
                   focus:border-indigo-300
                   focus:ring
                   focus:ring-indigo-200
                   focus:ring-opacity-50
                   w-[220px]"
                   ref="inputEl"
                   v-model="keyword" @keyup="search" @blur="clear" type="text" placeholder="Quick Navigation (Ctrl+K)"/>
        </div>
    </form>
</template>
