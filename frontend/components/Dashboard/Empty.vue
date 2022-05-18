<template>
	<div class="text-center">
		<img :data-src="`${url}images/empty.svg`" :alt="message" class="img-fluid max-h350 w-auto" v-lazy-load />
		<h4>{{message}}</h4>
	</div>
</template>
<script>
	export default {
		props: {
			message: "",
		},
	};
</script>