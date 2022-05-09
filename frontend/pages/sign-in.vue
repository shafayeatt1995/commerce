<template>
	<div class="container mb-3">
		<div class="row">
			<div class="col-md-6 bg-light">
				<div class="container h-100 d-flex flex-column">
					<div class="row my-auto">
						<div class="col-11 col-md-10 mx-auto">
							<h1 class="mb-4">Welcome, We are glad to see you again!</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 d-flex flex-column align-items-center">
				<div class="container pt-5">
					<div class="row gx-0">
						<div class="col-11 col-md-10 mx-auto">
							<p class="text-end">Not a member? <router-link :to="{name: 'sign-up'}">Sign up now</router-link>
							</p>
						</div>
					</div>
				</div>
				<div class="container my-auto py-5">
					<div class="row gx-0">
						<div class="col-11 col-md-10 col-lg-9 col-xl-8 mx-auto">
							<h3 class="text-9 mb-5">Sign in</h3>
							<form @submit.prevent="sign_in">
								<div class="mb-3">
									<label for="email" class="form-label">Email Address</label>
									<input type="email" class="form-control bg-light border-light" id="email" placeholder="Enter Your Email" v-model="credential.email" required>
								</div>
								<div class="mb-3">
									<label for="password" class="form-label">Password</label>
									<input type="password" class="form-control bg-light border-light" id="password" placeholder="Enter Password" v-model="credential.password" required>
								</div>
								<div class="row mt-4">
									<div class="col">
										<div class="form-check">
											<input id="remember-me" name="remember" class="form-check-input" type="checkbox">
											<label class="form-check-label" for="remember-me">Remember Me</label>
										</div>
									</div>
									<div class="col text-end">
										<router-link :to="{name: 'forgot-password'}">Forgot Password ?</router-link>
									</div>
								</div>
								<div class="d-grid my-4">
									<button class="btn btn-dark shadow-none" type="submit">Sign in</button>
								</div>
							</form>
							<div class="d-flex align-items-center my-4">
								<hr class="flex-grow-1">
								<span class="mx-2 text-2 text-muted fw-300">Or continue with</span>
								<hr class="flex-grow-1">
							</div>
							<div class="row gx-3">
								<div class="col-6 d-grid">
									<button type="button" class="btn btn-light btn-sm shadow-none border">
										<span class="me-2">
											<i>
												<icon :icon="['fab', 'google']"></icon>
											</i>
										</span>Google
									</button>
								</div>
								<div class="col-6 d-grid">
									<button type="button" class="btn btn-light btn-sm shadow-none border">
										<span class="me-2">
											<i>
												<icon :icon="['fab', 'facebook-f']"></icon>
											</i>
										</span>Facebook
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		head() {
			return {
				title: `Sign in - ${this.app_name}`,
			};
		},

		data() {
			return {
				click: true,
				loading: false,
				credential: {
					email: "",
					password: "",
				},
				errors: {},
			};
		},
		methods: {
			sign_in() {
				if (this.click) {
					this.click = false;
					this.$axios.post("").then(
						(response) => {
							this.click = true;
						},
						(error) => {
							$nuxt.$emit("error", error);
							this.click = true;
						}
					);
				}
			},
		},
	};
</script>