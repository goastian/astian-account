<template>
    <div class="pagination">
        <ul class="pages">
            <li class="item" @click="getPage(pages.current_page - 1)">Prev</li>
            <li class="item" @click="getPage(pages.current_page + 1)">Next</li>
        </ul>
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
            disabled_prev: null,
            disabled_next: null,
        };
    },

    methods: {
        getPage(id) {
            if (id == 0) {
                this.$emit("sendCurrentPage", 1);
            } else {
                this.$emit("sendCurrentPage", id);
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.pagination {
    width: 100%;
    padding: 1em;

    .pages {
        display: flex;
        list-style: none;
        justify-content: center;
        padding: 0;

        .item {
            margin: 1%;
            cursor: pointer;
            background-color: var(--primary);
            color: var(--white);
            font-weight: bold;
            border-radius: 0.3em;
            padding: 0.3em 1em;
            min-width: 10%;
            text-align: center;
            &:hover {
                background-color: var(--warning);
            }
        }
    }
}
</style>
