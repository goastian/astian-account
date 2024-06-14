<template>
    <v-confirm bg="btn-secondary" @is-confirmed="removeToken(token)">
        <template v-slot:button> Revoke token</template>
        <template v-slot:head> Revoke token </template>
        <template v-slot:body> this token will be revoked </template>
    </v-confirm>
</template>
<script>
export default {
    emits: ["TokenRemoved"],

    props: {
        token: {
            type: Object,
            required: true,
        },
    },

    methods: {
        async removeToken(token) {
            try {
                const res = await this.$server.delete(
                    "/oauth/personal-access-tokens/" + token.id
                );

                if (res.status == 204) {
                    this.$emit("TokenRemoved", res.data);
                }
            } catch (e) {}
        },
    },
};
</script>
<style lang=""></style>
