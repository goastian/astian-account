<template>
    <div class="card">
        <div class="head">Add new channel</div>
        <div class="body">
            <div class="box">
                <input
                    v-model="form.channel"
                    type="text"
                    class="input"
                    placeholder="Channel"
                />
                <v-error :error="errors.channel"></v-error>
            </div>
            <div class="box">
                <input
                    v-model="form.description"
                    class="input"
                    placeholder="Description"
                />

                <v-error :error="errors.description"></v-error>
            </div>
            <div class="box">
                <button class="btn btn-primary" @click="storeBroadcast">
                    Add new channel
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
            form: {},
            errors: {},
        };
    },

    methods: {
        async storeBroadcast() {
            try {
                const res = await this.$server.post(
                    "/api/broadcasts",
                    this.form
                );

                if (res.status == 201) {
                    this.form = {};
                    this.errors = {};
                    this.$emit("success", res.data.data);
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
<style lang="scss" scoped>
.card {
    width: 95%;
    margin: 1% auto;
    padding: 0.5em;
    border: 1px solid var(--border-color-light);
    border-radius: 0.5em;
    .head {
        color: var(--first-color);
        font-weight: bold;
    }

    .body {
        display: flex;
        flex-wrap: wrap;

        .box {
            flex: 1 1 100%;

            @media (min-width: 800px) {
                flex: 1 1 calc(100% / 2);
            }
        }
    }
}
</style>
