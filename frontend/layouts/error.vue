<template>
	<!--Not found area start-->
	<div class="error mb-5" :class="dashboard ? 'mt-50' : ''">
		<div class="container text-center">
			<img :src="`${url}images/404.svg`" alt="404 not found" class="img-fluid h-350" />
			<h5 class="mt-3">We can't seem to find the page you're looking.</h5>
			<nuxt-link :to="{name: 'dashboard'}" class="btn btn-primary" v-if="dashboard">Go To Dashboard</nuxt-link>
			<nuxt-link :to="{name: 'index'}" class="as-btn mask-btn" v-else>
				<span class="btn-text">Go To Home</span>
				<span class="btn-text-mask">Go To Home</span>
			</nuxt-link>
		</div>
	</div>
	<!--Not found area end-->
</template>
<script>
	export default {
		head() {
			return {
				title: "Not Found - " + this.app_name,
			};
		},
		data() {
			return {
				dashboard: false,
			};
		},

		created() {
			this.dashboard = this.$route.path.substring(0, 10) === "/dashboard";
		},
	};
</script>
