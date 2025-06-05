<template>
    <div class="container" ref="dropdown">
        <span class="label">Theme</span>
        <div class="row select" @click="chageState">
            <span><q-icon :name="theme.selectedTheme.icon" /></span>
            <span>
                {{ theme.selectedTheme.label }}
            </span>
        </div>
        <div class="column list" v-if="state">
            <template
                v-for="(item, index) in theme.themes"
            >
                <div
                    class="item row"
                    :class="{'q-item--active' : item.value == theme.selectedTheme.value}"
                    @click="changeTheme(item)"
                >
                    <span><q-icon :name="item.icon" /></span>
                    <span>{{ item.label }}</span>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import useThemeStore from '../stores/useThemeStore.js';
export default {
    data() {
        return {
            theme: useThemeStore(),
            state: false,
        };
    },

    created() {
        const $q = this.$q;
        this.theme.loadTheme($q)
    },

    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },

    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside)
    },

    methods: {
        chageState() {
            this.state = !this.state;
        },

        handleClickOutside(event) {
            if (this.$refs.dropdown && !this.$refs.dropdown.contains(event.target)) {
                this.state = false
            }
        },

        changeTheme(item) {
            this.theme.changeTheme(item, this.$q);
        },
    }
};
</script>

<style scoped>
.container {
    width: 200px;
    position: relative;
}

.label {
    position: absolute;
    top: -10px;
    left: 10px;
    font-size: .7rem;
}

.select {
    width: 200px;
    background-color: var(--q-background-primary);
    border: 1px solid var(--q-border);
    padding: .4rem .4rem;
    border-radius: .6rem;
    gap: 1rem;
    cursor: pointer;
}

.list {
    position: absolute;
    width: 100%;
    bottom: -120px;
    left: 0;
    background-color: var(--q-background-primary);
    padding: .2rem;
    border-radius: .6rem;
    border: 1px solid var(--q-border);
    gap: .2rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
}

.item {
    gap: 1rem;
    padding: .4rem;
    border-radius: .5rem;
    cursor: pointer;
}

.item:hover {
    background-color: var(--q-color-light);
}

.theme-selector-popup {
    max-height: 200px;
}
</style>
