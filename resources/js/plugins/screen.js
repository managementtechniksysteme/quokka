import { reactive } from 'vue';

const breakpoints = {
    sm: 576,
    md: 768,
    lg: 992,
    xl: 1200,
    xxl: 1400,
};

export default {
    install(app) {
        const screen = reactive({
            sm: false,
            md: false,
            lg: false,
            xl: false,
            xxl: false,
            width: 0,
            height: 0,
        });

        function update() {
            const w = window.innerWidth;
            screen.sm = w >= breakpoints.sm;
            screen.md = w >= breakpoints.md;
            screen.lg = w >= breakpoints.lg;
            screen.xl = w >= breakpoints.xl;
            screen.xxl = w >= breakpoints.xxl;
            screen.width = w;
            screen.height = window.innerHeight;
        }

        update();
        window.addEventListener('resize', update);

        app.config.globalProperties.$screen = screen;
    },
};
