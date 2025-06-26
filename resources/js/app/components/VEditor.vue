<template>
    <div class="q-my-md">
        <div id="toolbar" ref="toolbar">
            <span class="ql-formats">
                <select class="ql-header">
                    <option value="1"></option>
                    <option value="2"></option>
                    <option selected></option>
                </select>
                <select class="ql-font"></select>
            </span>
            <span class="ql-formats">
                <button class="ql-bold"></button>
                <button class="ql-italic"></button>
                <button class="ql-underline"></button>
                <button class="ql-strike"></button>
            </span>
            <span class="ql-formats">
                <select class="ql-color"></select>
                <select class="ql-background"></select>
            </span>
            <span class="ql-formats">
                <button class="ql-link"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-list" value="ordered"></button>
                <button class="ql-list" value="bullet"></button>
                <button class="ql-indent" value="-1"></button>
                <button class="ql-indent" value="+1"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-clean"></button>
            </span>
        </div>

        <div class="editor" id="editor" ref="editor"></div>
        <v-error :error="error"></v-error>
    </div>
</template>
<script>
import { nextTick, watch } from "vue";
import Quill from "quill";
import "quill/dist/quill.snow.css";

export default {
    props: {
        text: {
            type: String,
            required: false,
            default: "",
        },
        error: {
            required: false,
            type: [Object, String],
            default: "",
        },
    },

    emits: ["content"],

    data() {
        return {
            editor: null,
            localText: "",
        };
    },

    async mounted() {
        this.localText = this.text;

        await this.loadEditor();

        if (this.localText && this.editor) {
            this.editor.root.innerHTML = this.localText;
        }

        this.emitContent();
    },

    watch: {
        text(newVal) {
            if (this.editor && newVal !== this.editor.root.innerHTML) {
                this.editor.root.innerHTML = newVal;
            }
        },
    },

    methods: {
        async loadEditor() {
            await nextTick();

            this.editor = new Quill(this.$refs.editor, {
                modules: {
                    toolbar: this.$refs.toolbar,
                },
                theme: "snow",
            });

            this.editor.on("text-change", () => {
                this.localText = this.editor.root.innerHTML;
                this.$emit("content", this.localText);
            });
        },

        emitContent() {
            if (this.editor) {
                this.localText = this.editor.root.innerHTML;
                this.$emit("content", this.localText);
            }
        },
    },
};
</script>

<style scoped>
.editor {
    min-height: 200px;
}
#toolbar {
    border: 1px solid #ccc;
    border-bottom: none;
    border-radius: 4px 4px 0 0;
}
</style>
