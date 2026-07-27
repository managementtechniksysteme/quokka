<template>
    <div class="q-filter-search position-relative" ref="wrapper">
        <div class="q-filter-backdrop" :class="input_class" ref="backdrop" aria-hidden="true">
            <div class="q-filter-backdrop-scroll" ref="backdropScroll">
                <span
                    v-for="(part, index) in renderParts"
                    :key="index"
                    :ref="part.marker ? 'caretMarker' : undefined"
                    :class="part.cls"
                >{{ part.text }}</span>
            </div>
        </div>

        <input
            ref="input"
            type="text"
            :name="name"
            :class="['q-filter-input', input_class]"
            :placeholder="placeholder"
            autocomplete="off"
            :value="value"
            @input="onInput"
            @keydown="onKeydown"
            @keyup="onKeyup"
            @click="onCaretMove"
            @scroll="syncScroll"
            @blur="onBlur"
        />

        <ul class="dropdown-menu show q-filter-suggestions" ref="suggestionsList" v-if="suggestions.length" :style="{ left: caretLeft + 'px' }">
            <li
                v-for="(suggestion, index) in suggestions"
                :key="suggestion.id"
                :ref="index === highlightedIndex ? 'highlightedSuggestion' : undefined"
            >
                <button
                    type="button"
                    class="dropdown-item"
                    :class="{ 'q-filter-suggestion--highlighted': index === highlightedIndex }"
                    @mousedown.prevent="applySuggestion(suggestion)"
                >{{ suggestion.label }}</button>
            </li>
        </ul>
    </div>
</template>

