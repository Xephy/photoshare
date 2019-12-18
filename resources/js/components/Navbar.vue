<template>
    <nav class="navbar">
        <RouterLink class="navbar__brand" to="/">
            PhotoShare
        </RouterLink>
        <div class="navbar__menu">
            <div v-if="isLogin" class="navbar__item">
                <button class="button">
                    <i class="icon ion-md-add"></i>
                    Submit a Photo
                </button>
            </div>
            <span v-if="isLogin" class="navbar__item">
                {{ username }}
            </span>
            <div v-else class="navbar__item">
                <RouterLink class="button button--like" to="/login">
                    Login / Register
                </RouterLink>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "Navbar",
        computed: {
            isLogin() {
                return this.$store.getters["auth/check"];
            },
            username() {
		        return this.$store.getters["auth/username"];
            }
        }
    }
</script>

<style scoped>

</style>
