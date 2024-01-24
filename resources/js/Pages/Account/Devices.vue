<template>
    <div class="devices">
        <div class="col-12">
            <ul class="list-group">
                <li class="list-group-item active text-center">
                    Dispositivos conectados {{ sessions.length }}
                </li>
                <li class="list-group-item">
                    <button
                        class="btn-secondary btn mx-1 mb-1"
                        v-for="(item, index) in sessions"
                        :key="index"
                    >
                        <span style="display: inline-block">
                            {{ item.ip }} <br />
                            {{ item.agente }} <br />
                            {{ item.ultima_coneccion }}
                        </span>
                        <a
                            @click="destroySession(item.links.destroy)"
                            class="btn btn-danger text-white"
                            >X</a
                        >
                    </button>
                </li>
            </ul>
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
                .get("/api/sessions")
                .then((res) => {
                    this.sessions = res.data.data;
                })
                .catch((e) => {
                    if (e.response) {
                        console.log(e.response);
                    }
                });
        },

        destroySession(link) {
            this.$server
                .delete(link)
                .then((res) => {
                    this.session();
                })
                .catch((e) => {
                    if (e.response) {
                        console.log(e.response);
                    }
                });
        },
    },
};
</script>
<style lang=""></style>
