<template>
    <v-card class="m-2">
        <v-img
            class="align-end text-white"
            height="200"
            src="/img/docks.jpg"
            cover
        >
            <v-card-title class="text-gray-500">
                {{ $user.name }} {{ $user.last_name }}</v-card-title
            >
        </v-img>

        <v-card-subtitle class="pt-4"> {{ $user.phone }}</v-card-subtitle>

        <v-card-text>
            <div>{{ $user.name }}</div>

            <div>{{ $user.email }}</div>
        </v-card-text>

        <v-card-actions>
            <v-btn color="orange" text="Share"></v-btn>

            <v-btn color="orange" text="Explore"></v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    data() {
        return {
            roles: [],
        };
    },

    inject: ["$user"],

    created() {
        this.scopes();
    },
    methods: {
        async scopes() {
            try {
                const res = await this.$server.get("/api/oauth/scopes");
                if (res.status === 200) {
                    this.roles = res.data;
                }
            } catch (e) {
                console.error("Error obteniendo los roles", e);
            }
        },
    },
};
</script>
