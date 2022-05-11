<template>
	<div id="app">
		<div class="main-wrapper main-wrapper-1">
			<DashboardHeader v-if="auth_check" />
			<div class="main-content" :class="auth_check && sidebar ? 'active':''">
				<section class="section">
					<Nuxt />
				</section>
			</div>
			<DashboardFooter v-if="auth_check" />
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
						href: `${process.env.URL}dashboard/css/bootstrap.min.css`,
					},
					{
						rel: "stylesheet",
						href: `${process.env.URL}dashboard/css/components.css`,
					},
					{
						rel: "stylesheet",
						href: `${process.env.URL}dashboard/css/style.css`,
					},
				],
			};
		},

		data() {
			return {
				sidebar: true,
			};
		},

		created() {
			this.$nuxt.$on("toggleSidebar", (data) => {
				this.sidebar = data;
			});
		},
	};
</script>