<template>
    <div class="card text-color">
        <div class="card-head fw-bold text-center border-bottom">
            Devices Conected {{ sessions.length }}
        </div>
        <div class="card-body">
            <div class="row">
                <div
                    v-for="(item, index) in sessions"
                    :key="index"
                    :class="[
                        'col btn mx-1 mb-1',
                        [item.actual ? 'btn-primary card' : 'btn-secondary'],
                    ]"
                >
                    <button
                        v-show="!item.actual"
                        @click="destroySession(item.links.destroy, $event)"
                        class="btn btn-ternary text-white float-end"
                    >
                        X
                    </button>
                    <span>
                        {{ item.ip }}
                    </span>
                    <span>
                        {{ item.agente }}
                    </span>
                    <span>
                        {{ item.ultima_coneccion }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            message: null,
            sessions: {},
        };
    },

    created() {
        this.session();
    },

    methods: {
        session() {
            this.$server
                .get("sessions")
                .then((res) => {
                    this.sessions = res.data.data;
                })
                .catch((e) => {});
        },

        destroySession(link, event) {
            const button = event.target;
            button.disabled = true;

            this.$server
                .delete(link)
                .then((res) => {
                    this.session();
                    button.disabled = false;
                })
                .catch((e) => {
                    button.disabled = false;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.col {
    flex: 0 0 auto;
    width: 30%;
    margin-right: 1%;
}
</style>
