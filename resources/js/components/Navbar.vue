<template>
	<nav class="navbar">
        <RouterLink class="navbar__brand" to="/">
            PhotoShare
        </RouterLink>
        <div class="navbar__menu">
            <div class="navbar__item">
                <button class="button">
                    <i class="icon ion-md-add"></i>
                    Submit a Photo
                </button>
            </div>
            <span class="navbar__item">
                username
            </span>
        </div>
        <div class="navbar__item">
            <RouterLink class="button button--like" to="/login">
                Login / Register
            </RouterLink>
        </div>
    </nav>
</template>

<script>
	export default {
		name: "Navbar"
	}
</script>

<style scoped>

</style>
