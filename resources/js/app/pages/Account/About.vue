<template>
    <q-card class="q-mb-md">
        <q-img
            src="/img/docks.jpg"
            height="200px"
            class="text-white"
            fit="cover"
        >
            <div class="absolute-bottom text-grey-4 q-pa-md">
                {{ $user.name }} {{ $user.last_name }}
            </div>
        </q-img>

        <q-card-section class="q-pt-md">
            {{ $user.phone }}
        </q-card-section>

        <q-card-section>
            <div>{{ $user.name }}</div>
            <div>{{ $user.email }}</div>
        </q-card-section>

        <q-card-actions align="right">
            <q-btn color="orange" label="Share" />
            <q-btn color="orange" label="Explore" />
        </q-card-actions>
    </q-card>
</template>

<script>
export default {
    inject: ["$user"],
};
</script>
