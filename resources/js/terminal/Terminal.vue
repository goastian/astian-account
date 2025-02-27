<template>
    <v-app v-if="user.id">
        <v-main>
            <div class="container mx-auto py-4">
                <div class="flex gap-1">
                    <input
                        type="text"
                        class="flex-grow px-2 py.2 border rounded-md"
                        placeholder="write a command"
                        v-model="form.command"
                        @keydown.enter="executeCommand"
                    />
                    <v-btn color="blue-darken-1" @click="executeCommand">
                        Execute
                    </v-btn>
                </div>
                <v-data-table
                    :items-per-page="search.per_page"
                    :headers="headers"
                    :items="commands"
                >
                    <template #item.command="{ item }">
                        <v-chip>
                            {{ item.command }}
                        </v-chip>
                    </template>
                    <template #item.status="{ item }">
                        <div class="flex justify-around my-2 flex-wrap gap-1">
                            <v-chip>
                                {{ item.status ? "Successfully" : "Error" }}
                            </v-chip>
                        </div>
                    </template>
                    <template #item.user="{ item }">
                        <v-chip>
                            {{ item.user.name }}
                        </v-chip>
                    </template>
                    <template #item.created="{ item }">
                        <v-chip>
                            {{ item.created }}
                        </v-chip>
                    </template>
                    <template v-slot:bottom>
                        <v-pagination
                            v-model="search.page"
                            :length="pages.total_pages"
                            :total-visible="7"
                        ></v-pagination>
                    </template>
                </v-data-table>
            </div>
        </v-main>
    </v-app>

    <div v-if="!user.id">
        <div
            id="loading-screen"
            class="fixed inset-0 flex items-center justify-center bg-white z-50"
        >
            <div class="text-center">
                <div
                    class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"
                ></div>
                <p class="text-lg font-medium text-gray-700 animate-pulse">
                    Loading ...
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, nextTick } from "vue";
export default {
    data() {
        return {
            app_name: "",
            drawer: true,
            user: {},
            nonce: "",
            commands: [],
            form: {},
            headers: [
                { title: "Command", value: "command", align: "start" },
                { title: "Status", value: "status", align: "center" },
                { title: "User", value: "user", align: "center" },
                { title: "Created", value: "created", align: "center" },
            ],
            pages: {},
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    watch: {
        "search.page"(value) {
            this.getCommands();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getCommands();
            }
        },
    },

    provide() {
        return {
            $user: computed(() => this.user),
        };
    },

    created() {
        this.authenticated();
    },

    mounted() {
        this.appData();
        this.getCommands();
    },

    methods: {
        async authenticated() {
            try {
                const res = await this.$server.get("/api/gateway/user");
                if (res.status === 200) {
                    this.user = res.data;
                }
            } catch (e) {}
        },

        async getCommands() {
            try {
                const res = await this.$server.get("/api/admin/terminals", {
                    params: {
                        order_by: "created",
                        order_type: "desc",
                    },
                });
                if (res.status === 200) {
                    this.commands = res.data.data;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (e) {}
        },

        async executeCommand() {
            try {
                const res = await this.$server.post(
                    "/api/admin/terminals",
                    this.form,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );
                if (res.status === 201) {
                    this.getCommands();
                    this.form.command = "";
                    this.getCommands();
                }
            } catch (e) {}
        },

        async appData() {
            await nextTick();
            const app = document.querySelector("#terminal");
            this.app_name = app.dataset.appName;
        },

        open(item) {
            this.$router.push({ name: item.route });
        },
    },
};
</script>

<style scoped lang="css">
.custom-drawer {
    width: 250px;
}
</style>
