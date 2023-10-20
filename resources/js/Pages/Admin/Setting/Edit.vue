<script setup>
import {Head, Link, useForm, router} from "@inertiajs/vue3"
import {
    mdiAirplaneSettings,
    mdiArrowLeftBoldOutline
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue"
import SectionMain from "@/Components/Base/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/Base/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/Base/CardBox.vue"
import FormField from '@/Components/Base/FormField.vue'
import FormControl from '@/Components/Base/FormControl.vue'
import BaseButton from '@/Components/Base/BaseButton.vue'
import BaseButtons from '@/Components/Base/BaseButtons.vue'
import {showNotify} from "vant";

const props = defineProps({
    fields: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({
    _method: 'POST',
})

const submit = () => {
    let data = {}
    props.fields.forEach(field => {
        data[field.name] = field.value
    })
    router.post(route('admin.setting.store'), data, {
        preserveScroll: true,
        onSuccess: () => {
            showNotify({
                type: 'success',
                message: 'Update site setting successfully',
            })
        },
    })
}
</script>

<template>
    <LayoutAuthenticated>
        <Head title="Site Setting"/>
        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiAirplaneSettings"
                title="Update Site Setting"
                main
            >
                <BaseButton
                    :route-name="route('admin.dashboard')"
                    :icon="mdiArrowLeftBoldOutline"
                    label="Back"
                    color="white"
                    rounded-full
                    small
                />
            </SectionTitleLineWithButton>
            <CardBox
                form
                @submit.prevent="submit"
            >
                <FormField
                    v-for="field in fields"
                    :label="field.name"
                    :help="field.desc"
                >
                    <FormControl
                        v-model="field.value"
                        type="text"
                        placeholder="please input"
                    />
                </FormField>
                <template #footer>
                    <BaseButtons>
                        <BaseButton
                            type="submit"
                            color="info"
                            label="Submit"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        />
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
