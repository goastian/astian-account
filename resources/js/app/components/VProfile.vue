<template>
    <div class="text-center">
        <v-menu v-model="menu" :close-on-content-click="false" location="end">
            <template v-slot:activator="{ props }">
                <v-btn v-bind="props" icon="mdi-dots-vertical"></v-btn>
            </template>

            <v-card min-width="200">
                <v-list>
                    <v-list-item
                        :subtitle="$user.email"
                        :title="$user.name + ' ' + $user.last_name"
                    >
                    </v-list-item>
                </v-list>

                <v-divider></v-divider>

                <v-list>
                    <v-list-item @click="logout"> Logout </v-list-item>
                </v-list>
            </v-card>
        </v-menu>
    </div>
</template>
<script>
export default {
    data() {
        return {
            menu: false,
            fav: false,
        };
    },

    inject: ["$user"],

    methods: {
        async logout() {
            try {
                const res = await this.$server.post("logout");

                if (res.status == 200) {
                    window.location.href = "/";
                }
            } catch (error) {}
        },
    },
};
</script>
