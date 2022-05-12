<template>
	<div>
		<div class="navbar-bg"></div>
		<nav class="navbar navbar-expand-lg main-navbar" :class="!sidebar ? 'full' : ''">
			<form class="form-inline">
				<ul class="navbar-nav mr-3">
					<li>
						<a href="#" class="nav-link nav-link-lg" @click.prevent="toggle_sidebar">
							<i>
								<icon :icon="['fas', 'bars']"></icon>
							</i>
						</a>
					</li>
					<li class="pc-only">
						<nuxt-link class="btn btn-outline-light text-dark-hover text-white mx-2" :to="{name: 'index'}" target="_blank">
							<i>
								<icon :icon="['fas', 'globe']"></icon>
							</i> Brows Website
						</nuxt-link>
					</li>
				</ul>
			</form>
			<div class="mobile-only">
				<img :src="`${url}images/logo.svg`" alt="logo" width="100" class="img-fluid">
			</div>
			<ul class="navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="nav-link dropdown-toggle nav-link-lg nav-link-user" @click.prevent="dropdown = !dropdown">
						<img alt="image" :src="`${url}images/user.png`" class="rounded-circle mr-1">
						<div class="d-sm-none d-lg-inline-block">Hi, {{user.name}}</div>
					</a>
					<transition name="fade">
						<div class="dropdown-menu dropdown-menu-right show" v-if="dropdown">
							<a href="features-profile.html" class="dropdown-item has-icon">
								<i>
									<icon :icon="['fas', 'circle-user']"></icon>
								</i> Profile
							</a>
							<a href="features-settings.html" class="dropdown-item has-icon">
								<i>
									<icon :icon="['fas', 'gears']"></icon>
								</i> Settings
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item has-icon text-danger" @click.prevent="sign_out">
								<i>
									<icon :icon="['fas', 'arrow-right-from-bracket']"></icon>
								</i> Logout
							</a>
						</div>
					</transition>
				</li>
			</ul>
		</nav>
		<DashboardSidebar />
	</div>
</template>
<script>
	export default {
		data() {
			return {
				dropdown: false,
			};
		},

		methods: {
			sign_out() {
				this.$auth.logout("dashboard").then(() => {
					this.$router.push({ name: "dashboard-sign-in" });
				});
			},

			toggle_sidebar() {
				this.$store.dispatch("sidebar");
			},
		},
	};
</script>