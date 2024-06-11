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
        async enableUser(item) {
            try {
                const res = await this.$server.get(item.links.enable);

                if (res.status == 200) {
                    this.$emit("success", res.data.data);
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.$emit("errors", e.response);
                }
            }
        },

        async disableUser(item) {
            try {
                const res = await this.$server.delete(item.links.disable);

                if (res.status == 200) {
                    this.$emit("success", res.data.data);
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.$emit("errors", e.response);
                }
            }
        },

        enableOrDisable(item) {
            if (item.disabled) {
                this.enableUser(item);
            } else {
                this.disableUser(item);
            }
        },
    },
};
</script>
<style lang=""></style>
