<template>
	<div>
		<h1>{{sidebar}}</h1>
	</div>
</template>
<script>
	export default {
		name: "dashboard",
		auth: true,
		middleware: "admin",
		head() {
			return {
				title: `Dashboard - ${this.app_name}`,
			};
		},
	};
</script>