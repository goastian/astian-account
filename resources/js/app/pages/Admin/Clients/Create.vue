<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn
            outline
            color="primary"
            @click="open"
            icon="mdi-plus-circle-outline"
            label="Create client"
        />

        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
        >
            <q-card class="q-pa-md full-width">
                <q-card-section class="text-center">
                    <h6 class="text-gray-500">Add new client</h6>
                </q-card-section>
                <q-card-section>
                    <q-input
                        v-model="form.name"
                        label="Name"
                        dense
                        :error="!!errors.name"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>

                    <q-input
                        v-model="form.redirect"
                        label="Redirect"
                        dense
                        :error="!!errors.redirect"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.redirect"></v-error>
                        </template>
                    </q-input>

                    <q-checkbox
                        v-model="form.confidential"
                        label="Confidential client"
                        color="orange"
                        :error="!!errors.confidential"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.redirect"></v-error>
                        </template>
                    </q-checkbox>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn
                        dense
                        color="primary"
                        label="Accept"
                        @click="create"
                    />

                    <q-btn
                        dense
                        color="secondary"
                        label="Close"
                        @click="close"
                    />
                </q-card-actions>

                <q-card-section>
                    <div
                        v-if="client && Object.keys(client).length"
                        class="q-mt-md"
                    >
                        <div
                            class="text-negative text-bold text-center q-mb-sm"
                        >
                            These credentials are available only once. Please
                            store them safely.
                        </div>
                        <q-btn
                            label="Download credentials"
                            color="red"
                            icon="mdi-download"
                            unelevated
                            class="full-width"
                            @click="downloadJsonFile"
                        />
                    </div>
                </q-card-section>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            form: {},
            errors: {},
            client: {},
        };
    },

    methods: {
        close() {
            this.clean();
            this.dialog = false;
        },

        clean() {
            this.client = {};
            this.errors = {};
            this.form.name = null;
            this.form.redirect = null;
            this.form.confidential = false;
        },

        open() {
            this.clean();
            this.dialog = true;
        },

        downloadJsonFile() {
            const clientCopy = { ...this.client };
            delete clientCopy.links;
            delete clientCopy.revoked;
            delete clientCopy.provider;
            delete clientCopy.redirect;

            const filename = `${clientCopy.name || "client"}-credentials.json`;
            const jsonString = JSON.stringify(clientCopy, null, 2);
            const blob = new Blob([jsonString], { type: "application/json" });
            const url = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },

        async create() {
            try {
                const res = await this.$server.post(
                    this.$page.props.route["clients"],
                    this.form
                );

                if (res.status === 201) {
                    this.clean();
                    this.$emit("created", true);
                    this.client = res.data.data;
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
