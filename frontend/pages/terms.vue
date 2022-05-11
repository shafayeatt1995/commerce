<template>
	<div>
		<h1>Terms</h1>
	</div>
</template>
<script>
	export default {
		name: "terms",
		auth: false,
		head() {
			return {
				title: "Terms - " + this.app_name,
			};
		},
	};
</script>