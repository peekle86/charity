<template>
    <div class="innerHeader">
        <div class="innerHeader__ctr">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-7">
                    
                    </div>
                    <div class="col-xl-3 offset-xl-2 offset-lg-2   col-lg-3 d-flex justify-content-end ">
                        <button v-on:click="logout()">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name: "Header",
    data: () => ({
    
    }),
    methods: {
        logout () {
            Vue.axios.get('/logout').then(res => {
                this.$router.go(this.$router.currentRoute);
            })
        },
    },
    mounted() {

    }
};
</script>
