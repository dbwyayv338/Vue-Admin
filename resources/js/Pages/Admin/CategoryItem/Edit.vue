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
import FormCheckRadioGroup from '@/Components/Base/FormCheckRadioGroup.vue'
import BaseButton from '@/Components/Base/BaseButton.vue'
import BaseButtons from '@/Components/Base/BaseButtons.vue'

const props = defineProps({
  category: {
    type: Object,
    default: () => ({}),
  },
  item: {
    type: Object,
    default: () => ({}),
  },
  item_options: {
    type: Object,
    default: () => ({}),
  },
})


const form = useForm({
  _method: 'put',
  name: props.item.name,
  slug: props.item.slug,
  description: props.item.description,
  enabled: props.item.enabled,
  parent_id: props.item.parent_id,
  weight: props.item.weight
})
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Update category item" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiMenu"
        title="Update category item"
        main
      >
        <BaseButton
          :route-name="route('category.item.index', category.id)"
          :icon="mdiArrowLeftBoldOutline"
          label="Back"
          color="white"
          rounded-full
          small
        />
      </SectionTitleLineWithButton>
      <CardBox
        form
        @submit.prevent="form.post(route('category.item.update', {category: props.category.id, item:props.item.id}))"
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
          label="Slug"
          :class="{ 'text-red-400': form.errors.slug }"
        >
          <FormControl
            v-model="form.slug"
            type="text"
            placeholder="Enter Slug"
            :error="form.errors.slug"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.slug">
              {{ form.errors.slug }}
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
        <FormCheckRadioGroup
          v-model="form.enabled"
          name="enabled"
          :options="{ enabled: 'Enabled' }"
        />
        <FormField
          label="Parent Item"
          :class="{ 'text-red-400': form.errors.parent_id }"
        >
          <FormControl
            v-model="form.parent_id"
            type="select"
            placeholder="--ROOT--"
            :error="form.errors.parent_id"
            :options="item_options"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.parent_id">
              {{ form.errors.parent_id }}
            </div>
            <div>
                The maximum depth for a link and all its children is fixed. Some menu links may not be available as parents if selecting them would exceed this limit.
            </div>
          </FormControl>
        </FormField>
        <FormField
          label="Weight"
          :class="{ 'text-red-400': form.errors.weight }"
        >
          <FormControl
            v-model="form.weight"
            type="text"
            placeholder="Enter Weight"
            :error="form.errors.weight"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.weight">
              {{ form.errors.weight }}
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
