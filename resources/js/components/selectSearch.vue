<template>
    <ul :id="identifier" class="select-menu" :tabindex="identifier">
        <li @click="showMenu(items)">
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
            <li class="input">
                <input
                    class="text-capitalize"
                    type="text"
                    id="input"
                    :placeholder="placeholder"
                    @keyup="setKey"
                    @keyup.enter="selectKey"
                />
            </li>
            <ul class="options">
                <li
                    v-for="(item, num) in collections"
                    :key="num"
                    @click="selectMenu(num)"
                    :class="{ 'fw-bold': changeMenu(num) }"
                >
                    <slot name="options" :items="item"> </slot>
                </li>
            </ul>
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
        param: {
            required: true,
            type: String,
        },

        index: {
            required: false,
            type: [String, Number],
            default: 0,
        },
        placeholder: {
            required: false,
            type: String,
            default: "Search ...",
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
            collections: [],
            selectedIndex: -1,
            identifier: Math.random().toString(36).substring(2),
        };
    },

    mounted() {
        const dom = document.getElementById(this.identifier);
        dom.addEventListener("keydown", this.handleKeyDown);
        this.hideMenu();
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
                    event.target.id != "input" &&
                    event.target.parentNode.parentNode == this.identifier &&
                    this.is_visible
                ) {
                    this.is_visible = false;
                }

                if (
                    event.target.id != "input" &&
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
            if (this.collections.length > 0 && this.selectedIndex > -1) {
                this.value = this.collections[this.selectedIndex];
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
                    this.collections.length - 1,
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
                this.value = this.collections[this.selectedIndex];
                this.sendSelectedData(this.value);
                this.is_visible = false;
            }
        },

        showMenu(items) {
            this.is_visible = !this.is_visible;
            this.collections = items;
        },

        selectMenu(index) {
            this.value = this.collections[index];
            this.sendSelectedData(this.value);
            this.showMenu();
        },

        selectKey() {
            if (
                this.selectedIndex > -1 &&
                this.collections.length >= 1 &&
                this.selectedIndex <= this.collections.length - 1
            ) {
                this.value = this.collections[this.selectedIndex];
                this.sendSelectedData(this.value);
                this.is_visible = !this.is_visible;
            }
        },

        /**
         * set the collections
         * @param {*} event
         */
        setKey(event) {
            const deny_keys = ["ArrowDown", "ArrowUp"];

            if (!deny_keys.includes(event.key)) {
                this.selectedIndex = -1;

                const result = [];
                this.$props.items.forEach((element) => {
                    element[this.param]
                        .toLowerCase()
                        .match(
                            new RegExp(event.target.value.toLowerCase(), "g")
                        )
                        ? result.push(element)
                        : null;
                });

                this.collections = result;
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
        background-color: var(--theme);

        input {
            border: 1px solid var(--primary);
            border-radius: 1em;
            padding: 0.2em 0.8em;
            width: 100%;

            &.input {
                padding: 0 !important;
                margin: 0;
            }
        }

        .options {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 20em;
            overflow-y: auto;

            li {
                text-align: start;
                cursor: pointer;
                margin: 0.3em 0.4em;

                &:hover {
                    font-weight: bold;
                }
            }
        }
    }
}
</style>
