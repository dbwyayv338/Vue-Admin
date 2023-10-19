<script setup>
import {Head, Link} from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome"/>
    <div class="min-h-screen pt-[200px] flex flex-col items-center bg-gray-100">
        <div class="w-full sm:max-w-md text-center py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div>
                <div v-if="canLogin">
                    <Link v-if="$page.props.auth.user" :href="route('admin.dashboard')">
                        {{$t('dashboard')}}
                    </Link>
                    <div v-else>
                        <Link :href="route('login')">{{$t('login')}}</Link>
                        <Link class="ml-6" v-if="canRegister" :href="route('register')">{{$t('register')}}</Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>

</style>
