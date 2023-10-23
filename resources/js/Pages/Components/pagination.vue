<template>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li :class="['page-item', disabled_prev]">
                <a href="#" class="page-link" v-on:click="getPage(pages.current_page - 1)">
                    Anterior
                </a>
            </li>
            <li :class="['page-item', pages.current_page == index + 1 ? 'active' : '']" 
            v-for="(item, index) in pages.total_pages" :key="index" v-on:click="getPage(index + 1)">
                <a class="page-link" href="#">
                    {{ index + 1 }}</a>
            </li>

            <li :class="['page-item', disabled_next]">
                <a class="page-link" v-on:click="getPage(pages.current_page + 1)" href="#">
                    Siguiente
                </a>
            </li>
        </ul>
    </nav>   
</template>
<script>
export default {
    props: {
        pages: Object,
    },

    emits: ['sendCurrentPage'],

    data() {
        return {
            current_page: 1,
            disabled_prev: null,
            disabled_next: null,
        }
    },

    updated() {
        this.disablePreviuous()
    },

    created() {
        this.disablePreviuous()
    },

    methods: {

        disablePreviuous() {
            this.disabled_prev = (this.pages.current_page == 1) 
                                || (this.pages.total_pages == null) 
                                ? 'disabled' : ''
            this.disabled_next = (this.pages.current_page == this.pages.total_pages) ? 'disabled' : ''
        },

        getPage(id) {
            if (id == 0) { 
                this.$emit('sendCurrentPage', 1)
            } else { 
                this.$emit('sendCurrentPage', id)
            }
        }

    },


}
</script> 