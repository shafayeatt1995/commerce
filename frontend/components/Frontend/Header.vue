<template>
	<header class="as-header header-layout1">
		<div class="header-top d-none d-lg-block">
			<div class="container">
				<div class="row align-items-center justify-content-between">
					<div class="col-auto">
						<p class="header-notice">
							<i>
								<icon :icon="['far', 'paper-plane']"></icon>
							</i> FREE SHIPPING world wide for all orders over <span class="text-theme">$150</span>
						</p>
					</div>
					<div class="col-auto">
						<div class="header-links">
							<ul>
								<li>
									<router-link :to="{name: 'sign-in'}">Sign in</router-link>
								</li>
								<li>
									<router-link :to="{name: 'sign-up'}">Sign up</router-link>
								</li>
								<li><a href="#">Track Your Order</a></li>
								<li>
									<a class="dropdown-toggle" href="#" @click="language = !language">Language</a>
									<transition name="fade" mode="out-in">
										<ul class="dropdown-menu show" v-if="language">
											<li>
												<a href="#">German</a>
												<a href="#">French</a>
												<a href="#">Italian</a>
												<a href="#">Latvian</a>
												<a href="#">Spanish</a>
												<a href="#">Greek</a>
											</li>
										</ul>
									</transition>
								</li>
								<li>
									<a class="dropdown-toggle" href="#" @click="currency = !currency">Currency</a>
									<ul class="dropdown-menu show" v-if="currency">
										<li><a href="#">AED</a></li>
										<li><a href="#">BDT</a></li>
										<li><a href="#">ERN</a></li>
										<li><a href="#">EGP</a></li>
										<li><a href="#">EUR</a></li>
										<li><a href="#">GBP</a></li>
										<li><a href="#">USD</a></li>
									</ul>
								</li>
								<li>
									<div class="header-social">
										<a target="_blank" href="https://facebook.com/">
											<i>
												<icon :icon="['fab', 'facebook-f']"></icon>
											</i>
										</a>
										<a target="_blank" href="https://twitter.com/">
											<i>
												<icon :icon="['fab', 'twitter']"></icon>
											</i>
										</a>
										<a target="_blank" href="https://instagram.com/">
											<i>
												<icon :icon="['fab', 'instagram']"></icon>
											</i>
										</a>
										<a target="_blank" href="https://pinterest.com/">
											<i>
												<icon :icon="['fab', 'pinterest-p']"></icon>
											</i>
										</a>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="sticky-wrapper">
			<div class="sticky-active">
				<div class="menu-area">
					<div class="container">
						<div class="row gx-20 align-items-center justify-content-between">
							<div class="col-auto">
								<div class="header-logo py-3 py-lg-0">
									<router-link :to="{name: 'index'}">
										<img :src="`${url}images/logo.svg`" alt="logo">
									</router-link>
								</div>
							</div>
							<div class="col-auto">
								<nav class="main-menu d-none d-lg-inline-block">
									<ul>
										<li class="menu-item-has-children">
											<a href="#">Home
												<i>
													<icon :icon="['fas', 'chevron-down']"></icon>
												</i>
											</a>
											<ul class="sub-menu">
												<li><a href="index.html">Home One</a></li>
												<li><a href="index-2.html">Home Two</a></li>
												<li><a href="index-3.html">Home Three</a></li>
												<li><a href="index-4.html">Home Four</a></li>
											</ul>
										</li>
										<li><a href="about.html">About</a></li>
										<li class="menu-item-has-children mega-menu-wrap">
											<a href="#">Pages
												<i>
													<icon :icon="['fas', 'chevron-down']"></icon>
												</i>
											</a>
											<ul class="mega-menu">
												<li><a href="shop.html">Pagelist 1</a>
													<ul>
														<li><a href="index.html">Home One</a></li>
														<li><a href="index-2.html">Home Two</a></li>
														<li><a href="index-3.html">Home Three</a></li>
														<li><a href="index-4.html">Home Four</a></li>
													</ul>
												</li>
												<li><a href="#">Pagelist 2</a>
													<ul>
														<li><a href="about.html">About</a></li>
														<li><a href="blog.html">Blog</a></li>
														<li><a href="blog-2.html">Blog Two</a></li>
														<li><a href="blog-3.html">Blog Three</a></li>
													</ul>
												</li>
												<li><a href="#">Pagelist 3</a>
													<ul>
														<li><a href="blog-details.html">Blog Details</a></li>
														<li><a href="shop.html">Shop</a></li>
														<li><a href="shop-details.html">Shop Details</a></li>
														<li><a href="cart.html">Cart Page</a></li>
													</ul>
												</li>
												<li><a href="#">Pagelist 4</a>
													<ul>
														<li><a href="wishlist.html">Wishlist</a></li>
														<li><a href="checkout.html">Checkout</a></li>
														<li><a href="contact.html">Contact Us</a></li>
														<li><a href="error.html">Error Page</a></li>
													</ul>
												</li>
											</ul>
										</li>
										<li class="menu-item-has-children">
											<a href="shop.html">Shop
												<i>
													<icon :icon="['fas', 'chevron-down']"></icon>
												</i>
											</a>
											<ul class="sub-menu">
												<li><a href="shop.html">Shop</a></li>
												<li><a href="shop-details.html">Shop Details</a></li>
												<li><a href="cart.html">Cart Page</a></li>
												<li><a href="compare.html">Compare</a></li>
												<li><a href="checkout.html">Checkout</a></li>
												<li><a href="wishlist.html">Wishlist</a></li>
											</ul>
										</li>
										<li class="menu-item-has-children">
											<a href="blog.html">Blog
												<i>
													<icon :icon="['fas', 'chevron-down']"></icon>
												</i>
											</a>
											<ul class="sub-menu">
												<li><a href="blog.html">Blog</a></li>
												<li><a href="blog-2.html">Blog Two</a></li>
												<li><a href="blog-3.html">Blog Three</a></li>
												<li><a href="blog-details.html">Blog Details</a></li>
											</ul>
										</li>
										<li><a href="contact.html">Contact</a></li>
									</ul>
								</nav>
								<button type="button" class="as-menu-toggle d-inline-block d-lg-none">
									<i>
										<icon :icon="['fas', 'bars']"></icon>
									</i>
								</button>
							</div>
							<div class="col-auto d-none d-xl-block">
								<div class="header-button">
									<button type="button" class="searchBoxToggler" @click="search = !search">
										<i>
											<icon :icon="['fas', 'magnifying-glass']"></icon>
										</i>
									</button>
									<div class="popup-search-box d-none d-lg-block" :class="search ? 'show' : ''">
										<button class="searchClose border-theme text-theme" @click="search = !search">
											<i>
												<icon :icon="['fas', 'xmark']"></icon>
											</i>
										</button>
										<form action="#">
											<input type="text" class="border-theme" placeholder="What are you looking for">
											<button type="submit">
												<i>
													<icon :icon="['fas', 'magnifying-glass']"></icon>
												</i>
											</button>
										</form>
									</div>
									<a href="shop.html">
										<i>
											<icon :icon="['far', 'user']"></icon>
										</i>
									</a>
									<a href="wishlist.html">
										<i>
											<icon :icon="['far', 'heart']"></icon>
										</i>
									</a>
									<a href="cart.html" class="sideMenuToggler has-badge">
										<i>
											<icon :icon="['fas', 'basket-shopping']"></icon>
										</i><span class="badge">4</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
</template>
<script>
	export default {
		data() {
			return {
				language: false,
				currency: false,
				search: false,
			};
		},
	};
</script>