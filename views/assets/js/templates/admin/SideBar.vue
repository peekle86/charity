<template>
    <aside class="innerAside " :class="{'innerAside__nav_active':isNavActive}">
        <span class="innerAside__openBar" @click="ctrNav">☰</span>
        <span class="innerAside__closeBar" @click="ctrNav">X</span>
        <div class="innerAside__nav ">
            <nav>
                <ul>
                    <router-link to="/dashboard" tag="li" active-class="innerAside__active" exact>Dashboard</router-link>
                    <router-link to="/orgs" tag="li" active-class="innerAside__active" exact>Organizations</router-link>
                    <router-link to="/cats" tag="li" active-class="innerAside__active" exact>Categories</router-link>
                    <router-link to="/setup" tag="li" active-class="innerAside__active" exact>Settings</router-link>
                    <router-link to="/lng" tag="li" active-class="innerAside__active" exact>Languages</router-link>
                    <router-link to="/usr" tag="li" active-class="innerAside__active" exact>Users</router-link>
                    <router-link to="/pgs" tag="li" active-class="innerAside__active" exact>Pages</router-link>
                    <router-link to="/ads" tag="li" active-class="innerAside__active" exact>Ads</router-link>
                    <router-link to="/prtns" tag="li" active-class="innerAside__active" exact>Partners</router-link>
                    <li v-on:click="logout()">Logout</li>
                </ul>
            </nav>
        </div>
    </aside>
</template>
<script>

export default {
    name: "",
    props: {
        navs: Object,
    },
    data() {
        return {
            isNavActive: false,
            pro: 1,
            
        };
    },
    methods: {
        ctrNav() {
            this.isNavActive ? this.isNavActive = false : this.isNavActive = true
        },
        setUserId() {
            //this.$store.commit('setUserId');
        },
        clicked (e) {
            console.log(this.pro);
            if (this.pro === 0) {
                e.preventDefault();
            }
        },
        clickable () {
            if (this.pro === 0) {
                return false;
            }
            return true;
        },
        logout () {
            Vue.axios.get('/logout').then(res => {
                this.$router.go(this.$router.currentRoute);
            })
        },
    },
    mounted() {
        Vue.axios.get('/currentuser').then(res => {
                Vue.axios
                    .get('/users/'+res.data.id)
                    .then(res => {
                        this.pro = res.data.pro
                    });
            }
        );
    },
    components: {
    
    
    
    }
};
</script>
