<template>
	<div id="app">
		<div class="main-wrapper main-wrapper-1">
			<DashboardHeader v-if="auth_check" />
			<div class="main-content" :class="auth_check ? !sidebar ? 'full':'' : 'full'">
				<section class="section">
					<Nuxt />
				</section>
			</div>
			<transition name="fade">
				<div class="sidebar-overlay" v-if="!sidebar && auth_check" @click="toggle_sidebar"></div>
			</transition>
			<DashboardPhoto v-if="photo_modal" />
		</div>
	</div>
</template>
<script>
	export default {
		head() {
			return {
				link: [
					{
						rel: "stylesheet",
						href: `${process.env.URL}dashboard/bootstrap.min.css`,
					},
					{
						rel: "stylesheet",
						href: `${process.env.URL}dashboard/components.css`,
					},
					{
						rel: "stylesheet",
						href: `${process.env.URL}dashboard/style.css`,
					},
				],
			};
		},
		methods: {
			toggle_sidebar() {
				this.$store.dispatch("sidebar");
			},
		},
	};
</script>