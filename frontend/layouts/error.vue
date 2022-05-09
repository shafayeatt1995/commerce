<template>
	<Error :dashboard="dashboard" />
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
