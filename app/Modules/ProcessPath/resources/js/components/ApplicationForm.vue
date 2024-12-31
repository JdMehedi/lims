<template>

    <component :is="setAppComponent" :encoded_app_id="encoded_ref_id"></component>

</template>

<script>
//Vue 3 is having a special function to define these async functions
import { defineAsyncComponent } from "vue";

export default {
    props: {
        openMode: '',
        module_name: '',
        encoded_ref_id: '',
    },
    data() {
        return {
            appComponentName: '',
        }
    },
    computed: {
        setAppComponent() {
            return defineAsyncComponent(() => import(`../../../../${this.module_name}/resources/js/components/${this.appComponentName}.vue`))
        }
    },
    created() {
        if (this.openMode == 'add' || this.openMode == 'edit') {
            this.appComponentName = this.module_name + 'Form';
        } else {
            this.appComponentName = this.module_name + 'View';
        }
    },
    methods: {}
}
</script>

<style>

</style>