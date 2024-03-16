<template>
    <v-modal target="create" @is-accepted="createUser">
        <template v-slot:button> New user </template>
        <template v-slot:body>
            <div class="row user-register">
                <div class="col">
                    <input
                        placeholder="Firs Name"
                        class="form-control form-control-sm"
                        type="text"
                        v-model="form.name"
                    />
                    <v-error :error="errors.name"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        placeholder="Last Name"
                        v-model="form.last_name"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.last_name"></v-error>
                </div>
                <div class="col">
                    <input
                        type="email"
                        v-model="form.email"
                        placeholder="Email Address"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.email"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        id="country"
                        placeholder="Country"
                        v-model="form.country"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.country"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="form.city"
                        placeholder="City"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.city"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="form.address"
                        placeholder="Home Address"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.address"></v-error>
                </div>
                <div class="col">
                    <input
                        type="text"
                        v-model="form.phone"
                        placeholder="Phone Number"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.phone"></v-error>
                </div>
                <div class="col">
                    <input
                        type="date"
                        v-model="form.birthday"
                        placeholder="Birthday"
                        class="form-control form-control-sm"
                    />
                    <v-error :error="errors.birthday"></v-error>
                </div>
            </div>
            <div class="m-2 p-2">
                <span class="">User Scopes</span>
            </div>
            <div class="row user-scopes border p-1">
                <div
                    class="col form-check"
                    v-for="(item, index) in roles"
                    :key="index"
                    v-show="!item.public"
                >
                    <input
                        class="form-check-input"
                        type="checkbox"
                        :value="item.id"
                        :id="index"
                        v-model="form.scope"
                    />
                    <label class="form-check-label text-sm" :for="index">
                        <strong class="text-color">{{ item.scope }}: </strong>
                        <span>{{ item.description }}</span>
                    </label>
                </div>
            </div>
            <div>
                <v-error :error="errors.scope"></v-error>
            </div>
            <v-message :message="message" @close="close"></v-message>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success", "errors"],

    data() {
        return {
            form: { scope: [] },
            errors: {},
            roles: {},
            message: null,
        };
    },

    mounted() {
        this.getRoles();
    },

    methods: {
        close() {
            this.message = null;
        },
        getRoles() {
            this.$server
                .get("/api/roles")
                .then((res) => {
                    this.roles = res.data.data;
                })
                .catch((e) => {});
        },

        createUser() {
            this.$server
                .post("/api/users", this.form)
                .then((res) => {
                    this.message = "A new user has been registered";
                    this.form = { scope: [] };
                    this.errors = {};
                    this.$emit("success", res.data.data);
                })
                .catch((e) => {
                    if (e.response && e.response.data.errors) {
                        this.errors = e.response.data.errors;
                    }
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.col {
    flex: 0 0 auto;

    @media (min-width: 320px) {
        margin-bottom: 2%;
        width: 98%;
    }

    @media (min-width: 800px) {
        width: 45%;
    }

    @media (min-width: 940px) {
        margin-bottom: 1%;
        width: 30%;
    }
}
</style>
