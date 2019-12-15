<template>
    <div>
        <header>
            <Navbar />
        </header>
        <main>
            <div class="container">
                <RouterView />
            </div>
        </main>
        <Footer />
    </div>
</template>

<script>
	import Navbar from "./components/Navbar";
    import Footer from "./components/Footer";

    export default {
		name: "App",
        components: {Footer, Navbar}
    }
</script>

<style scoped>

</style>
