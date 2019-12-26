<template>
    <div>
        <header>
            <Navbar/>
        </header>
        <main>
            <div class="container">
                <Message/>
                <RouterView/>
            </div>
        </main>
        <Footer/>
    </div>
</template>

<script>
    import Message from "./components/Message";
    import Navbar from "./components/Navbar";
    import Footer from "./components/Footer";
    import {INTERNAL_SERVER_ERROR, NOT_FOUND, UNAUTHORIZED} from "./util";

    export default {
        name: "App",
        components: {Message, Footer, Navbar},
        computed: {
            errorCode() {
                return this.$store.state.error.code;
            }
        },
        watch: {
            errorCode: {
                async handler(val) {
                    if (val === INTERNAL_SERVER_ERROR) {
                        this.$router.push("/500");
                    } else if (val === UNAUTHORIZED) {
                        await axios.get('/api/refresh-token');
                        this.$store.commit('auth/setUser', null);
                        this.$router.push('/login');
                    } else if (val === NOT_FOUND) {
                        this.$route.push('/not-found');
                    }
                },
                immediate: true
            }
        },
        $route() {
            this.$store.commit("error/setCode", null);
        }
    }
</script>

<style scoped>

</style>
