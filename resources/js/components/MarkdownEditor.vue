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
            await import('codemirror/addon/hint/show-hint.js');

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

        mounted() {
            const codemirror = this.$refs.markdownEditor.getMDEInstance().codemirror;
            const mentionHint = this.mentionHint.bind(this);

            codemirror.on('inputRead', (instance, change) => {
                if (change.text[0] === '@') {
                    instance.showHint({ hint: mentionHint, completeSingle: false });
                }
            });
        },

        computed: {
            mentionableEmployees() {
                return this.employees
                    .filter((person) => person.employee?.user?.username)
                    .map((person) => ({
                        username: person.employee.user.username,
                        name: person.name,
                        avatar: person.avatar,
                    }));
            },
        },

        methods: {
            // CodeMirror show-hint `hint` function: looks back from the cursor to
            // the start of the current "@word", then filters mentionableEmployees
            // by username/name. Returning plain {line, ch} objects (rather than
            // CodeMirror.Pos instances) works fine here — CodeMirror only reads
            // those two properties off from/to.
            mentionHint(cm) {
                const cursor = cm.getCursor();
                const line = cm.getLine(cursor.line);

                let start = cursor.ch;
                while (start > 0 && !/\s/.test(line.charAt(start - 1))) {
                    start--;
                }

                const word = line.slice(start, cursor.ch);

                if (!word.startsWith('@')) {
                    return null;
                }

                const term = word.slice(1).toLowerCase();

                const list = this.mentionableEmployees
                    .filter((person) => person.username.toLowerCase().startsWith(term)
                        || person.name.toLowerCase().includes(term))
                    .slice(0, 8)
                    .map((person) => ({
                        text: `@${person.username} `,
                        name: person.name,
                        avatar: person.avatar,
                        render: this.renderMentionHint,
                    }));

                return {
                    list,
                    from: { line: cursor.line, ch: start },
                    to: { line: cursor.line, ch: cursor.ch },
                };
            },

            renderMentionHint(element, data, completion) {
                const avatar = document.createElement('span');
                avatar.className = 'q-avatar q-avatar--round q-avatar--sm me-2';
                avatar.textContent = completion.avatar?.initials ?? '';

                if (completion.avatar?.hex) {
                    avatar.style.background = `color-mix(in srgb, ${completion.avatar.hex} 20%, transparent)`;
                    avatar.style.color = completion.avatar.hex;
                } else if (completion.avatar?.colour) {
                    avatar.classList.add(`q-avatar--${completion.avatar.colour}`);
                }

                const label = document.createElement('span');
                label.textContent = completion.name;

                element.appendChild(avatar);
                element.appendChild(label);
            },
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

            employees: {
                type: Array,
                default() {
                    return [];
                },
            },
        },
    };
</script>
