<template>
	<div>
		<div class="main-sidebar sidebar-style-2" :class="!sidebar ? 'hide' : ''">
			<aside id="sidebar-wrapper">
				<div class="sidebar-brand">
					<nuxt-link :to="{name: 'dashboard'}">
						<img :src="`${url}images/logo.svg`" alt="logo" width="100" class="img-fluid">
					</nuxt-link>
				</div>
				<ul class="sidebar-menu mt-3">
					<li>
						<nuxt-link :to="{name: 'dashboard'}" class="nav-link">
							<i>
								<icon :icon="['fas', 'gauge-high']"></icon>
							</i>
							<span>Dashboard</span>
						</nuxt-link>
					</li>
					<li>
						<nuxt-link :to="{name: 'dashboard-brand'}" class="nav-link">
							<i>
								<icon :icon="['fas', 'tags']"></icon>
							</i>
							<span>Brand</span>
						</nuxt-link>
					</li>
					<li class="sidebar-dropdown" :class="active === 'category' || route.substring(11, 19) === 'category' ? 'dropdown-active' : ''">
						<a href="#" class="nav-link has-dropdown" @click.prevent="active = active !== 'category' ? 'category' : ''">
							<i>
								<icon :icon="['far', 'rectangle-list']"></icon>
							</i>
							<span>Category</span>
						</a>
						<transition name="slide" mode="out-in">
							<ul class="sidebar-dropdown-menu" v-if="active === 'category' || route.substring(11, 19) === 'category'">
								<li>
									<nuxt-link :to="{name: 'dashboard-category'}">Category</nuxt-link>
								</li>
								<li>
									<nuxt-link :to="{name: 'dashboard-category-sub'}">Sub Category</nuxt-link>
								</li>
							</ul>
						</transition>
					</li>
					<li>
						<nuxt-link :to="{name: 'dashboard-size'}" class="nav-link">
							<i>
								<icon :icon="['fas', 'tags']"></icon>
							</i>
							<span>Size</span>
						</nuxt-link>
					</li>
				</ul>
			</aside>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				route: "",
				active: "",
			};
		},

		created() {
			this.route = this.$route.path;
		},

		watch: {
			$route(to, from) {
				this.route = to.path;
			},
		},
	};
</script>