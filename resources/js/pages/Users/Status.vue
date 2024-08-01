<template>
    <el-button
        :type="user.disabled ? 'danger' : 'warning'"
        @click="open(user)"
        >{{ user.disabled ? "Inactive" : "Active" }}</el-button
    >
</template>
<script>
import { ElMessage, ElMessageBox } from "element-plus";
export default {
    props: {
        user: { type: Object, requered: true },
    },

    methods: {
        async enableUser(item) {
            try {
                const res = await this.$server.get(item.links.enable);

                if (res.status == 200) {
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                }
            }
        },

        async disableUser(item) {
            try {
                const res = await this.$server.delete(item.links.disable);

                if (res.status == 200) {
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                }
            }
        },

        open(item) {
            ElMessageBox.confirm(
                item.disabled
                    ? "Are you sure you want to enable this user?"
                    : "Are you sure you want to dasable this user? For security reasons we're removing all credentials for this user.",
                item.disabled ? "Enable user account" : "disable user account",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            )
                .then(() => {
                    if (item.disabled) {
                        this.enableUser(item);
                    } else {
                        this.disableUser(item);
                    }
                })
                .catch(() => {
                    ElMessage({
                        type: "info",
                        message: "canceled",
                    });
                });
        },
    },
};
</script>
<style lang=""></style>
