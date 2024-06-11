<template>
    <v-confirm bg="btn-secondary" @is-confirmed="removeScope(scope)">
        <template v-slot:button> Delete </template>
        <template v-slot:head>
            The next scope (<strong>{{ scope.scope }}</strong
            >) will be destroyed
        </template>
        <template v-slot:body>
            <p class="text-xl fw-bold">Caution: System failure</p>
            <p class="text-md">
                Deleting this scope may cause system failures. Only delete
                scopes that were entered by mistake or that are no longer
                necessary for the operation of any application.
            </p>
        </template>
    </v-confirm>
</template>
<script>
export default {
    emits: ["success", "errors"],

    props: {
        scope: {
            type: Object,
            required: true,
        },
    },

    methods: {
        async removeScope(item) {
            try {
                const res = await this.$server.delete(item.links.destroy);
                if (res.status == 200) {
                    this.$emit("success", res.data.data);
                }
            } catch (e) {
                if (e.response && e.response.status == 403) {
                    this.$emit("errors", e.response);
                }
            }
        },
    },
};
</script>
