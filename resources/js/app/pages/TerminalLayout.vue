<template>
    <q-layout view="hHh lpR fFf">
        <q-header elevated>
            <q-toolbar>
                <q-btn dense flat round icon="mdi-home-account" @click="home" />
                <q-toolbar-title>Command Terminal</q-toolbar-title>
            </q-toolbar>
        </q-header>

        <q-page-container>
            <q-page class="q-pa-md">
                <div class="row q-gutter-md">
                    <q-input
                        v-model="form.command"
                        label="Write a command"
                        @keyup.enter="executeCommand"
                        outlined
                        dense
                        class="col-grow"
                    />
                    <q-btn
                        color="primary"
                        label="Execute"
                        @click="executeCommand"
                    />
                </div>

                <q-banner v-if="errors.command" class="q-mt-md text-negative">
                    {{ errors.command }}
                </q-banner>

                <q-table
                    flat
                    bordered
                    :rows="commands"
                    :columns="headers"
                    hide-bottom
                    hide-pagination
                    :rows-per-page-options="[search.per_page]"
                >
                    <template v-slot:body-cell-command="props">
                        <q-td>
                            <q-chip>{{ props.row.command }}</q-chip>
                        </q-td>
                    </template>
                    <template v-slot:body-cell-status="props">
                        <q-td>
                            <q-chip :color="props.row.status ? 'green' : 'red'">
                                {{
                                    props.row.status ? "Successfully" : "Error"
                                }}
                            </q-chip>
                        </q-td>
                    </template>
                    <template v-slot:body-cell-user="props">
                        <q-td>
                            <q-chip>{{ props.row.user.name }}</q-chip>
                        </q-td>
                    </template>
                    <template v-slot:body-cell-created="props">
                        <q-td>
                            <q-chip>{{ props.row.created }}</q-chip>
                        </q-td>
                    </template>
                    <template v-slot:body-cell-actions="props">
                        <q-td>
                            <q-btn
                                flat
                                round
                                icon="visibility"
                                @click="showOutput(props.row)"
                            />
                        </q-td>
                    </template>
                </q-table>

                <div class="row justify-center q-mt-md">
                    <q-pagination
                        v-model="search.page"
                        color="grey-8"
                        :max="pages.total_pages"
                        size="sm"
                    />
                </div>

                <q-dialog
                    v-model="outputDialog"
                    transition-show="slide-up"
                    transition-hide="slide-down"
                >
                    <q-card>
                        <q-card-section>
                            <div class="text-h6">Output details</div>
                        </q-card-section>
                        <q-card-section>
                            <div
                                v-for="(line, index) in selectedOutput"
                                :key="index"
                            >
                                {{ line }}
                            </div>
                        </q-card-section>
                        <q-card-actions align="right">
                            <q-btn flat label="Close" v-close-popup />
                        </q-card-actions>
                    </q-card>
                </q-dialog>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            commands: [],
            form: {},
            errors: {},
            search: { page: 1, rowsPerPage: 15 },
            headers: [
                {
                    name: "command",
                    label: "Command",
                    field: "command",
                    align: "left",
                },
                {
                    name: "status",
                    label: "Status",
                    field: "status",
                    align: "left",
                },
                {
                    name: "user",
                    label: "User",
                    field: "user",
                    align: "left",
                },
                {
                    name: "created",
                    label: "Created",
                    field: "created",
                    align: "left",
                },
                { name: "actions", label: "Actions", align: "left" },
            ],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
                order_by: "created",
                order_type: "desc",
            },
            outputDialog: false,
            selectedOutput: [],
        };
    },

    created() {
        const values = this.$page.props.commands;
        this.commands = values.data;
        this.pages = values.meta.pagination;
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

    methods: {
        home() {
            this.$router.push({ name: "about" });
        },

        async getCommands() {
            try {
                const res = await this.$server.get("/api/admin/terminals", {
                    params: this.search,
                });
                if (res.status === 200) {
                    this.commands = res.data.data;
                    const meta = res.data.meta.pagination;
                    this.pages = meta;
                    this.search.current_page = meta.current_page;
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
                    this.$q.notify({
                        type: "positive",
                        message: "Command executed successfully",
                    });
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
                if (e.response && e.response.data && e.response.data.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                    });
                }
            }
        },
        showOutput(row) {
            this.selectedOutput = row.output;
            this.outputDialog = true;
        },
    },
};
</script>
