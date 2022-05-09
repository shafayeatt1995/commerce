<template>
	<div>
		<div class="site-preloader" :class="animation ? 'hide':''" v-if="!hide">
			<div class="preloader-left"></div>
			<div class="preloader-right"></div>
			<h1 class="preloader" :class="preloader ? 'active' : ''">
				<span class="letters-container">
					<span class="letters letters-left">Loading</span>
					<span class="letters bang">!</span>
				</span>
				<span class="circle circle-white"></span>
				<span class="circle circle-dark"></span>
				<span class="circle circle-container"><span class="circle circle-dark-dashed"></span></span>
			</h1>
		</div>
		<Dashboard v-if="dashboard" />
		<Frontend v-else />
	</div>
</template>

<script>
	export default {
		data() {
			return {
				dashboard: "",
				preloader: false,
				animation: false,
				hide: false,
			};
		},

		created() {
			this.dashboard = this.$route.path.substring(0, 10) === "/dashboard";

			//Trigger Success Toast Message
			this.$nuxt.$on("success", (success) => {
				this.$toast.success(success);
			});

			// Trigger Error Toast Message
			this.$nuxt.$on("error", (error) => {
				this.$toast.error(
					error.response.data.errors
						? error.response.data.errors[
								Object.keys(error.response.data.errors)[0]
						  ][0]
						: error.response.data.message
						? error.response.data.message
						: "Something Wrong! Please try Again"
				);
			});

			// Trigger Error Toast Message
			this.$nuxt.$on("customError", (message) => {
				this.$toast.error(message);
			});

			//Trigger Info Toast Message
			this.$nuxt.$on("warning", (warning) => {
				this.$toast.warning(warning);
			});
		},

		mounted() {
			this.preloader = true;

			window.addEventListener("load", (event) => {
				setTimeout(() => {
					this.preloader = false;
					setTimeout(() => (this.animation = true), 400);
					setTimeout(() => (this.hide = true), 500);
				}, 500);
			});
		},
	};
</script>

<style scoped>
	@import url("https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap");
	@import url("https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&amp;family=Roboto:wght@300;400;500;700&amp;display=swap");

	* {
		overflow: hidden;
	}

	h1.preloader {
		font-weight: 900;
		font-size: 4.5em;
		color: #fff;
		width: 3em;
		height: 3em;
	}

	.preloader .letters-container {
		position: absolute;
		color: #fff !important;
		left: 0;
		right: 0;
		margin: auto;
		top: 0;
		bottom: 0;
		height: 1em;
		text-align: center;
		transform: scale(0);
		font-size: 60px;
		transition: transform 0.3s ease-out 0s;
		z-index: 3;
		height: 85px;
	}

	.preloader.active .letters-container {
		transform: scale(1);
		transition: transform 0.3s ease-in 0.3s;
	}

	.preloader .letters {
		font-family: "Permanent Marker", cursive !important;
		position: relative;
		z-index: 2;
		display: inline-block;
		line-height: 0.7em;
		right: -0.12em;
		top: -0.2em;
		padding: 8px 0;
	}

	.preloader .bang {
		font-size: 1.4em;
		top: auto;
		left: -0.06em;
	}

	.preloader .circle {
		position: absolute;
		left: 0;
		right: 0;
		margin: auto;
		top: 0;
		bottom: 0;
	}

	.preloader .circle-white {
		width: 3em;
		height: 3em;
		border: 2px dashed #fff;
		border-radius: 2em;
		animation: scale 0.5s linear;
		opacity: 0;
	}

	@keyframes scale {
		0% {
			transform: scale(0);
			opacity: 1;
		}
		40% {
			transform: scale(0);
			opacity: 1;
		}

		100% {
			opacity: 0;
			transform: scale(3);
		}
	}

	.preloader .circle-dark {
		width: 0;
		height: 0;
		background-color: #6b4fe8;
		border-radius: 50%;
		z-index: 1;
		transition: 0.3s ease-out 0.3s;
	}

	.preloader.active .circle-dark {
		width: 300px;
		height: 300px;
		transition: 0.3s ease-in 0s;
	}

	.preloader.active .circle-dark-dashed {
		width: 310px;
		height: 310px;
		border: 2px dashed #6b4fe8;
	}

	.preloader .circle-dark-dashed {
		border-radius: 50%;
		background-color: transparent;
		border: 0;
		width: 0;
		height: 0;
		transition: 0.3s;
		transition-delay: 0.1s;
		animation: rotate 15s linear infinite;
	}

	@keyframes rotate {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}

	.site-preloader {
		overflow: hidden;
		position: fixed;
		top: 0;
		left: 0;
		height: 100vh;
		width: 100vw;
		z-index: 999;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.preloader-right {
		position: absolute;
		background: #fff;
		height: 100vh;
		width: 50vw;
		right: 0;
		top: 0;
		z-index: -1;
		transition: 0.5s;
	}

	.preloader-left {
		position: absolute;
		background: #fff;
		height: 100vh;
		width: 50vw;
		left: 0;
		top: 0;
		z-index: -1;
		transition: 0.5s;
	}

	.site-preloader.hide .preloader-left {
		left: -50vw;
	}

	.site-preloader.hide .preloader-right {
		right: -50vw;
	}

	h1.preloader.hide {
		transform: scale(10);
		opacity: 0 !important;
	}
</style>
