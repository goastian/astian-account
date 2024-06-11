<template>
    
    <v-confirm @is-confirmed="remove(item)">
        <template v-slot:button> remove </template>

        <template v-slot:head>
            Remove Channel (<strong>{{ item.channel }}</strong
            >)
        </template>
        <template v-slot:body>
            <p class="text-xl text-center">
                Are you sure to destroy this channel
                <strong>{{ item.channel }}</strong
                >?
            </p>
        </template>
    </v-confirm>
</template>
<script>
export default {
    props: ["item"],

    emits: ["success", "errors"],

    methods: {
        async remove(item) {
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
