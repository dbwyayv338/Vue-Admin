<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
  mdiMenu,
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

const form = useForm({
  name: '',
  machine_name: '',
  description: ''

})
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Create Category Type" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiMenu"
        title="Add Type"
        main
      >
        <BaseButton
          :route-name="route('category.index')"
          :icon="mdiArrowLeftBoldOutline"
          label="Back"
          color="white"
          rounded-full
          small
        />
      </SectionTitleLineWithButton>
      <CardBox
        form
        @submit.prevent="form.post(route('category.store'))"
      >
        <FormField
          label="Name"
          :class="{ 'text-red-400': form.errors.name }"
        >
          <FormControl
            v-model="form.name"
            type="text"
            placeholder="Enter Name"
            :error="form.errors.name"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.name">
              {{ form.errors.name }}
            </div>
          </FormControl>
        </FormField>
        <FormField
          label="Machine Name"
          :class="{ 'text-red-400': form.errors.machine_name }"
        >
          <FormControl
            v-model="form.machine_name"
            type="text"
            placeholder="Enter Machine Name"
            :error="form.errors.name"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.machine_name">
              {{ form.errors.machine_name }}
            </div>
          </FormControl>
        </FormField>
        <FormField
          label="Description"
          :class="{ 'text-red-400': form.errors.description }"
        >
          <FormControl
            v-model="form.description"
            type="text"
            placeholder="Enter Description"
            :error="form.errors.description"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.description">
              {{ form.errors.description }}
            </div>
          </FormControl>
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
