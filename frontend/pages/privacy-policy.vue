<template>
	<div>
		<h1>privacy policy</h1>
	</div>
</template>
<script>
	export default {
		name: "privacy-policy",
		auth: false,
		head() {
			return {
				title: "Privacy Policy - " + this.app_name,
			};
		},
	};
</script>