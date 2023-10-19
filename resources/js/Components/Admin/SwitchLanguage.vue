<script setup>
import {Link, useForm, usePage} from "@inertiajs/vue3"
import BaseButton from "@/Components/Base/BaseButton.vue"
import CardBoxModal from "@/Components/Base/CardBoxModal.vue"
import { mdiSignLanguage } from "@mdi/js"
import {computed, reactive} from "vue";
import {currentLanguage} from "@/config";

const userLang = computed(() => usePage().props.auth.user.lang)
let locales = computed(() => usePage().props.locale.available)

const language = reactive({
    current: userLang,
    isModalActive: false,
    list: locales,
    change: () => {
        language.isModalActive = false
        localStorage.setItem(currentLanguage, form.lang)
        form.post(route('admin.account.language.store'))
        location.reload()
    }
})

const form = useForm({
    lang: language.current,
})
</script>

<template>
    <BaseButton
        @click="language.isModalActive = true"
        target="_blank"
        :icon="mdiSignLanguage"
        label="Switch Language"
        color="contrast"
        rounded-full
        small
    />
    <CardBoxModal
        @confirm="language.change"
        v-model="language.isModalActive"
        title="Switch Language"
    >
        <p>Please select the <b>language</b> you need.</p>
        <p>Current language is {{ language.current }}.</p>
        <p>
            <select
                v-model="form.lang"
                class="form-select mt-1 block w-full"
            >
                <option
                    v-for="item in language.list"
                    :key="item.regional"
                    :value="item.regional"
                >
                    {{ item.native }}
                </option>
            </select>
        </p>
    </CardBoxModal>
</template>
