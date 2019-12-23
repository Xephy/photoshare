<template>
    <nav class="navbar">
        <RouterLink class="navbar__brand" to="/">
            PhotoShare
        </RouterLink>
        <div class="navbar__menu">
            <div v-if="isLogin" class="navbar__item">
                <button class="button" @click="showForm = ! showForm">
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
        <PhotoForm v-model="showForm"/>
    </nav>
</template>

<script>
    import PhotoForm from "./PhotoForm";

    export default {
        name: "Navbar",
        components: {PhotoForm},
        comments: {
            PhotoForm
        },
        data() {
            return {
                showForm: false
            }
        },
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
