export default {
    data() {
        return { _mqLg: null, isDesktopGrid: true };
    },
    created() {
        this._mqLg = window.matchMedia('(min-width: 992px)');
        this.isDesktopGrid = this._mqLg.matches;
        this._onMq = e => { this.isDesktopGrid = e.matches; };
        this._mqLg.addEventListener('change', this._onMq);
    },
    beforeUnmount() {
        this._mqLg?.removeEventListener('change', this._onMq);
    },
};
