<template>
	<div class="container mb-3">
		<div class="row">
			<div class="col-md-6 bg-light">
				<div class="container h-100 d-flex flex-column">
					<div class="row my-auto">
						<div class="col-11 col-md-10 mx-auto">
							<h1 class="mb-4">Join the largest E-commerce store in the world.</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 d-flex flex-column align-items-center">
				<div class="container pt-5">
					<div class="row gx-0">
						<div class="col-11 col-md-10 mx-auto">
							<p class="text-end">Already a member? <router-link :to="{name: 'sign-in'}">Sign in now</router-link>
							</p>
						</div>
					</div>
				</div>
				<div class="container my-auto py-5">
					<div class="row gx-0">
						<div class="col-11 col-md-10 col-lg-9 col-xl-8 mx-auto">
							<h3 class="text-9 mb-5">Sign up</h3>
							<form @submit.prevent="sign_in">
								<div class="mb-3">
									<label for="name" class="form-label">Full Name</label>
									<input type="text" class="form-control bg-light border-light" id="name" placeholder="Enter Your Name" v-model="credential.name" required>
									<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
								</div>
								<div class="mb-3">
									<label for="email" class="form-label">Email Address</label>
									<input type="email" class="form-control bg-light border-light" id="email" placeholder="Enter Your Email" v-model="credential.email" required>
									<p class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</p>
								</div>
								<div class="mb-3">
									<label for="password" class="form-label">Password</label>
									<input type="password" class="form-control bg-light border-light" id="password" placeholder="Enter Password" v-model="credential.password" required>
									<p class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</p>
								</div>
								<div class="mb-3">
									<label for="password_confirmation" class="form-label">Confirrrm Password</label>
									<input type="password" class="form-control bg-light" :class="credential.password_confirmation.length > 0 ? credential.password === credential.password_confirmation ? 'border-success' : 'border-danger' : 'border-light'" id="password_confirmation" placeholder="Re Enter Password" v-model="credential.password_confirmation" required>
									<p :class="credential.password === credential.password_confirmation ? 'valid-feedback' : 'invalid-feedback'" v-if="credential.password_confirmation.length > 0">
										<span v-if="credential.password === credential.password_confirmation">
											<i>
												<icon :icon="['fas', 'check']"></icon>
											</i>
											Confirm password matched
										</span>
										<span v-else>
											<i>
												<icon :icon="['fas', 'xmark']"></icon>
											</i>
											Confirm password not matched
										</span>
									</p>
								</div>
								<div class="row mt-4">
									<div class="col">
										<div class="form-check">
											<input id="agree" name="agree" class="form-check-input" type="checkbox" v-model="agree" required>
											<label class="form-check-label" for="agree">I agree to the <router-link :to="{name: 'terms'}">Terms</router-link> and <router-link :to="{name: 'privacy-policy'}">Privacy Policy</router-link>.</label>
										</div>
									</div>
								</div>
								<div class="d-grid my-4">
									<button class="btn btn-dark shadow-none" type="submit" :disabled="loading">
										<span v-if="loading">Checking...</span>
										<span v-else>Sign up</span>
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "sign-up",
		auth: "guest",
		head() {
			return {
				title: `Sign Up - ${this.app_name}`,
			};
		},

		data() {
			return {
				click: true,
				loading: false,
				credential: {
					name: "",
					email: "",
					password: "",
					password_confirmation: "",
				},
				agree: true,
				errors: {},
			};
		},

		methods: {
			sign_in() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.error = false;
					this.$axios.post("register", this.credential).then(
						() => {
							this.$auth
								.loginWith("general", { data: this.credential })
								.then(
									() => {
										this.$router.push({ name: "index" });
									},
									() => {
										this.click = true;
										this.loading = false;
									}
								);
							this.click = true;
							this.loading = false;
						},
						(error) => {
							this.errors = error.response.data.errors;
							this.click = true;
							this.loading = false;
						}
					);
				}
			},
		},
	};
</script>