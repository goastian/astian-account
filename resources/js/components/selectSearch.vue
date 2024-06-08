<template>
    <div class="menu" :id="identifier" :tabindex="identifier">
        <div class="head">
            <span @click="showMenu">
                <slot name="title" :item="value" :text="text"></slot>
            </span>
            <i
                @click="showMenu"
                :class="{
                    'mx-2': true,
                    bi: true,
                    'bi-caret-down': !is_visible,
                    'bi-caret-up': is_visible,
                }"
            ></i>
        </div>
        <div class="body" v-show="is_visible">
            <div class="searcher">
                <input
                    id="input"
                    type="text"
                    :placeholder="placeholder"
                    @keyup="setKey"
                    @keyup.enter="selectKey"
                />
            </div>
            <ul class="list">
                <li
                    v-for="(item, num) in collections"
                    :key="num"
                    @click="selectMenu(num)"
                    :class="{ 'fw-bold': changeMenu(num) }"
                >
                    <slot name="options" :items="item"> </slot>
                </li>
            </ul>
        </div>
    </div>
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
        this.collections = this.items;
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

        showMenu() {
            this.is_visible = !this.is_visible;
            this.collections = this.items;
        },

        selectMenu(index) {
            this.value = this.collections[index];
            this.sendSelectedData(this.value);
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
.menu {
    .head {
        text-align: start;
        text-transform: capitalize;
        cursor: pointer;

        span {
            margin-bottom: 0.5em;
        }
    }

    .body {
        background-color: white;
        border: 1px solid #e4dcdc;
        overflow-x: none;
        padding: 0 0.3em;
        position: absolute;
        border-radius: 0.3em;

        .searcher {
            input {
                width: 95%;
                padding: 0.4em;
                border-radius: 0.3em;
                border-style: solid;
                margin: 0.2em 0;
            }
        }
        .list {
            overflow-y: scroll;
            max-height: 300px;
            list-style: none;
            padding: 0 0.3em;
            margin: 0;
            li {
                cursor: pointer;
                margin-bottom: 0.5em;
            }
        }
    }
}
</style>
