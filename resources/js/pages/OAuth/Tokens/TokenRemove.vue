<template>
    <v-confirm bg="btn-seconday" @is-confirmed="removeToken(token)">
        <template v-slot:button>Revoke</template>
        <template v-slot:head> Revoke Token </template>
        <template v-slot:body> This token will be revoked. </template>
    </v-confirm>
</template>
<script>
export default {
    emits: ["tokenRevoked"],

    props: {
        token: {
            type: Object,
            required: true,
        },
    },

    methods: {
        removeToken(token) {
            this.$server
                .delete("/oauth/tokens/" + token.id)
                .then((res) => {
                    this.$emit("tokenRevoked", token);
                })
                .catch((e) => {
                    console.log(e);
                });
        },
    },
};
</script>
<style lang=""></style>
