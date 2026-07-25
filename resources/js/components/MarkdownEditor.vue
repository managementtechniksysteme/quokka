<template>
    <div>
        <input type="hidden" :name="name" :value="content" />
        <vue-easymde v-model="content" :options="configuration" ref="markdownEditor"/>
    </div>
</template>

<script>
    import { debounce } from 'lodash';

    function createAvatarElement(avatar) {
        const element = document.createElement('span');
        element.className = 'q-avatar q-avatar--round q-avatar--sm';
        element.textContent = avatar?.initials ?? '';

        if (avatar?.hex) {
            element.style.background = `color-mix(in srgb, ${avatar.hex} 20%, transparent)`;
            element.style.color = avatar.hex;
        } else if (avatar?.colour) {
            element.classList.add(`q-avatar--${avatar.colour}`);
        }

        return element;
    }

    function appendMentionContent(element, person) {
        element.appendChild(createAvatarElement(person.avatar));

        const label = document.createElement('span');
        label.textContent = person.name;
        element.appendChild(label);
    }

    // CodeMirror show-hint `render` callback: `this` is the plain completion
    // object when CodeMirror invokes it (`cur.render(elt, data, cur)`), so this
    // stays a free function rather than a component method.
    function renderMentionHint(element, data, completion) {
        appendMentionContent(element, completion);
    }

    function createMentionWidget(person) {
        const element = document.createElement('span');
        element.className = 'q-mention-chip';
        appendMentionContent(element, person);

        return element;
    }

    // reference.icon is server-rendered markup from partials/model_icon.blade.php
    // (the same per-model icon mapping the /search results list already uses),
    // shipped as part of the cross-reference JSON rather than duplicated here.
    function appendCrossReferenceContent(element, reference) {
        element.title = reference.type;
        element.insertAdjacentHTML('beforeend', reference.icon ?? '');

        const label = document.createElement('span');
        label.textContent = reference.name;
        element.appendChild(label);
    }

    function renderCrossReferenceHint(element, data, completion) {
        appendCrossReferenceContent(element, completion);
    }

    function createCrossReferenceWidget(reference) {
        const element = document.createElement('span');
        element.className = 'q-crossref-chip';
        appendCrossReferenceContent(element, reference);

        return element;
    }

    export default {
        name: "MarkdownEditor",

        data() {
            return {
                content: this.value,
                mentionMarks: [],
                crossReferenceMarks: [],
                crossReferenceCache: {},
                crossReferenceAbortController: null,
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
            const crossReferenceHint = debounce(this.crossReferenceHint.bind(this), 200);
            crossReferenceHint.async = true;

            codemirror.on('inputRead', (instance, change) => {
                if (change.text[0] === '@') {
                    instance.showHint({ hint: mentionHint, completeSingle: false });
                } else if (change.text[0] === '#') {
                    instance.showHint({ hint: crossReferenceHint, completeSingle: false });
                }
            });

            // Re-decorates the document on every change (debounced), so
            // mentions/cross-references typed by hand or already present
            // (editing an existing comment/report) turn into chips too, not
            // just ones just picked from a hint dropdown. Skips the line the
            // cursor is currently on: markText's replacedWith forces a
            // re-render of that line, which can race with and swallow an
            // in-flight keystroke on that same line. A final unrestricted pass
            // on blur catches that last line.
            codemirror.on('changes', debounce(() => {
                const line = codemirror.getCursor().line;
                this.decorateMentions(codemirror, line);
                this.decorateCrossReferences(codemirror, line);
            }, 300));
            codemirror.on('blur', () => {
                this.decorateMentions(codemirror, null);
                this.decorateCrossReferences(codemirror, null);
            });

            this.decorateMentions(codemirror, null);
            this.decorateCrossReferences(codemirror, null);
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

            mentionableEmployeesByUsername() {
                const byUsername = {};
                this.mentionableEmployees.forEach((person) => {
                    byUsername[person.username] = person;
                });
                return byUsername;
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
                        username: person.username,
                        render: renderMentionHint,
                        hint: (cm2, data, completion) => this.insertMention(cm2, data, completion),
                    }));

                return {
                    list,
                    from: { line: cursor.line, ch: start },
                    to: { line: cursor.line, ch: cursor.ch },
                };
            },

            // Custom show-hint insertion (replaces the default replaceRange):
            // inserts the text and immediately marks the "@username" part as a
            // chip in the same operation, so picking a suggestion shows the
            // chip right away instead of waiting for the next debounced scan.
            insertMention(cm, data, completion) {
                const from = completion.from || data.from;
                const to = completion.to || data.to;

                // Picking via mouse click leaves show-hint's own `setTimeout(()
                // => cm.focus(), 20)` (from its "mousedown" handler) pending;
                // if the user's very next keystroke lands inside that 20ms
                // window, that delayed refocus can swallow it. Focusing here,
                // synchronously, makes that later call a harmless no-op.
                cm.focus();

                cm.replaceRange(completion.text, from, to, 'complete');

                const chipTo = { line: from.line, ch: from.ch + 1 + completion.username.length };

                this.mentionMarks.push({
                    username: completion.username,
                    mark: cm.markText(from, chipTo, {
                        replacedWith: createMentionWidget(completion),
                        atomic: true,
                        inclusiveLeft: false,
                        inclusiveRight: false,
                    }),
                });
            },

            // Scans the whole document for "@username" runs matching a known
            // mentionable employee (mirrors App\Helpers\Mentions' regex closely
            // enough for this purpose) and replaces each with a chip widget via
            // markText/atomic, so a single Backspace at its edge removes it as
            // one unit rather than character by character.
            //
            // Diffs against the marks already in place rather than clearing
            // and rebuilding everything on every call: recreating a mark right
            // next to where the user is actively typing desyncs the cursor and
            // swallows keystrokes (atomic ranges push the cursor out when
            // (re-)created). A still-valid, unchanged mention is left
            // completely untouched. `excludeLine`, if given, skips creating new
            // marks on that line (the one currently being typed on); pass null
            // to decorate every line, e.g. on mount or on blur.
            decorateMentions(cm, excludeLine) {
                const samePos = (a, b) => a.line === b.line && a.ch === b.ch;

                this.mentionMarks = this.mentionMarks.filter((entry) => {
                    const range = entry.mark.find();

                    if (!range || cm.getRange(range.from, range.to) !== `@${entry.username}`) {
                        entry.mark.clear();
                        return false;
                    }

                    return true;
                });

                const byUsername = this.mentionableEmployeesByUsername;
                const text = cm.getValue();
                const pattern = /(^|[^a-zA-Z0-9_@])@(?!\/)([a-zA-Z0-9/_]{1,15})(?=[^a-zA-Z0-9_/]|$)/g;

                let match;
                while ((match = pattern.exec(text)) !== null) {
                    const username = match[2];
                    const person = byUsername[username];

                    if (!person) {
                        continue;
                    }

                    const atStart = match.index + match[1].length;
                    const from = cm.posFromIndex(atStart);
                    const to = cm.posFromIndex(atStart + 1 + username.length);

                    if (from.line === excludeLine) {
                        continue;
                    }

                    const alreadyMarked = this.mentionMarks.some((entry) => {
                        const range = entry.mark.find();
                        return range && samePos(range.from, from) && samePos(range.to, to);
                    });

                    if (alreadyMarked) {
                        continue;
                    }

                    this.mentionMarks.push({
                        username,
                        mark: cm.markText(from, to, {
                            replacedWith: createMentionWidget(person),
                            atomic: true,
                            inclusiveLeft: false,
                            inclusiveRight: false,
                        }),
                    });
                }
            },

            // Async CodeMirror show-hint `hint` function (note the (cm,
            // callback) signature + `.async = true` set by the caller):
            // searches across every referenceable record via the same
            // GlobalSearch-backed endpoint the app's global search itself
            // uses. Unlike mentionHint, the candidate list can't be preloaded
            // (open-ended, app-wide), so this hits the server on each lookup.
            crossReferenceHint(cm, callback) {
                const cursor = cm.getCursor();
                const line = cm.getLine(cursor.line);

                let start = cursor.ch;
                while (start > 0 && !/\s/.test(line.charAt(start - 1))) {
                    start--;
                }

                const word = line.slice(start, cursor.ch);

                if (!word.startsWith('#') || word.length < 3) {
                    callback(null);
                    return;
                }

                // Without this, each debounced keystroke fires its own
                // request without cancelling the last one -- typing a normal
                // search term fires several overlapping searches (each
                // running the full cross-model GlobalSearch query) that pile
                // up competing for server workers, and can resolve out of
                // order. Abort whatever's still in flight before starting
                // the next one, so only the latest keystroke's request ever
                // completes.
                this.crossReferenceAbortController?.abort();
                this.crossReferenceAbortController = new AbortController();

                axios.get('/cross-references', {
                    params: { query: word.slice(1) },
                    signal: this.crossReferenceAbortController.signal,
                })
                    .then((response) => {
                        const list = response.data.map((reference) => ({
                            text: `#${reference.token} `,
                            type: reference.type,
                            name: reference.name,
                            token: reference.token,
                            icon: reference.icon,
                            render: renderCrossReferenceHint,
                            hint: (cm2, data, completion) => this.insertCrossReference(cm2, data, completion),
                        }));

                        callback({
                            list,
                            from: { line: cursor.line, ch: start },
                            to: { line: cursor.line, ch: cursor.ch },
                        });
                    })
                    .catch((error) => {
                        // A cancelled (superseded) request has nothing useful
                        // to report -- the request that aborted it already
                        // owns showing (or not showing) results, so leave the
                        // dropdown alone rather than flashing it to empty.
                        if (axios.isCancel(error)) return;

                        callback(null);
                    });
            },

            // Mirrors insertMention: inserts the token and marks it as a chip
            // immediately, in the same operation, rather than waiting for the
            // next debounced scan (which would also need a round trip to
            // resolve it first).
            insertCrossReference(cm, data, completion) {
                const from = completion.from || data.from;
                const to = completion.to || data.to;

                cm.focus();
                cm.replaceRange(completion.text, from, to, 'complete');

                const chipTo = { line: from.line, ch: from.ch + 1 + completion.token.length };

                this.crossReferenceCache[completion.token] = {
                    type: completion.type,
                    name: completion.name,
                    icon: completion.icon,
                };

                this.crossReferenceMarks.push({
                    token: completion.token,
                    mark: cm.markText(from, chipTo, {
                        replacedWith: createCrossReferenceWidget(completion),
                        atomic: true,
                        inclusiveLeft: false,
                        inclusiveRight: false,
                    }),
                });
            },

            // Scans the document for "#type-id" tokens (mirroring
            // CrossReferenceResolver's token format) and replaces resolvable
            // ones with a chip, same diffing/atomic/excludeLine approach as
            // decorateMentions. The one addition: resolving a token means
            // asking the server, so unresolved candidates are looked up in one
            // batched request and cached (including negative results) before
            // marks are created.
            decorateCrossReferences(cm, excludeLine) {
                const samePos = (a, b) => a.line === b.line && a.ch === b.ch;

                this.crossReferenceMarks = this.crossReferenceMarks.filter((entry) => {
                    const range = entry.mark.find();

                    if (!range || cm.getRange(range.from, range.to) !== `#${entry.token}`) {
                        entry.mark.clear();
                        return false;
                    }

                    return true;
                });

                const text = cm.getValue();
                const pattern = /(^|[^a-zA-Z0-9_#])#([a-z]+(?:-[a-z]+)*-\d+)(?=[^a-zA-Z0-9\-]|$)/g;

                const candidates = [];
                let match;
                while ((match = pattern.exec(text)) !== null) {
                    const token = match[2];
                    const atStart = match.index + match[1].length;
                    const from = cm.posFromIndex(atStart);
                    const to = cm.posFromIndex(atStart + 1 + token.length);

                    if (from.line === excludeLine) {
                        continue;
                    }

                    const alreadyMarked = this.crossReferenceMarks.some((entry) => {
                        const range = entry.mark.find();
                        return range && samePos(range.from, from) && samePos(range.to, to);
                    });

                    if (alreadyMarked) {
                        continue;
                    }

                    candidates.push({ token, from, to });
                }

                const applyCandidates = () => {
                    candidates.forEach(({ token, from, to }) => {
                        const reference = this.crossReferenceCache[token];

                        if (!reference) {
                            return;
                        }

                        this.crossReferenceMarks.push({
                            token,
                            mark: cm.markText(from, to, {
                                replacedWith: createCrossReferenceWidget(reference),
                                atomic: true,
                                inclusiveLeft: false,
                                inclusiveRight: false,
                            }),
                        });
                    });
                };

                const unresolvedTokens = [...new Set(candidates
                    .map((candidate) => candidate.token)
                    .filter((token) => !(token in this.crossReferenceCache)))];

                if (unresolvedTokens.length === 0) {
                    applyCandidates();
                    return;
                }

                axios.post('/cross-references/resolve', { tokens: unresolvedTokens })
                    .then((response) => {
                        unresolvedTokens.forEach((token) => {
                            this.crossReferenceCache[token] = response.data[token] ?? null;
                        });
                        applyCandidates();
                    })
                    .catch(() => {});
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
