<template>
    <div class="pagination">
        <div class="pages">
            <button
                class="btn btn-primary"
                @click="prevPage(pages.current_page)"
            >
                Prev
            </button>

            <button
                class="btn btn-ternary"
                @click="nextPage(pages.current_page)"
            >
                Next
            </button>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        pages: Object,
    },

    emits: ["sendCurrentPage"],

    data() {
        return {
            current_page: 1,
        };
    },

    methods: {
        nextPage(id) {
            const page = id + 1;

            if (page > 0 && this.pages.current_page < this.pages.total_pages) {
                this.$emit("sendCurrentPage", page);
            }
        },

        prevPage(id) {
            const page = id - 1;

            if (page == 0) {
                this.$emit("sendCurrentPage", 1);
            }

            if (page > 0 && this.pages.current_page > page) {
                this.$emit("sendCurrentPage", page);
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.pagination {
    width: 100%;
    .pages {
        display: flex;
        list-style: none;
        justify-content: center;
        padding: 0;

        button {
            margin: 1%;
            padding: 0.7% 0;
            cursor: pointer;
        }
    }
}
</style>
