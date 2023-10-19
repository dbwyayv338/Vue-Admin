<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
  mdiLink,
  mdiPlus,
  mdiSquareEditOutline,
  mdiTrashCan,
  mdiAlertBoxOutline,
  mdiArrowLeftBoldOutline
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue"
import CardBoxModal from '@/Components/Base/CardBoxModal.vue'
import SectionMain from "@/Components/Base/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/Base/SectionTitleLineWithButton.vue"
import BaseButton from "@/Components/Base/BaseButton.vue"
import CardBox from "@/Components/Base/CardBox.vue"
import BaseButtons from "@/Components/Base/BaseButtons.vue"
import NotificationBar from "@/Components/Base/NotificationBar.vue"
import CategoryItemList from "@/Components/Admin/CategoryItemList.vue";
import {reactive} from "vue";

const props = defineProps({
  items: {
    type: Object,
    default: () => ({}),
  },
  category: {
    type: Object,
    default: () => ({}),
  },
  can: {
    type: Object,
    default: () => ({}),
  },
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
        formDelete.delete(route("category.item.destroy", {category: props.category.id, item: destroy.id}))
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
    <Head title="Category Items" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiLink"
        title="Category Items"
        main
      >
        <BaseButtons type="justify-start lg:justify-end" no-wrap>
          <BaseButton
            :route-name="route('category.index')"
            :icon="mdiArrowLeftBoldOutline"
            label="Back"
            color="white"
            rounded-full
            small
          />
          <BaseButton
            v-if="can.delete"
            :route-name="route('category.item.create', category.id)"
            :icon="mdiPlus"
            label="Add"
            color="info"
            rounded-full
            small
          />
        </BaseButtons>
      </SectionTitleLineWithButton>
      <NotificationBar
        :key="Date.now()"
        v-if="$page.props.flash.message"
        color="success"
        :icon="mdiAlertBoxOutline"
      >
        {{ $page.props.flash.message }}
      </NotificationBar>
      <CardBox class="mb-5" has-table>
          <table class="border-collapse w-full border border-slate-400 dark:border-slate-500 bg-white text-sm shadow-sm">
              <tbody>
              <tr>
                  <td
                      class="
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                "
                  >
                      Name
                  </td>
                  <td
                      class="w-2/3"
                      data-label="Name">
                      {{ category.name }}
                  </td>
              </tr>
              <tr>
                  <td
                      class="
                  text-slate-500
                  dark:text-slate-400
                  hidden
                  lg:block
                "
                  >
                      Machine name
                  </td>
                  <td
                      class="w-2/3"
                      data-label="Machine Name">
                      {{ category.machine_name }}
                  </td>
              </tr>
              </tbody>
          </table>
      </CardBox>
      <CardBox class="mb-6" has-table>
        <table>
          <thead>
            <tr>
              <th>
                Name
              </th>
              <th>
                Slug
              </th>
              <th>
                Enable
              </th>
              <th v-if="can.edit || can.delete">Actions</th>
            </tr>
          </thead>

          <tbody>
            <template v-for="item in items">
              <CategoryItemList :item="item" :category="category" :can="can" :level=0 @delete="destroy.confirm" />
            </template>
          </tbody>
        </table>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
