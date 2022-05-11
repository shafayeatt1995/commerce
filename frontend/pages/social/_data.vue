<template>
	<p>Verifying your identification</p>
</template>
<script>
	export default {
		name: "social-check",
		auth: "guest",

		head() {
			return {
				title: `Verifying - ${this.app_name}`,
			};
		},

		data() {
			return {
				credential: {
					data: this.$route.params.data,
				},
			};
		},

		methods: {
			sign_in() {
				this.$auth.loginWith("social", { data: this.credential }).then(
					() => {
						this.$router.push({ name: "index" });
					},
					(error) => {
						this.$router.push({ name: "sign-in" });
						$nuxt.$emit("error", error);
					}
				);
			},
		},

		created() {
			if (process.client) {
				this.sign_in();
			}
		},
	};
</script>