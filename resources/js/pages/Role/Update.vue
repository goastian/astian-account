<template>
    <v-modal @is-clicked="loadData(scope)">
        <template v-slot:button> Details </template>
        <template v-slot:head>
            Update scope (<strong v-text="scope.scope"></strong>)
        </template>
        <template v-slot:body>
            <div class="box">
                <div class="item">
                    <label class="label fw-bold" for="scope">Scope Name</label>
                    <input
                        type="text"
                        class="input"
                        placeholder="Role"
                        id="scope"
                        v-model="form.scope"
                    />
                    <v-error :error="errors.scope"></v-error>
                </div>
                <div class="item">
                    <label class="label fw-bold" for="description"
                        >Description</label
                    >
                    <textarea
                        class="textarea"
                        v-model="form.description"
                    ></textarea>

                    <v-error :error="errors.description"></v-error>
                </div>
                <div class="item">
                    <label class="label fw-bold text-md" for="public"
                        >Make available for all Users (Public Scope)</label
                    >
                    <select
                        v-model="form.public"
                        name="public"
                        id="public"
                        class="select"
                    >
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                    <v-error :error="errors.public"></v-error>
                </div>
                <div class="item">
                    <label class="label fw-bold text-md" for="required_payment"
                        >Required payment</label
                    >
                    <select
                        v-model="form.required_payment"
                        name="required_payment"
                        id="required_payment"
                        class="select"
                    >
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                    <v-error :error="errors.required_payment"></v-error>
                </div>
            </div>
            <div v-show="message">
                {{ message }}
            </div>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success"],

    props: ["scope"],

    data() {
        return {
            errors: {},
            message: null,
            form: {
                scope: null,
                description: null,
                public: 0,
            },
        };
    },

    methods: {
        clean() {
            this.errors = {};
            this.form = {};
            this.message = null;
        },

        loadData(scope) {
            this.$server
                .get(scope.links.show)
                .then((res) => {
                    this.form = res.data.data;
                })
                .catch((e) => {
                    if (e.response && e.response.status == 401) {
                        window.location.href = "/login";
                    }
                });
        },

        update(scope) {
            this.message = null;
            this.$server
                .put(scope.links.update, this.form)
                .then((res) => {
                    this.message = "Successful update.";
                    this.errors = {};
                    this.$emit("success", res.data.data);
                })
                .catch((e) => {
                    if (
                        e.response &&
                        e.response.status != 403 &&
                        e.response.data.errors
                    ) {
                        this.errors = e.response.data.errors;
                    }

                    if (e.response.status == 403 && e.response.data.message) {
                        this.message = e.response.data.message;
                    }
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.box {
    display: flex;
    flex-wrap: wrap;

    .item {
        flex: 1 1 100%;

        @media (min-width: 800px) {
            flex: 1 1 calc(100% / 2);
        }
        margin-bottom: 1%;
    }
}
</style>
