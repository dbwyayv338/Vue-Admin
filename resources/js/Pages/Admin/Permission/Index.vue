<script setup>
import {Head, Link, useForm} from "@inertiajs/vue3"
import {
    mdiAccountKey,
    mdiPlus,
    mdiSquareEditOutline,
    mdiTrashCan,
    mdiAlertBoxOutline,
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue"
import CardBoxModal from '@/Components/Base/CardBoxModal.vue'
import SectionMain from "@/Components/Base/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/Base/SectionTitleLineWithButton.vue"
import BaseButton from "@/Components/Base/BaseButton.vue"
import CardBox from "@/Components/Base/CardBox.vue"
import BaseButtons from "@/Components/Base/BaseButtons.vue"
import NotificationBar from "@/Components/Base/NotificationBar.vue"
import Pagination from "@/Components/Admin/Pagination.vue"
import Sort from "@/Components/Admin/Sort.vue"
import {reactive, ref} from "vue";

const props = defineProps({
    permissions: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    can: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({
    search: props.filters.search,
})

const formDelete = useForm({})

const destroy = reactive({
    id: 0,
    isModalActive: false,
    confirm: (id) => {
        destroy.isModalActive = true
        destroy.id = id
    },
    submit: () => {
        formDelete.delete(route("permission.destroy", destroy.id))
    }
})

</script>

<template>
    <CardBoxModal
        v-model="destroy.isModalActive"
        large-title="Please confirm"
        button="danger"
        @confirm="destroy.submit"
        has-cancel
    >
        <p>Are you sure you want to <b>delete?</b></p>
    </CardBoxModal>
    <LayoutAuthenticated>
        <Head title="Permissions"/>
        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiAccountKey"
                title="Permissions"
                main
            >
                <BaseButton
                    v-if="can.delete"
                    :route-name="route('permission.create')"
                    :icon="mdiPlus"
                    label="Add"
                    color="info"
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
            <CardBox class="mb-6" has-table>
                <form @submit.prevent="form.get(route('permission.index'))">
                    <div class="py-2 flex">
                        <div class="flex pl-4">
                            <input
                                type="search"
                                v-model="form.search"
                                class="
                  rounded-md
                  shadow-sm
                  border-gray-300
                  focus:border-indigo-300
                  focus:ring
                  focus:ring-indigo-200
                  focus:ring-opacity-50
                "
                                placeholder="Search"
                            />
                            <BaseButton
                                label="Search"
                                type="submit"
                                color="info"
                                class="ml-4 inline-flex items-center px-4 py-2"
                            />
                        </div>
                    </div>
                </form>
            </CardBox>
            <CardBox class="mb-6" has-table>
                <table>
                    <thead>
                    <tr>
                        <th>
                            <Sort label="Name" attribute="name"/>
                        </th>
                        <th v-if="can.edit || can.delete">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="permission in permissions.data" :key="permission.id">
                        <td data-label="Name">
                            <Link
                                :href="route('permission.show', permission.id)"
                                class="
                    no-underline
                    hover:underline
                    text-cyan-600
                    dark:text-cyan-400
                  "
                            >
                                {{ permission.name }}
                            </Link>
                        </td>
                        <td
                            v-if="can.edit || can.delete"
                            class="before:hidden lg:w-1 whitespace-nowrap"
                        >
                            <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                <BaseButton
                                    v-if="can.edit"
                                    :route-name="route('permission.edit', permission.id)"
                                    color="info"
                                    :icon="mdiSquareEditOutline"
                                    small
                                />
                                <BaseButton
                                    v-if="can.delete"
                                    color="danger"
                                    :icon="mdiTrashCan"
                                    small
                                    @click="destroy.confirm(permission.id)"
                                />
                            </BaseButtons>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="py-4">
                    <Pagination :data="permissions"/>
                </div>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
