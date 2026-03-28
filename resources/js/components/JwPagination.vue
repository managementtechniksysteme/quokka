<template>
    <ul v-if="totalPages > 1" class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <a class="page-link" href="#" @click.prevent="setPage(1)">{{ labels.first }}</a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <a class="page-link" href="#" @click.prevent="setPage(currentPage - 1)">{{ labels.previous }}</a>
        </li>
        <li v-for="page in visiblePages" :key="page" class="page-item" :class="{ active: currentPage === page }">
            <a class="page-link" href="#" @click.prevent="setPage(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <a class="page-link" href="#" @click.prevent="setPage(currentPage + 1)">{{ labels.next }}</a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <a class="page-link" href="#" @click.prevent="setPage(totalPages)">{{ labels.last }}</a>
        </li>
    </ul>
</template>

<script>
export default {
    name: 'JwPagination',

    props: {
        items: { type: Array, required: true },
        pageSize: { type: Number, default: 10 },
        initialPage: { type: Number, default: 1 },
        labels: {
            type: Object,
            default: () => ({ first: '<<', last: '>>', previous: '<', next: '>' }),
        },
    },

    emits: ['changePage'],

    data() {
        return {
            currentPage: this.initialPage,
        };
    },

    computed: {
        totalPages() {
            return Math.ceil(this.items.length / this.pageSize);
        },

        visiblePages() {
            const total = this.totalPages;
            const current = this.currentPage;
            const maxVisible = 10;

            let start = Math.max(1, current - Math.floor(maxVisible / 2));
            let end = Math.min(total, start + maxVisible - 1);

            if (end - start + 1 < maxVisible) {
                start = Math.max(1, end - maxVisible + 1);
            }

            const pages = [];
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },
    },

    watch: {
        items() {
            if (this.currentPage > this.totalPages) {
                this.currentPage = Math.max(1, this.totalPages);
            }
            this.emitPage();
        },

        initialPage(val) {
            if (val !== this.currentPage) {
                this.currentPage = val;
                this.emitPage();
            }
        },
    },

    mounted() {
        this.emitPage();
    },

    methods: {
        setPage(page) {
            if (page < 1 || page > this.totalPages || page === this.currentPage) return;
            this.currentPage = page;
            this.emitPage();
        },

        emitPage() {
            const start = (this.currentPage - 1) * this.pageSize;
            this.$emit('changePage', this.items.slice(start, start + this.pageSize));
        },
    },
};
</script>
