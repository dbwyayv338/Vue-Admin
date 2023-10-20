<script setup>
import {Head, Link, useForm} from "@inertiajs/vue3"
import {
    mdiAccount,
    mdiAccountCircle,
    mdiLock,
    mdiMail,
    mdiPhone,
    mdiAsterisk,
    mdiFormTextboxPassword,
    mdiArrowLeftBoldOutline,
    mdiAlertBoxOutline,
} from "@mdi/js"
import SectionMain from "@/Components/Base/SectionMain.vue"
import CardBox from "@/Components/Base/CardBox.vue"
import BaseDivider from "@/Components/Base/BaseDivider.vue"
import FormField from "@/Components/Base/FormField.vue"
import FormControl from "@/Components/Base/FormControl.vue"
import BaseButton from "@/Components/Base/BaseButton.vue"
import BaseButtons from "@/Components/Base/BaseButtons.vue"
import NotificationBar from "@/Components/Base/NotificationBar.vue"
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue"
import SectionTitleLineWithButton from "@/Components/Base/SectionTitleLineWithButton.vue"
import Uploader from '@/Components/Admin/Uploader.vue'

const props = defineProps({
    user: {
        type: Object,
        default: () => ({}),
    },
})

const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    avatar: props.user.avatar,
    phone: props.user.phone,
})
const passwordForm = useForm({
    old_password: null,
    new_password: null,
    confirm_password: null,
})
</script>

<template>
    <LayoutAuthenticated>
        <Head title="Edit Account Info"/>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccount" title="Profile" main>
                <BaseButton
                    :route-name="route('admin.dashboard')"
                    :icon="mdiArrowLeftBoldOutline"
                    label="Back"
                    color="white"
                    rounded-full
                    small
                />
            </SectionTitleLineWithButton>
            <NotificationBar
                :key="Date.now()"
                v-if="$page.props.flash.message"
                color="success"
                :icon="mdiAlertBoxOutline"
            >
                {{ $page.props.flash.message }}
            </NotificationBar>
            <div class="lg:flex lg:gap-6">
                <div class="flex-1">
                    <CardBox
                        title="Edit Profile"
                        :icon="mdiAccountCircle"
                        form
                        @submit.prevent="profileForm.post(route('admin.account.info.store'))"
                    >
                        <FormField
                            label="Name"
                            help="Required. Your name"
                            :class="{ 'text-red-400': profileForm.errors.name }"
                        >
                            <FormControl
                                v-model="profileForm.name"
                                :icon="mdiAccount"
                                name="name"
                                required
                                :error="profileForm.errors.name"
                            >
                                <div class="text-red-400 text-sm" v-if="profileForm.errors.name">
                                    {{ profileForm.errors.name }}
                                </div>
                            </FormControl>
                        </FormField>
                        <FormField
                            label="Email"
                            help="Required. Your e-mail"
                            :class="{ 'text-red-400': profileForm.errors.email }"
                        >
                            <FormControl
                                v-model="profileForm.email"
                                :icon="mdiMail"
                                type="email"
                                name="email"
                                required
                                :error="profileForm.errors.email"
                            >
                                <div class="text-red-400 text-sm" v-if="profileForm.errors.email">
                                    {{ profileForm.errors.email }}
                                </div>
                            </FormControl>
                        </FormField>

                        <FormField
                            label="Phone"
                            help="Your phone number"
                        >
                            <FormControl
                                v-model="profileForm.phone"
                                :icon="mdiPhone"
                                name="phone" />

                        </FormField>

                        <BaseDivider/>

                        <FormField
                            label="Avatar"
                            help="Max: 500K"
                            :class="{ 'text-red-400': profileForm.errors.avatar }"
                        >
                            <Uploader v-model="profileForm.avatar" />
                        </FormField>

                        <template #footer>
                            <BaseButtons>
                                <BaseButton color="info" type="submit" label="Submit"/>
                            </BaseButtons>
                        </template>
                    </CardBox>
                </div>

                <div class="flex-1 lg:mt-0 mt-6">
                    <CardBox
                        title="Change Password"
                        :icon="mdiLock"
                        form
                        @submit.prevent="
            passwordForm.post(route('admin.account.password.store'), {
              preserveScroll: true,
              onSuccess: () => passwordForm.reset(),
            })
          "
                    >
                        <FormField
                            label="Current password"
                            help="Required. Your current password"
                            :class="{ 'text-red-400': passwordForm.errors.old_password }"
                        >
                            <FormControl
                                v-model="passwordForm.old_password"
                                :icon="mdiAsterisk"
                                name="old_password"
                                type="password"
                                required
                                :error="passwordForm.errors.old_password"
                            >
                                <div
                                    class="text-red-400 text-sm"
                                    v-if="passwordForm.errors.old_password"
                                >
                                    {{ passwordForm.errors.old_password }}
                                </div>
                            </FormControl>
                        </FormField>

                        <BaseDivider/>

                        <FormField
                            label="New password"
                            help="Required. New password"
                            :class="{ 'text-red-400': passwordForm.errors.new_password }"
                        >
                            <FormControl
                                v-model="passwordForm.new_password"
                                :icon="mdiFormTextboxPassword"
                                name="new_password"
                                type="password"
                                required
                                :error="passwordForm.errors.new_password"
                            >
                                <div
                                    class="text-red-400 text-sm"
                                    v-if="passwordForm.errors.new_password"
                                >
                                    {{ passwordForm.errors.new_password }}
                                </div>
                            </FormControl>
                        </FormField>

                        <FormField
                            label="Confirm password"
                            help="Required. New password one more time"
                            :class="{ 'text-red-400': passwordForm.errors.confirm_password }"
                        >
                            <FormControl
                                v-model="passwordForm.confirm_password"
                                :icon="mdiFormTextboxPassword"
                                name="confirm_password"
                                type="password"
                                required
                                :error="passwordForm.errors.confirm_password"
                            >
                                <div
                                    class="text-red-400 text-sm"
                                    v-if="passwordForm.errors.confirm_password"
                                >
                                    {{ passwordForm.errors.confirm_password }}
                                </div>
                            </FormControl>
                        </FormField>

                        <template #footer>
                            <BaseButtons>
                                <BaseButton type="submit" color="info" label="Submit"/>
                            </BaseButtons>
                        </template>
                    </CardBox>
                </div>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
