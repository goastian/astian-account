<template>
    <div class="row search">
        <div class="col">
            <input
                type="text"
                placeholder="First Name"
                v-model="search.last_name"
                class="form-control form-control-sm"
                @keyup.enter="searching()"
            />
        </div>
        <div class="col">
            <input
                type="text"
                placeholder="Last Name"
                v-model="search.name"
                class="form-control form-control-sm"
                @keyup.enter="searching()"
            />
        </div>

        <div class="col">
            <input
                type="email"
                placeholder="Email Address"
                v-model="search.email"
                class="form-control form-control-sm"
                @keyup.enter="searching()"
            />
        </div>

        <div class="col">
            <button class="btn bg-warning btn-sm" @click="clear()">
                Clear
            </button>
        </div>
        <div class="col">
            <slot name="button"></slot>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["success"],

    data() {
        return {
            search: {
                last_name: null,
                name: null,
                email: null,
                number: null,
            },
        };
    },

    methods: {
        searching() {
            this.$emit("success", this.search);
        },

        clear() {
            (this.search = {
                last_name: null,
                name: null,
                email: null,
                number: null,
            }),
                this.$emit("searching", this.search);
        },
    },
};
</script>

<style scoped lang="scss">
.search .col {
    flex: 0 0 auto;
    margin: 1%;

    @media (min-width: 240px) {
        width: 98%;
    }

    @media (min-width: 568px) {
        width: 48%;
    }

    @media (min-width: 800px) {
        width: 48%;
    }

    @media (min-width: 940px) {
        margin: 0%;
        width: 20%;
    }
}
</style>
