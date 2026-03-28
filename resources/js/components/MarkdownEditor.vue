<template>
    <div>
        <input type="hidden" :name="name" :value="content" />
        <vue-easymde v-model="content" :options="configuration" ref="markdownEditor"/>
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

        async created() {
            const CodeMirror = (await import('codemirror')).default;

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
                            function singleTrailingSpace() {
                                return stream.pos % 2 == 0 ?
                                    "markdown-single-trailing-space-even" :
                                    "markdown-single-trailing-space-odd";
                            }

                            if (!stream.string.length) {
                                return null;
                            }

                            let skipBreak = stream.string.match(/(#|---).*/);

                            if (stream.match(/.*[^ ]/)) {
                                return null;
                            }

                            if (stream.match(/ (?= +$)/))
                                return singleTrailingSpace();

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
                default: null,
            },

            value: {
                type: String,
                default: null,
            },

            placeholder: {
                type: String,
                default: null,
            },

            configs: {
                type: Object,
                default: null,
            },
        },
    };
</script>
