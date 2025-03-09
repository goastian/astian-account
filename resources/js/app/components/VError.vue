<template>
    <div v-if="hasErrors">
        <span
            v-for="(item, index) in filteredErrors"
            :key="index"
            class="text-red-500 block"
        >
            {{ item }}
        </span>
    </div>
</template>

<script>
export default {
    props: {
        error: {
            type: [Object, String],
            required: true,
            default: () => ({ example: "example Error" }),
        },
    },
    computed: {
        filteredErrors() {
            if (typeof this.error === "string") {
                return [this.error];
            }
            return Object.values(this.error).filter(
                (msg) => msg !== "example Error"
            );
        },
        hasErrors() {
            return this.filteredErrors.length > 0;
        },
    },
};
</script>
