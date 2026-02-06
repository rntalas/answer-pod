import { EditorState } from "@codemirror/state";
import { EditorView } from "@codemirror/view";
import { basicSetup } from "codemirror";
import { javascript } from "@codemirror/lang-javascript";
import { html } from "@codemirror/lang-html";
import { oneDark } from "@codemirror/theme-one-dark";

window.codeMirrorEditor = function () {
    return {
        editor: null,

        setup(name, encodedValue, language = 'javascript', theme = 'dark') {
            const initialValue = encodedValue ? atob(encodedValue) : ''
            const lang = language.toLowerCase()

            let langExtension
            switch (lang) {
                case 'html':
                    langExtension = html()
                    break
                case 'javascript':
                default:
                    langExtension = javascript()
            }

            const extensions = [
                basicSetup,
                langExtension,
                EditorView.lineWrapping,
                EditorView.updateListener.of(update => {
                    if (update.docChanged) {
                        this.$refs.hidden.value = update.state.doc.toString()
                    }
                }),
            ]

            if (theme === 'dark') {
                extensions.push(oneDark)
            }

            this.editor = new EditorView({
                state: EditorState.create({
                    doc: initialValue,
                    extensions,
                }),
                parent: this.$refs.editor,
            })
        },
    }
}


