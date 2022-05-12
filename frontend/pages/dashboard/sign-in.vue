<template>
	<div class="container mt-5">
		<div class="row">
			<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
				<div class="login-brand">
					<img :src="`${url}images/logo.svg`" alt="logo" width="100" class="img-fluid">
				</div>

				<div class="card card-primary">
					<div class="card-header">
						<h4>Login</h4>
					</div>

					<div class="card-body">
						<form class="needs-validation" @submit.prevent="sign_in">
							<div class="form-group">
								<label for="email">Email</label>
								<input id="email" type="email" class="form-control" v-model="credential.email" required>
							</div>

							<div class="form-group">
								<div class="d-block">
									<label for="password" class="control-label">Password</label>
								</div>
								<input id="password" type="password" class="form-control" v-model="credential.password" required>
							</div>
							<p class="invalid-feedback show" v-if="error">Email or password not matched</p>

							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="remember-me">
									<label class="custom-control-label" for="remember-me">Remember Me</label>
								</div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block" :disabled="loading">
									<span v-if="loading">Checking...</span>
									<span v-else>Sign in</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "dashboard-sign-in",
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
					this.$auth
						.loginWith("dashboard", { data: this.credential })
						.then(
							() => {
								this.$router.push({ name: "dashboard" });
							},
							() => {
								this.error = true;
								this.click = true;
								this.loading = false;
							}
						);
				}
			},
		},
	};
</script>