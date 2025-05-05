<template>
    <q-dialog v-model="dialog" persistent>
        <q-card class="w-100">
            <q-card-section>
                <div class="text-h6">Add new user</div>
            </q-card-section>

            <q-card-section>
                <div class="q-gutter-md">
                    <q-input
                        outlined
                        v-model="form.name"
                        dense="dense"
                        label="Name"
                        :error="!!errors.name"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>
                    <q-input
                        outlined
                        v-model="form.last_name"
                        dense="dense"
                        label="Last name"
                        :error="!!errors.last_name"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.last_name"></v-error>
                        </template>
                    </q-input>

                    <q-input
                        outlined
                        v-model="form.email"
                        dense="dense"
                        label="Email"
                        :error="!!errors.email"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.email"></v-error>
                        </template>
                    </q-input>

                    <q-select
                        v-model="form.country"
                        dense="dense"
                        emit-value
                        map-options
                        :options="
                            countries.map((c) => ({
                                label: `${c.emoji} ${c.name_en} `,
                                value: c.name_en,
                            }))
                        "
                        label="Country"
                        outlined
                        :error="!!errors.country"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.country"></v-error>
                        </template>
                    </q-select>

                    <q-select
                        v-model="form.dial_code"
                        dense="dense"
                        emit-value
                        map-options
                        :options="
                            countries.map((c) => ({
                                label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                                value: c.dial_code,
                            }))
                        "
                        label="Dial Code"
                        outlined
                        :error="!!errors.dial_code"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.dial_code"></v-error>
                        </template>
                    </q-select>

                    <q-input
                        outlined
                        v-model="form.phone"
                        dense="dense"
                        label="Phone"
                        :error="!!errors.phone"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.phone"></v-error>
                        </template>
                    </q-input>

                    <div class="w-full mb-2">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            Birthday
                        </label>
                        <VueDatePicker
                            v-model="form.birthday"
                            :enable-time-picker="false"
                            :max-date="new Date()"
                            format="yyyy-MM-dd"
                            model-type="format"
                            placeholder="YYYY-MM-DD"
                        />

                        <v-error :error="errors.birthday"></v-error>
                    </div>

                    <q-checkbox
                        dense="dense"
                        v-model="form.verify_email"
                        label="Mark user email as verified"
                        :error="!!form.groups"
                    >
                        <template v-slot:error>
                            <v-error :error="errors.groups"></v-error>
                        </template>
                    </q-checkbox>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    label="Save"
                    icon="mdi-content-save-alert"
                    color="primary"
                    @click="create"
                />
                <q-btn
                    label="Close"
                    icon="mdi-close-circle"
                    color="negative"
                    @click="dialog = false"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-btn color="positive" outline round icon="mdi-plus" @click="open">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Add new users
        </q-tooltip>
    </q-btn>
</template>

<script>
export default {
    emits: ["created"],
    data() {
        return {
            dialog: false,
            form: {
                name: null,
                last_name: null,
                email: null,
                country: null,
                dial_code: null,
                phone: null,
                birthday: null,
                groups: [],
                verify_email: false,
            },
            selected_groups: [],
            errors: {},
            countries: [],
            groups: [],
            formFields: {
                name: { label: "Name", type: "text" },
                last_name: { label: "Last Name", type: "text" },
                email: { label: "Email", type: "email" },
                phone: { label: "Phone Number", type: "text" },
            },
        };
    },

    watch: {
        selected_groups(value) {
            this.form.groups = value.map((item) => item.value);
        },
    },

    methods: {
        async open() {
            this.dialog = true;
            await this.getCountries();
            await this.getGroups();
        },

        close(dialog) {
            dialog.value = false;
            this.form = { groups: [] };
            this.countries = [];
        },
        async getGroups() {
            try {
                const res = await this.$server.get("/api/admin/groups", {
                    params: { per_page: 150 },
                });
                if (res.status === 200) this.groups = res.data.data;
            } catch (error) {}
        },
        async create() {
            try {
                const res = await this.$server.post(
                    "/api/admin/users",
                    this.form,
                    { headers: { "Content-Type": "multipart/form-data" } }
                );
                if (res.status === 201) {
                    this.form = { groups: [] };
                    this.errors = {};
                    this.selected_groups = [];

                    this.$q.notify({
                        type: "positive",
                        message: "A new user has been created successfully",
                        timeout: 3000,
                    });

                    this.$emit("created", true);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }

                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                    });
                }
            }
        },
        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: { order_by: "name_en", order_type: "asc" },
                });
                if (res.status === 200) this.countries = res.data;
            } catch (e) {}
        },
    },
};
</script>
