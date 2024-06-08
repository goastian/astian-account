<template>
    <v-modal @is-accepted="update(user)">
        <template v-slot:button> Details </template>
        <template v-slot:head>
            <p class="label fw-bold">
                ABOUT
                {{ user.name }} {{ user.last_name }}
            </p>
        </template>

        <template v-slot:body>
            <div class="user-update">
                <div class="item">
                    <label>First Name</label>

                    <input
                        @keypress.enter="update(user)"
                        v-model="user.name"
                        type="text"
                        class="input"
                    />

                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="item">
                    <label>Last Name</label>

                    <input
                        @keypress.enter="update(user)"
                        v-model="user.last_name"
                        type="text"
                        class="input"
                    />

                    <v-error :error="errors.last_name"></v-error>
                </div>
                <div class="item">
                    <label>Email Address</label>

                    <input
                        @keypress.enter="update(user)"
                        v-model="user.email"
                        type="email"
                        class="input"
                    />

                    <v-error :error="errors.email"></v-error>
                </div>

                <div class="item">
                    <label class="label">Phone Number</label>
                    <div class="group">
                        <v-select-search
                            class="label"
                            :items="countries"
                            param="name_en"
                            :text="user.dial_code"
                            @selected="setDialCode"
                        >
                            <template #title="slotProps">
                                {{
                                    slotProps.item.name_en
                                        ? slotProps.item.emoji +
                                          " " +
                                          slotProps.item.name_en +
                                          " " +
                                          slotProps.item.dial_code
                                        : slotProps.text
                                }}
                            </template>

                            <template #options="slotProps">
                                <span class="">
                                    {{ slotProps.items.emoji }}
                                    {{ slotProps.items.name_en }}
                                    {{ slotProps.items.dial_code }}
                                </span>
                            </template>
                        </v-select-search>

                        <input
                            @keypress.enter="update(user)"
                            v-model="user.phone"
                            type="text"
                        />
                    </div>
                    <v-error :error="errors.phone"></v-error>
                </div>

                <div class="item">
                    <label for="">Country</label>
                    <div class="group">
                        <v-select-search
                            class="label"
                            :items="countries"
                            param="name_en"
                            :text="user.country"
                            @selected="setContry"
                        >
                            <template #title="slotProps">
                                {{
                                    slotProps.item.name_en
                                        ? slotProps.item.emoji +
                                          " " +
                                          slotProps.item.name_en
                                        : slotProps.text
                                }}
                            </template>

                            <template #options="slotProps">
                                <span class="">
                                    {{ slotProps.items.emoji }}
                                    {{ slotProps.items.name_en }}
                                </span>
                            </template>
                        </v-select-search>

                        <input
                            @keypress.enter="update(user)"
                            v-model="user.country"
                            type="text"
                        />
                    </div>

                    <v-error :error="errors.country"></v-error>
                </div>
                <div class="item">
                    <label class="label">City Or State</label>

                    <input
                        @keypress.enter="update(user)"
                        v-model="user.city"
                        type="text"
                        class="input"
                    />

                    <v-error :error="errors.city"></v-error>
                </div>
                <div class="item">
                    <label>Home Address</label>

                    <input
                        @keypress.enter="update(user)"
                        v-model="user.address"
                        type="text"
                        class="input"
                    />

                    <v-error :error="errors.address"></v-error>
                </div>
                <div class="item">
                    <label>Date of birth</label>

                    <input
                        @keypress.enter="update(user)"
                        v-model="user.birthday"
                        type="date"
                        class="input"
                    />

                    <v-error :error="errors.birthday"></v-error>
                </div>
                <div class="item">
                    <label class="label fw-bold">Join us</label>
                    <span>{{ user.created }}</span>
                </div>
                <div class="item">
                    <label class="label fw-bold">Last Update</label>
                    <span>{{ user.updated }}</span>
                </div>
            </div>
            <div class="item">
                <label class="label fw-bold">Status</label>

                <span class="label">
                    {{
                        user.disabled
                            ? `User inactive since ${user.disabled}`
                            : "Active User"
                    }}
                </span>
            </div>

            <div :class="{ status: status, hide: !status }">
                <p>{{ status }}</p>
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success"],

    props: ["user"],

    data() {
        return {
            status: false,
            errors: {},
            countries: {},
        };
    },

    mounted() {
        this.getCountries();
    },

    methods: {
        setContry(event) {
            this.user.country = event.name_en;
        },

        setDialCode(event) {
            this.user.dial_code = event.dial_code;
        },

        /**
         * Actualiza a un usuario
         * @param {*} item
         */
        update(item) {
            this.status = null;
            this.$server
                .put(item.links.update, this.user)
                .then((res) => {
                    this.status = "User updated";
                    this.$emit("success", res.data.data);
                    this.errors = {};
                })
                .catch((e) => {
                    if (
                        e.response &&
                        e.response.data.errors &&
                        e.response.status == 422
                    ) {
                        this.errors = e.response.data.errors;
                    }
                    if (e.response && e.response.status == 403) {
                        this.status =
                            "Without authorization to perform this action";
                    }
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
        },

        getCountries() {
            this.$server
                .get("/api/locations/countries")
                .then((res) => {
                    this.countries = res.data;
                })
                .catch((err) => {});
        },
    },
};
</script>
<style lang="scss" scoped>
.user-update {
    display: flex;
    flex-wrap: wrap;
    color: var(--first-color) !important;

    .item {
        flex: 1 1 calc(100% / 2);
    }
}

.status {
    padding: 1em;
    text-align: center;
    border-top: 1px solid var(--border-color-light);

    p {
        font-size: 1em;
        color: var(--first-color);
    }
}

.hide {
    display: none;
}
</style>
