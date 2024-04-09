<template>
    <v-modal
        :target="scope.scope.trim()"
        @is-clicked="loadData(scope)"
        @is-accepted="update(form)"
        @is-closed="clean"
    >
        <template v-slot:button> Details </template>
        <template v-slot:body>
            <div class="row">
                <div class="col">
                    <label for="scope">Scope Name</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="Role"
                        id="scope"
                        v-model="form.scope"
                    />
                    <v-error :error="errors.scope"></v-error>
                </div>
                <div class="col">
                    <label for="description">Description</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="description"
                        v-model="form.description"
                    />
                    <v-error :error="errors.description"></v-error>
                </div>
                <div class="col">
                    <label for="public"
                        >Make available for all Users (Public Scope)</label
                    >
                    <select
                        v-model="form.public"
                        name="public"
                        id="public"
                        class="form-control form-control-sm"
                    >
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <v-error :error="errors.public"></v-error>
                </div>
                <div class="col">
                    <label for="required_payment"
                        >Required payment</label
                    >
                    <select
                        v-model="form.required_payment"
                        name="required_payment"
                        id="required_payment"
                        class="form-control form-control-sm"
                    >
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <v-error :error="errors.required_payment"></v-error>
                </div>
            </div>
            <div v-show="message" class="my-3 py-2 bg-secondary text-light h6">
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
                    console.log(e);
                });
        },

        update(scope) {
            this.message = null
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
.col {
    flex: 0 0 auto;
    width: 100%;
    margin-bottom: 1%;

    @media (min-width: 240px) {
        width: 98%;
    }

    @media (min-width: 800px) {
        width: 30%;
    }
}

label {
    display: block !important;
    text-align: left;
}
</style>
