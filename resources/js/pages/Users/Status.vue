<template>
    <v-confirm      
        :bg="user.disabled ? 'btn-ternary' : 'btn-warning'"
        @is-confirmed="enableOrDisable(user)"
    >
        <template v-slot:button>
            {{ user.disabled ? "Inactive" : "Active" }}
        </template>
        <template v-slot:head>
            {{ user.disabled ? "Enable user account" : "disable user account" }}
        </template>
        <template v-slot:body>
            {{
                user.disabled
                    ? "Are you sure you want to enable this user?"
                    : "Are you sure you want to dasable this user? For security reasons we're removing all credentials for this user."
            }}
        </template>
    </v-confirm>
</template>
<script>
export default {
    emits: ["success", "errors"],

    props: {
        user: { type: Object, requered: true },
    },

    methods: {
        enableOrDisable(item) {
            if (item.disabled) {
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