<script>
    // Caret-aware syntax-highlighted overlay for the filter-query mini-language
    // (parsed server-side by app/Traits/FiltersSearch.php). Domain-independent:
    // all prefix/value knowledge comes from the `fields` prop (see
    // Task::filterKeyMetadata()); this component has no model-specific logic.
    //
    // Mechanism: a real <input> on top (transparent text, native caret only)
    // sits over a mirrored backdrop <div> that renders the same text as
    // colored spans. The backdrop's own render pass also splices in a
    // zero-width marker at the caret's character offset, so its measured
    // offsetLeft doubles as the dropdown's horizontal position -- one
    // mirrored element solves both coloring and caret-position measurement.
    //
    // Tokenizing/classifying is a simplified, cosmetic-only JS approximation
    // of the PHP parser: it never changes what the form actually submits
    // (still a plain name="search" input inside the existing GET form), so a
    // misclassification can at worst mis-color or omit a suggestion, never
    // change which records are returned.
    export default {
        name: 'FilterSearchInput',

        data() {
            return {
                value: this.initial_value,
                caretPos: 0,
                caretLeft: 0,
                activeTerm: { start: 0, end: 0, text: '' },
                renderParts: [],
                suggestions: [],
                highlightedIndex: 0,
                lookupAbortController: null,
                suppressNextKeyup: false,
            };
        },

        props: {
            name: {
                type: String,
                default() {
                    return 'search';
                }
            },
            initial_value: {
                type: String,
                default() {
                    return '';
                }
            },
            placeholder: {
                type: String,
                default() {
                    return '';
                }
            },
            input_class: {
                type: String,
                default() {
                    return '';
                }
            },
            model: {
                type: String,
                required: true,
            },
            fields: {
                type: Array,
                default() {
                    return [];
                }
            },
            suggestions_url: {
                type: String,
                required: true,
            },
        },

        mounted() {
            this.rebuildSegments();
        },

        methods: {
            // -- events -----------------------------------------------------

            onInput(event) {
                this.value = event.target.value;
                this.caretPos = event.target.selectionStart;
                this.rebuildDropdown();
                this.rebuildSegments();
                this.$nextTick(() => this.measureCaretLeft());
            },

            onCaretMove() {
                if (!this.$refs.input) {
                    return;
                }

                this.caretPos = this.$refs.input.selectionStart;
                this.rebuildDropdown();
                this.rebuildSegments();
                this.$nextTick(() => this.measureCaretLeft());
            },

            onBlur() {
                this.suggestions = [];
            },

            syncScroll() {
                // Scrolling happens on this inner element, not the outer
                // .q-filter-backdrop -- see the CSS comment on
                // .q-filter-backdrop-scroll for why that split matters
                // whenever the input has left/right padding reserved for
                // something else (e.g. the mobile leading search icon).
                if (this.$refs.backdropScroll && this.$refs.input) {
                    this.$refs.backdropScroll.scrollLeft = this.$refs.input.scrollLeft;
                }
            },

            onKeydown(event) {
                if (!this.suggestions.length) {
                    return;
                }

                // The matching keyup for whichever key we handle here would
                // otherwise immediately recompute and reopen the dropdown
                // from scratch (e.g. Escape clears suggestions, then its own
                // keyup fires onKeyup -> rebuildDropdown(), instantly undoing
                // the dismissal) -- suppress that one follow-up recompute.
                this.suppressNextKeyup = true;

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.suggestions.length - 1);
                    this.$nextTick(() => this.scrollHighlightedIntoView());
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
                    this.$nextTick(() => this.scrollHighlightedIntoView());
                } else if (event.key === 'Enter' || event.key === 'Tab') {
                    event.preventDefault();
                    this.applySuggestion(this.suggestions[this.highlightedIndex]);
                } else if (event.key === 'Escape') {
                    event.preventDefault();
                    this.suggestions = [];
                } else {
                    this.suppressNextKeyup = false;
                }
            },

            onKeyup(event) {
                if (this.suppressNextKeyup) {
                    this.suppressNextKeyup = false;
                    return;
                }

                this.onCaretMove();
            },

            // -- tokenizing / classification --------------------------------

            // Mirrors FiltersSearch.php's `preg_match_all('/"(?:\\.|[^\\"])*"|\S+/', ...)`:
            // a term is either a whole double-quoted run (only if the quote
            // opens right at that position) or a run of non-whitespace.
            tokenize(text) {
                const regex = /"(?:\\.|[^\\"])*"|\S+/g;
                const terms = [];
                let match;

                while ((match = regex.exec(text)) !== null) {
                    terms.push({ text: match[0], start: match.index, end: match.index + match[0].length });
                }

                // Backspacing through a quoted value naturally passes through an
                // "unterminated quote" state (closing quote removed) -- the regex
                // above then falls through to \S+, splitting the rest of the
                // string into separate broken terms at the next whitespace and
                // losing the active-term match entirely mid-edit. Since this is a
                // cosmetic JS approximation only (the real query isn't submitted
                // until the form does, at which point the quote is presumably
                // balanced again or the term is gone), merge everything from that
                // lone opening quote to the end of the string back into one term
                // so editing stays smooth through the transient unbalanced state.
                for (let i = 0; i < terms.length; i++) {
                    const term = terms[i];
                    const startsQuoted = term.text[0] === '"' || term.text[0] === "'";
                    const endsQuoted = term.text.length > 1 && /['"]$/.test(term.text);

                    if (startsQuoted && !endsQuoted) {
                        terms.splice(i, terms.length - i, {
                            text: text.slice(term.start),
                            start: term.start,
                            end: text.length,
                        });
                        break;
                    }
                }

                return terms;
            },

            findActiveTerm(caretPos, terms) {
                for (const term of terms) {
                    if (caretPos >= term.start && caretPos <= term.end) {
                        return term;
                    }
                }

                return { start: caretPos, end: caretPos, text: '' };
            },

            // Mirrors the server's exact order: strip surrounding quotes/apostrophes
            // first (trim($term, '\'"')), THEN check for a leading '!'.
            classify(rawTerm) {
                const unwrapped = rawTerm.replace(/^['"]+/, '').replace(/['"]+$/, '');
                const negated = unwrapped.startsWith('!');
                const content = negated ? unwrapped.slice(1) : unwrapped;

                for (const field of this.fields) {
                    if (!content.startsWith(field.prefix)) {
                        continue;
                    }

                    const candidateValue = content.slice(field.prefix.length);

                    if (field.kind === 'lookup') {
                        // even a bare/partial value fully matches server-side (the
                        // '(.*)' capture group matches any string, including empty)
                        return { negated, content, matchedField: field, valuePart: candidateValue, recognized: true };
                    }

                    // enum: bare prefix ("ist:") or a partial value being typed
                    // ("ist:n") both still belong to this field -- for the
                    // dropdown to keep filtering -- but only an EXACT complete
                    // value is one of the real literal filterKeys server-side,
                    // so only that gets colored as recognized.
                    const recognized = field.values.some((value) => value.value === candidateValue);

                    return { negated, content, matchedField: field, valuePart: candidateValue, recognized };
                }

                return { negated, content, matchedField: null, valuePart: '', recognized: false };
            },

            // -- dropdown -----------------------------------------------------

            rebuildDropdown() {
                const terms = this.tokenize(this.value);
                this.activeTerm = this.findActiveTerm(this.caretPos, terms);

                const info = this.classify(this.activeTerm.text);

                if (info.matchedField && info.matchedField.kind === 'enum') {
                    this.cancelLookup();

                    this.suggestions = info.matchedField.values
                        // aliases (e.g. 'nv' alongside 'nicht_verrechnet') stay in the
                        // data for recognition/coloring, just not listed a second time
                        .filter((value) => !value.duplicate)
                        .filter((value) => value.value.toLowerCase().startsWith(info.valuePart.toLowerCase()))
                        .map((value) => ({
                            id: info.matchedField.prefix + value.value,
                            label: value.label,
                            insertText: (info.negated ? '!' : '') + info.matchedField.prefix + value.value,
                            closeAfter: true,
                        }));

                    this.highlightedIndex = 0;
                    return;
                }

                if (info.matchedField && info.matchedField.kind === 'lookup') {
                    this.fetchLookupSuggestions(info.matchedField, info.valuePart, info.negated);
                    return;
                }

                this.cancelLookup();

                const typed = info.content.toLowerCase();

                this.suggestions = this.fields
                    // aliases (e.g. 'p:' alongside 'projekt:') stay in the data
                    // for recognition, just not listed a second time
                    .filter((field) => !field.duplicate)
                    .filter((field) => field.prefix.toLowerCase().startsWith(typed))
                    .map((field) => ({
                        id: field.prefix,
                        label: field.label,
                        insertText: (info.negated ? '!' : '') + field.prefix,
                        closeAfter: false,
                    }));

                this.highlightedIndex = 0;
            },

            fetchLookupSuggestions(field, term, negated) {
                if (term.length < 1) {
                    this.suggestions = [];
                    this.cancelLookup();
                    return;
                }

                // Cancel whatever's still in flight before starting the next one, so
                // only the latest keystroke's request can ever populate the dropdown
                // (same pattern as MarkdownEditor.vue's crossReferenceHint).
                this.lookupAbortController?.abort();
                this.lookupAbortController = new AbortController();

                axios.get(this.suggestions_url, {
                    params: { model: this.model, prefix: field.prefix, query: term },
                    signal: this.lookupAbortController.signal,
                })
                    .then((response) => {
                        this.suggestions = response.data.map((suggestedValue) => ({
                            id: field.prefix + suggestedValue,
                            label: suggestedValue,
                            insertText: this.buildLookupToken(field, suggestedValue, negated),
                            closeAfter: true,
                        }));
                        this.highlightedIndex = 0;

                        // This suggestion set lands well after onInput's own
                        // nextTick(measureCaretLeft) already ran, so its DOM
                        // width was never accounted for -- without remeasuring
                        // here, the dropdown keeps whatever (often narrower)
                        // clamp position was last computed and can render
                        // past the right edge once the real, wider content
                        // arrives.
                        this.$nextTick(() => this.measureCaretLeft());
                    })
                    .catch(() => {
                        // a cancelled or failed suggestion fetch shouldn't disrupt typing
                    });
            },

            cancelLookup() {
                this.lookupAbortController?.abort();
            },

            // Quotes the whole reconstructed token (negation + prefix + value) when
            // the value contains whitespace -- the server's quote-matching only
            // counts a '"' as opening a quoted term if it's the very first
            // character of that whitespace-delimited run, so quoting only the
            // value half (e.g. p:"Projekt Name") would silently split into two
            // terms server-side. Only quoting the entire token parses correctly.
            buildLookupToken(field, suggestedValue, negated) {
                const body = (negated ? '!' : '') + field.prefix + suggestedValue;

                return /\s/.test(body) ? `"${body}"` : body;
            },

            applySuggestion(suggestion) {
                const before = this.value.slice(0, this.activeTerm.start);
                const after = this.value.slice(this.activeTerm.end);
                const inserted = suggestion.insertText + (suggestion.closeAfter ? ' ' : '');

                this.value = before + inserted + after;

                const newCaret = this.activeTerm.start + inserted.length;

                this.$nextTick(() => {
                    this.$refs.input.focus();
                    this.$refs.input.setSelectionRange(newCaret, newCaret);
                    this.caretPos = newCaret;

                    // A final (closeAfter) selection lands the caret just past a
                    // trailing space -- that's a fresh "key position", so blindly
                    // rebuilding here would immediately reopen the dropdown with
                    // every available prefix instead of actually closing it.
                    // Continuing into a key's value stage (closeAfter: false)
                    // still needs the real rebuild to populate that stage.
                    if (suggestion.closeAfter) {
                        this.cancelLookup();
                        this.suggestions = [];
                    } else {
                        this.rebuildDropdown();
                    }

                    this.rebuildSegments();
                    this.$nextTick(() => {
                        // setSelectionRange() (a programmatic caret move) doesn't
                        // reliably trigger the browser's own "scroll caret into
                        // view" behavior the way organic typing does -- without
                        // this, selecting a suggestion while already scrolled to
                        // the edge leaves the newly-inserted text/caret entirely
                        // out of view.
                        this.scrollCaretIntoView();
                        this.measureCaretLeft();
                    });
                });
            },

            scrollCaretIntoView() {
                const input = this.$refs.input;
                const marker = (this.$refs.caretMarker || [])[0];

                if (!input || !marker) {
                    return;
                }

                // Reserve the input's own right padding as a margin, same as
                // organic typing already does natively -- without this, the
                // caret lands flush against the theoretical edge (clientWidth
                // includes the padding box), cutting the last character or two
                // uncomfortably close and pushing the caret past where the
                // padding should visually protect it.
                const rightPadding = parseFloat(getComputedStyle(input).paddingRight) || 0;
                const caretOffset = marker.offsetLeft;
                const visibleLeft = input.scrollLeft;
                const visibleRight = visibleLeft + input.clientWidth - rightPadding;

                if (caretOffset < visibleLeft) {
                    input.scrollLeft = caretOffset;
                } else if (caretOffset > visibleRight) {
                    input.scrollLeft = caretOffset - input.clientWidth + rightPadding;
                }

                this.syncScroll();
            },

            // Arrow-key navigation moves highlightedIndex without any native
            // browser scroll-following (unlike mouse hover/scroll, which the
            // .q-filter-suggestions overflow-y:auto container already handles
            // on its own) -- keep the highlighted row inside the visible
            // scrollable area the same way scrollCaretIntoView() does for the
            // input's own horizontal scroll.
            scrollHighlightedIntoView() {
                const list = this.$refs.suggestionsList;
                const item = (this.$refs.highlightedSuggestion || [])[0];

                if (!list || !item) {
                    return;
                }

                const itemTop = item.offsetTop;
                const itemBottom = itemTop + item.offsetHeight;
                const visibleTop = list.scrollTop;
                const visibleBottom = visibleTop + list.clientHeight;

                if (itemTop < visibleTop) {
                    list.scrollTop = itemTop;
                } else if (itemBottom > visibleBottom) {
                    list.scrollTop = itemBottom - list.clientHeight;
                }
            },

            // -- backdrop rendering / caret measurement ----------------------

            rebuildSegments() {
                const terms = this.tokenize(this.value);
                const parts = [];
                let cursor = 0;

                for (const term of terms) {
                    if (term.start > cursor) {
                        parts.push({ text: this.value.slice(cursor, term.start), cls: '' });
                    }

                    const raw = term.text;
                    const bang = raw.startsWith('!') ? raw[0] : '';
                    const rest = bang ? raw.slice(1) : raw;

                    if (bang) {
                        parts.push({ text: bang, cls: '' });
                    }

                    const info = this.classify(raw);

                    // Only the VALUE gets a color highlight (GitHub's own style) --
                    // the key/prefix and any quotes stay plain, same as free text.
                    // A recognized-but-empty value (bare "p:" while still typing)
                    // gets no highlight at all rather than a zero-width one.
                    if (info.recognized && info.valuePart.length > 0) {
                        const leadingQuote = /^['"]/.test(rest) ? 1 : 0;
                        const trailingQuote = rest.length > leadingQuote && /['"]$/.test(rest) ? 1 : 0;
                        const keyEnd = leadingQuote + info.matchedField.prefix.length;
                        const valueEnd = rest.length - trailingQuote;

                        if (leadingQuote) {
                            parts.push({ text: rest.slice(0, leadingQuote), cls: '' });
                        }

                        parts.push({ text: rest.slice(leadingQuote, keyEnd), cls: '' });
                        parts.push({
                            text: rest.slice(keyEnd, valueEnd),
                            cls: info.negated ? 'q-filter-value--negated' : 'q-filter-value--recognized',
                        });

                        if (trailingQuote) {
                            parts.push({ text: rest.slice(valueEnd), cls: '' });
                        }
                    } else {
                        parts.push({ text: rest, cls: '' });
                    }

                    cursor = term.end;
                }

                if (cursor < this.value.length) {
                    parts.push({ text: this.value.slice(cursor), cls: '' });
                }

                this.renderParts = this.spliceCaretMarker(parts, this.caretPos);
            },

            spliceCaretMarker(parts, caretPos) {
                const result = [];
                let cursor = 0;
                let inserted = false;

                for (const part of parts) {
                    const partStart = cursor;
                    const partEnd = cursor + part.text.length;

                    if (!inserted && caretPos >= partStart && caretPos <= partEnd) {
                        const offset = caretPos - partStart;

                        if (offset > 0) {
                            result.push({ text: part.text.slice(0, offset), cls: part.cls });
                        }

                        result.push({ text: '', cls: '', marker: true });

                        if (offset < part.text.length) {
                            result.push({ text: part.text.slice(offset), cls: part.cls });
                        }

                        inserted = true;
                    } else {
                        result.push(part);
                    }

                    cursor = partEnd;
                }

                if (!inserted) {
                    result.push({ text: '', cls: '', marker: true });
                }

                return result;
            },

            measureCaretLeft() {
                const marker = (this.$refs.caretMarker || [])[0];

                if (!marker || !this.$refs.input) {
                    return;
                }

                let left = marker.offsetLeft - this.$refs.input.scrollLeft;

                const list = this.$refs.suggestionsList;
                const wrapper = this.$refs.wrapper;

                if (list && wrapper) {
                    // Keep the dropdown from overflowing past the input's own
                    // right edge, and also never let it run off the actual
                    // browser viewport even if the wrapper itself is narrower
                    // than the dropdown's content (e.g. a long project name on
                    // a narrow mobile input).
                    const maxLeftInWrapper = Math.max(0, wrapper.clientWidth - list.offsetWidth);
                    const wrapperLeft = wrapper.getBoundingClientRect().left;
                    const maxLeftInViewport = Math.max(0, window.innerWidth - wrapperLeft - list.offsetWidth - 8);

                    left = Math.min(left, maxLeftInWrapper, maxLeftInViewport);
                }

                this.caretLeft = Math.max(0, left);
            },
        },
    };
</script>
