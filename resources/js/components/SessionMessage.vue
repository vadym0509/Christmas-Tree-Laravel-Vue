<template>

</template>
<script>
    export default {
        props: {
            toast: {
                type: Object,
                default: () => {}
            }
        },
        mounted() {
            this.$toastr.defaultclassNames = ['animated', 'zoomInUp'];
            this.$toastr.defaultStyle = {'opacity': 1};
            this.$toastr.defaultTimeout = 7000;
            this.$toastr.defaultPosition = 'toast-top-center',
            this.$toastr.defaultProgressBar = false;

            if (this.toast.title) {
                this.$toastr.s(this.toast);
            }
        }
    }
</script>
