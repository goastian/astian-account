<template>
    <div class="header">
        <div class="left">
            <div>
                <q-btn
                    dense
                    flat
                    round
                    icon="menu"
                    @click="state.isVisible = !state.isVisible"
                />
            </div>
            <span>.</span>
            <div class="title">
                <q-icon name="mdi-home" size="20px" />
                <span>{{ title }}</span>
            </div>
        </div>
        <v-profile />
    </div>
</template>

<script>
export default {
    data() {

    },

    props: {
        title: {
            type: String,
            required: true,
        }
    },

    inject: ['state'],
}
</script>

<style scoped>
.left {
    display: flex;
    gap: .5rem;
}

.left > span {
    font-size: 1.5rem;
    line-height: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    padding: .4rem .8rem;
    border-bottom: .04rem solid var(--border-color);
}

.title {
    display: flex;
    align-items: center;
    gap: .4rem;
}

</style>
