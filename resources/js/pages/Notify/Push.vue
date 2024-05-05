<template lang="">
    <div class="notification-push">
        <div class="card">
            <div class="card-head fw-bold text-color text-center py-2">
                Send notifications
            </div>
            <div class="card-body text-color">
                <div class="row">
                    <div class="col">
                        Method
                        <select
                            name="method"
                            id="method"
                            class="form-select"
                            v-model="form.method"
                        >
                            <option value="database">Aplication</option>
                            <option value="mail">Email</option>
                        </select>
                        <v-error :error="errors.method"></v-error>
                    </div>
                    <div class="col">
                        <input
                            type="text"
                            name="title"
                            id="title"
                            placeholder="Title"
                            class="form-control"
                            v-model="form.title"
                        />
                        <v-error :error="errors.title"></v-error>
                    </div>
                    <div class="col">
                        <textarea
                            name="message"
                            id="message"
                            class="form-control"
                            placeholder="here write the message"
                            v-model="form.message"
                        ></textarea>
                        <v-error :error="errors.message"></v-error>
                    </div>
                </div>
                <div class="col">
                    <input
                        class="form-control"
                        type="text"
                        name="resource"
                        id="resource"
                        placeholder="https://astian.com/recurso/policies"
                        v-model="form.resource"
                    />
                    <v-error :error="errors.resource"></v-error>
                </div>

                <div class="col">
                    <input
                        class="form-control"
                        type="text"
                        name="scope"
                        id="scope"
                        placeholder="Email , Scope or * to send to all users"
                        v-model="form.scope"
                    />
                    <v-error :error="errors.scope"></v-error>
                </div>
            </div>
            <div class="card-footer">
                <a href="#" @click="send" class="btn btn-success"
                    >Send notification</a
                >
            </div>
            <v-message :message="message" @close="close"></v-message>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            form: {},
            message: null,
            errors: {},
        };
    },

    methods: {
        close() {
            this.message = null;
        },

        send() {
            this.$server
                .post("/api/notifications/push", this.form)
                .then((res) => {
                    this.errors = {};
                    this.form = {};
                    this.message = res.data.message;
                })
                .catch((err) => {
                    if (err.response && err.response.data.errors) {
                        this.errors = err.response.data.errors;
                    }

                    if (err.response && err.response.status == 403) {
                        this.message = err.response.data.message;
                    }
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.notification-push .col {
    flex: 0 0 auto;
    width: 100%;
    margin-bottom: 4%;

    @media (min-width: 800px) {
        margin-bottom: 2%;
    }
}

.notification-push .card {
    width: 100%;
    margin-top: 3% !important;

    @media (min-width: 800px) {
        width: 70%;
        margin: auto;
    }
}
</style>
