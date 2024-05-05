<template>
    <ul :id="identifier" class="select-menu" :tabindex="identifier">
        <li @click="showMenu">
            <slot name="title" :item="value" :text="text"></slot>
            <i
                :class="{
                    'mx-2': true,
                    bi: true,
                    'bi-caret-down': !is_visible,
                    'bi-caret-up': is_visible,
                }"
            ></i>
        </li>
        <ul v-show="is_visible" class="float-menu">
            <li
                v-for="(item, index) in items"
                :key="index"
                @click="selectMenu(item)"
                class="item"
                :class="{ 'fw-bold': changeMenu(index) }"
            >
                <slot name="options" :items="item"> </slot>
            </li>
        </ul>
    </ul>
</template>
<script>
export default {
    props: {
        items: {
            required: true,
            type: Object,
        },

        index: {
            required: false,
            type: [String, Number],
        },

        text: {
            required: false,
            type: String,
        },
    },

    emits: ["selected"],

    data() {
        return {
            is_visible: false,
            value: {},
            selectedIndex: -1,
            identifier: Math.random().toString(36).substring(2),
        };
    },

    mounted() {
        const dom = document.getElementById(this.identifier);
        dom.addEventListener("keydown", this.handleKeyDown);
        this.hideMenu();

        if (this.index >= -1) {
            this.value = this.items[this.index];
            // this.sendSelectedData(this.value);
        }
    },

    beforeDestroy() {
        //destroy the event
        const dom = document.getElementById(this.identifier);
        dom.removeEventListener("keydown", this.handleKeyDown);
    },

    methods: {
        /**
         * Send selected data
         * @param {*} item
         */
        sendSelectedData(item) {
            this.$emit("selected", item);
        },

        /**
         * Hide popup the user pess click outside the popup
         */
        hideMenu() {
            const body = document.getElementsByTagName("body")[0];
            body.addEventListener("click", this.hideFloatMenu);
        },

        /**
         * funtion to hide the menu
         */
        hideFloatMenu(event) {
            try {
                if (
                    event.target.parentNode.parentNode == this.identifier &&
                    this.is_visible
                ) {
                    this.is_visible = false;
                }

                if (
                    event.target.parentNode.parentNode.id != this.identifier &&
                    this.is_visible != false
                ) {
                    this.is_visible = false;
                }
            } catch (TypeError) {
                if (this.is_visible) {
                    this.is_visible = false;
                }
            }
        },

        /**
         * Change menu using arrow key
         */
        changeMenu(index) {
            if (this.items.length > 1 && this.selectedIndex > -1) {
                this.value = this.items[this.selectedIndex];
                // this.sendSelectedData(this.value)
                return this.selectedIndex === index;
            }
        },
        /**
         * Change the item in the suggestions list
         * when the user press the arrow down and up Key
         * @param {*} event
         */
        handleKeyDown(event) {
            if (event.key === "ArrowUp") {
                event.preventDefault();
                this.selectedIndex = Math.max(0, this.selectedIndex - 1);
            } else if (event.key === "ArrowDown") {
                event.preventDefault();
                this.selectedIndex = Math.min(
                    this.items.length - 1,
                    this.selectedIndex + 1
                );
            }

            /**
             * Close the popup
             */
            if (event.key === "Escape") {
                this.is_visible = false;
            }

            /**
             * Selected the current item if the user press the enter
             */
            if (event.key === "Enter" && this.selectedIndex > -1) {
                this.value = this.items[this.selectedIndex];
                this.sendSelectedData(this.value);
                this.is_visible = false;
            }
        },

        showMenu() {
            this.is_visible = !this.is_visible;
        },

        selectMenu(value) {
            this.value = value;
            this.sendSelectedData(this.value);
            this.showMenu();
        },

        selectKey() {
            if (
                this.selectedIndex > -1 &&
                this.items.length >= 1 &&
                this.selectedIndex <= this.items.length - 1
            ) {
                this.value = this.items[this.selectedIndex];
                this.sendSelectedData(this.value);
                this.is_visible = !this.is_visible;
            }
        },
    },
};
</script>
<style lang="scss" scoped>
.select-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    min-width: 100%;
    border-radius: 1em;

    li:first-child {
        padding: 0;
        margin: 0;
        text-align: start;
        cursor: pointer;
    }

    .float-menu {
        list-style: none;
        position: absolute;
        border: 1px solid var(--light);
        color: var(--code);
        padding: 0.4em 0.5em 0.5em 0.5em;
        border-radius: 0.5em;
        margin-top: 1.8em;
        background-color: var(--theme);
        max-height: 20em;
        overflow-y: scroll;

        .item {
            text-align: start;
            cursor: pointer;
            margin: 0.3em 0.4em;

            &:hover {
                font-weight: bold;
            }
        }
    }
}
</style>
