<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import UserAvatar from '@/Components/Base/UserAvatar.vue'

const user = computed(() => usePage().props.auth.user)
</script>

<template>
  <UserAvatar
    :username="user.name"
    :avatar="user.avatar"
    api="initials"
  />
</template>
