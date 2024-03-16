<template lang="">
    <v-modal
        :target="'__X_' + user.id"
        styles="btn btn-link"
        :button_accept_show="false"
        button_cancel_name="Cancelar operacion"
    >
        <template v-slot:button> Destroy Account </template>
        <template v-slot:head> Delete permanently </template>

        <template v-slot:body>
            <div class="text-center text-color">
                The account will be eliminated permanent. Once eliminated any
                information you have saved will be deleted. We will send you an
                email.
            </div>
            <button
                @click="remove(user)"
                data-bs-dismiss="modal"
                class="btn btn-success mt-4"
            >
                Accep
            </button>
        </template>
    </v-modal>
</template>
<script>
export default {
    emits: ["success", "errors"],

    props: ["user"],

    methods: {
        remove(item) {
            this.$server
                .delete(item.links.disable)
                .then((res) => {
                    this.$emit(
                        "success",
                        "Your account has been destoryed, we're sending an email confimation. in 5 seconds your session will be destroy"
                    );
                })
                .catch((e) => {
                    if (e.response) {
                        this.$emit("errors", e.response);
                    }
                });
        },
    },
};
</script>
<style lang=""></style>
