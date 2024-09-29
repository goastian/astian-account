<template>
    <el-button type="danger" @click="open(token)">Revoke token</el-button>
</template>
<script>
import { ElMessage, ElMessageBox } from "element-plus";
export default {
    emits: ["removed"],

    props: {
        token: {
            type: Object,
            required: true,
        },
    },

    methods: {
        open(token) {
            ElMessageBox.confirm("this token will be revoked", "Revoke token", {
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                type: "warning",
            })
                .then(() => {
                    this.removeToken(token);
                })
                .catch(() => {});
        },

        /**
         * message
         */
        popup(message, type = "success") {
            if (message) {
                ElMessage({
                    message: message,
                    type: type,
                });
            }
        },

        async removeToken(token) {
            try {
                const res = await this.$server.delete(
                    "/oauth/personal-access-tokens/" + token.id
                );

                if (res.status == 204) {
                    this.$emit("removed", res.data);
                }
            } catch (e) {}
        },
    },
};
</script>
<style lang=""></style>
