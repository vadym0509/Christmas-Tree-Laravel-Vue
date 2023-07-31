export const isFF = {
    methods: {
        ff() {
            let pathArray = window.location.pathname.split('/');
            return pathArray.includes('festive-floristry');
        }
    }
};
