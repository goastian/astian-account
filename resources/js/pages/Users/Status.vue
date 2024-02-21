<template lang="">
    <v-modal
        :target="'__X_' + user.id"
        :styles="['btn-sm', [user.inactivo ? 'btn-danger' : 'btn-primary']]"
        :button_accept_show="false"
        button_cancel_name="Abort operation"
    >
        <template v-slot:button>
            {{ user.inactivo ? "Inactive" : "Active" }}
        </template>
        <template v-slot:head>
            {{ user.inactivo ? "Enable user account" : "disable user account" }}
        </template>

        <template v-slot:body>
            <div>
                {{
                    user.inactivo
                        ? "Are you sure you want to enable this user?"
                        : "Are you sure you want to dasable this user? For security reasons we're removing all credentials for this user."
                }}
            </div>
            <button
                @click="enableOrDisable(user)"
                data-bs-dismiss="modal"
                class="btn btn-success mt-4"
            >
                Procced
            </button>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success", "errors"],

    props: {
        user: { type: Object, requered: true },
    },

    methods: {
        enableOrDisable(item) {
            if (item.inactivo) {
                this.$server
                    .get(item.links.enable)
                    .then((res) => {
                        this.$emit("success", res.data.data);
                    })
                    .catch((e) => {
                        if (e.response) {
                            this.$emit("errors", e.response);
                        }
                    });
            } else {
                this.$server
                    .delete(item.links.disable)
                    .then((res) => {
                        this.$emit("success", res.data.data);
                    })
                    .catch((e) => {
                        if (e.response) {
                            this.$emit("errors", e.response);
                        }
                    });
            }
        },
    },
};
</script>
<style lang=""></style>
