<script setup>
import {Link, useForm, usePage} from "@inertiajs/vue3"
import BaseButton from "@/Components/BaseButton.vue"
import CardBoxModal from "@/Components/CardBoxModal.vue"
import { mdiSignLanguage } from "@mdi/js"
import {computed, reactive} from "vue";

const lang = computed(() => usePage().props.auth.user.lang)

const language = reactive({
    current: lang,
    isModalActive: false,
    list: [
        {
            label: 'English',
            value: 'en'
        },
        {
            label: '中文',
            value: 'zh'
        }
    ],
    change: () => {
        language.isModalActive = false
        form.post(route('admin.account.language.store'))
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
                    :key="item.value"
                    :value="item.value"
                >
                    {{ item.label }}
                </option>
            </select>
        </p>
    </CardBoxModal>
</template>
