<script setup>
import FormControl from '@/Components/Base/FormControl.vue'
import {computed, reactive, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const keyword = ref(null);

const menus = computed(() => usePage().props.navigation.menu);

const search = (k) => {
    const menu = menus.value;
    const result = [];
    const searchMenu = (menu) => {
        for (const item of menu) {
            if (item.children) {
                searchMenu(item.children);
            } else {
                if (item.link.toLowerCase().includes(k.toLowerCase())) {
                    result.push(item);
                }
            }
        }
    };
    searchMenu(menu);
    return result;
}

const enter = () => {
    const result = search(keyword.value);
    if (result.length) {
        window.location.href = result[0].link;
    }
    return false;
}
</script>

<template>
    <form @submit.prevent="enter">
        <FormControl
            placeholder="Search nav (Ctrl+K)"
            ctrl-k-focus
            transparent
            borderless
            v-model="keyword"
        />
    </form>
</template>
