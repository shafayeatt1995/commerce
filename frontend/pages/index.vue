<template>
	<div>
		<h1>Anik</h1>
	</div>
</template>
<script>
	export default {
		name: "index",
		auth: false,
		head() {
			return {
				title: "Home - " + this.app_name,
			};
		},
	};
</script>