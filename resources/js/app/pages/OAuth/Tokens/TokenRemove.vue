<template>
    <el-button type="danger" @click="open(token)">Revoke token</el-button>
</template>

<script>
import { ElMessage, ElMessageBox } from "element-plus";

export default {
    emits: ["revoked"],

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

        removeToken(token) {
            this.$server
                .delete("/oauth/tokens/" + token.id)
                .then((res) => {
                    this.$emit("revoked", token);
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style lang=""></style>
