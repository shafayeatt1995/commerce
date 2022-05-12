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
							<p class="text-end">Not a member? <nuxt-link :to="{name: 'sign-up'}">Sign up now</nuxt-link>
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
								<p class="invalid-feedback" v-if="error">Email or password not matched</p>
								<div class="row mt-4">
									<div class="col">
										<div class="form-check">
											<input id="remember-me" name="remember" class="form-check-input" type="checkbox">
											<label class="form-check-label" for="remember-me">Remember Me</label>
										</div>
									</div>
									<div class="col text-end">
										<nuxt-link :to="{name: 'forgot-password'}">Forgot Password ?</nuxt-link>
									</div>
								</div>
								<div class="d-grid my-4">
									<button class="btn btn-dark shadow-none" type="submit" :disabled="loading">
										<span v-if="loading">Checking...</span>
										<span v-else>Sign in</span>
									</button>
								</div>
							</form>
							<div class="d-flex align-items-center my-4">
								<hr class="flex-grow-1">
								<span class="mx-2 text-2 text-muted fw-300">Or continue with</span>
								<hr class="flex-grow-1">
							</div>
							<div class="row gx-3">
								<div class="col-6 d-grid">
									<button type="button" class="btn btn-light btn-sm shadow-none border" @click="social_sign_in('google')">
										<span class="me-2">
											<i>
												<icon :icon="['fab', 'google']"></icon>
											</i>
										</span>Google
									</button>
								</div>
								<div class="col-6 d-grid">
									<button type="button" class="btn btn-light btn-sm shadow-none border" @click="social_sign_in('facebook')">
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
		name: "sign-in",
		auth: "guest",
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
				error: false,
			};
		},

		methods: {
			sign_in() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.error = false;
					this.$auth.loginWith("general", { data: this.credential }).then(
						() => {
							this.$router.push({ name: "index" });
						},
						() => {
							this.error = true;
							this.click = true;
							this.loading = false;
						}
					);
				}
			},

			social_sign_in(social) {
				window.location.href = `${this.url}api/sign-in/${social}`;
			},
		},
	};
</script>