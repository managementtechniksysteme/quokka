<template>
    <div>
        <vue-easymde :name="name" v-model="content" :configs="configuration" ref="markdownEditor"/>
    </div>
</template>

<script>
    export default {
        name: "MarkdownEditor",

        data() {
            return {
                content: this.value,
                configuration: this.configs ?? {
                    placeholder: this.placeholder,
                    maxHeight: '300px',
                    tabSize: 4,
                    indentWithtabs: false,
                    spellChecker: false,
                    status: false,
                    renderingConfig: {
                        singleLineBreaks: false
                    },
                    toolbar: [
                        'bold',
                        'italic',
                        'strikethrough',
                        'heading',
                        'quote',
                        'unordered-list',
                        'ordered-list',
                        'link',
                        'image',
                        'table',
                        '|',
                        'preview',
                        'side-by-side',
                        'fullscreen',
                        '|',
                        {
                            name: 'linebreaks',
                            className: 'fa fa-paragraph',
                            title: 'Show line breaks',
                            action: function (editor) {
                                editor.codemirror.setOption('showMarkdownLineBreaks',
                                    !editor.codemirror.getOption('showMarkdownLineBreaks'))
                            },
                        }
                    ],
                },
            };
        },

        computed: {
            easymde() {
                return this.$refs.markdownEditor.easymde;
            },
        },

        created() {
            var CodeMirror = require('codemirror');

            CodeMirror.defineOption("showMarkdownLineBreaks", false, function(codeMirror, newValue, oldValue) {

                if (oldValue == CodeMirror.Init) {
                    oldValue = false;
                }
                if (oldValue && !newValue) {
                    codeMirror.removeOverlay("show-markdown-line-breaks");
                }
                else if (!oldValue && newValue) {
                    codeMirror.addOverlay({
                        name: "show-markdown-line-breaks",
                        token: function(stream) {
                            // Possible stream.string:s and how they're handled: (`n` is text
                            // with no trailing spaces)
                            // ""
                            // "n"    case 1
                            // "_"    case 3
                            // "n_"   case 1, case 3
                            // "__"   case 2, case 3
                            // "n__"  case 1, case 2, case 3
                            // "___"  case 2, case 3
                            // "n___" case 1, case 2, case 3

                            // Returns separate CSS classes for each trailing space;
                            // otherwise CodeMirror merges contiguous spaces into
                            // one single <span>, and then I don't know how to via CSS
                            // replace each space with exact one '·'.
                            function singleTrailingSpace() {
                                return stream.pos % 2 == 0 ?
                                    "markdown-single-trailing-space-even" :
                                    "markdown-single-trailing-space-odd";
                            }

                            if (!stream.string.length) { // can this happen?
                                return null;
                            }

                            // skip non breakable lines
                            let skipBreak = stream.string.match(/(#|---).*/);

                            // Case 1: Non-space characters. Eat until last non-space char.
                            if (stream.match(/.*[^ ]/)) {
                                return null;
                            }

                            // Case 2, more than one trailing space left before end-of-line.
                            // Match one space at a time, so each space gets its own
                            // `singleTrailingSpace()` CSS class. Look-ahead (`(?=`) for more spaces.
                            if (stream.match(/ (?= +$)/))
                                return singleTrailingSpace();

                            // Case 3: the very last char on this line, and it's a space.
                            // Count num trailing spaces up to including this last char on this line.
                            // If there are 2 spaces (or more), we have a line break.
                            var str = stream.string;
                            var len = str.length;
                            var twoTrailingSpaces = len >= 2 && str[len - 2] == ' ';
                            stream.eat(/./);

                            if (twoTrailingSpaces && !skipBreak) {
                                return "markdown-line-break";
                            }

                            return singleTrailingSpace();
                        }
                    });
                }
            });
        },

        props: {
            name: {
                type: String,
                default() {
                    return null;
                }
            },

            value: {
                type: String,
                default() {
                    return null;
                }
            },

            placeholder: {
                type: String,
                default() {
                    return null;
                }
            },

            configs: {
                type: Object,
                default() {
                    return null;
                }
            },
        },
    };
</script>
