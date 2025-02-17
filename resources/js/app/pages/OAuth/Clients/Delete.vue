<template>
    <v-dialog max-width="500">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                color="red-darken-1"
                icon
                variant="tonal"
            >
                <v-icon icon="mdi-delete-outline"></v-icon>
            </v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card title="Dialog">
                <v-card-text>
                    Are you share you want to remove this client with name
                    <v-chip color="blue-darken-1">{{ item.name }}</v-chip> with
                    ID <v-chip color="blue-darken-1">{{ item.id }}</v-chip> ?
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        text="Agree"
                        color="blue-accent-2"
                        @click="destroy(isActive)"
                    ></v-btn>

                    <v-btn
                        text="Close"
                        color="red-darken-1"
                        @click="isActive.value = false"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>
<script>
export default {
    emits: ["deleted"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    methods: {
        async destroy(isActive) {
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    isActive.value = false;
                }
            } catch (err) {}
        },
    },
};
</script>
