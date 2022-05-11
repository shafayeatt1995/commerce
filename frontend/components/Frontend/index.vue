<template>
	<div>
		<FrontendHeader />
		<Nuxt />
		<FrontendFooter />
	</div>
</template>
<script>
	export default {
		head() {
			return {
				link: [
					{
						rel: "stylesheet",
						href: `${process.env.URL}frontend/bootstrap.min.css`,
					},
					{
						rel: "stylesheet",
						href: `${process.env.URL}frontend/style.css`,
					},
				],
				// script: [
				// 	{
				// 		async: true,
				// 		type: "text/javascript",
				// 		src: `https://www.googletagmanager.com/gtag/js?id=${this.google_analytics}`,
				// 	},
				// ],
			};
		},

		mounted() {
			// window.dataLayer = window.dataLayer || [];
			// function gtag() {
			// 	dataLayer.push(arguments);
			// }
			// gtag("js", new Date());
			// gtag("config", `${this.google_analytics}`);
		},
	};
</script>