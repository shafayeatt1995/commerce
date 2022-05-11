<template>
	<div>
		<h1>This is Forgot password page</h1>
	</div>
</template>
<script>
	export default {
		name: "forgot-password",
		auth: "guest",
		head() {
			return {
				title: `Forgot Password - ${this.app_name}`,
			};
		},
	};
</script>