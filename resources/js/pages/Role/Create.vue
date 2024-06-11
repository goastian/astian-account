<template>
    <div class="card">
        <div class="head fw-bold">Add new scopes</div>
        <div class="body">
            <div class="item">
                <input
                    type="text"
                    class="input"
                    placeholder="Scope"
                    v-model="form.scope"
                />
                <v-error :error="errors.scope"></v-error>
            </div>
            <div class="item">
                <textarea
                    class="textarea"
                    v-model="form.description"
                    placeholder="No lengthy description"
                >
                </textarea>
                <v-error :error="errors.description"></v-error>
            </div>
            <div class="item">
                <input
                    type="checkbox"
                    value="1"
                    id="categoria"
                    v-model="form.public"
                />
                <label for="categoria" class="text-sm">
                    Make available for all users (Public Scope)
                </label>
                <v-error :error="errors.public"></v-error>
            </div>
            <div class="item">
                <input
                    type="checkbox"
                    value="1"
                    id="required_payment"
                    v-model="form.required_payment"
                />
                <label for="required_payment" class="text-sm">
                    Required payement
                </label>
                <v-error :error="errors.required_payment"></v-error>
            </div>
            <div class="buttons">
                <button class="btn btn-primary" @click="create">
                    Add new Scope
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["success"],

    data() {
        return {
            errors: {},
            form: {},
        };
    },

    methods: {
        async create() {
            try {
                const res = await this.$server.post(
                    "/api/admin/roles",
                    this.form
                );

                if (res.status == 422) {
                    this.errors = {};
                    this.form = {};
                    this.$emit("success", res.data.data);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.card {
    border: 1px solid var(--border-color-light);
    padding: 0.5em;
    width: 95%;
    margin: 1% auto;
    color: var(--first-color);
    border-radius: 1em;

    .body {
        display: flex;
        flex-wrap: wrap;
        margin-top: 1%;

        .item {
            flex: 1 1 100%;

            margin-bottom: 1%;
            @media (min-width: 800px) {
                flex: 1 1 calc(100% / 2);
            }
        }
    }
}
</style>
