<template>
	<footer class="footer-wrapper bg-smoke footer-layout2">
		<div class="container">
			<div class="footer-top">
				<div class="row align-items-center justify-content-between">
					<div class="col-lg-5">
						<h2 class="h2">Subscribe to Our Newsletter!</h2>
						<p class="mb-n2">Authoritatively morph 24/7 potentialities with error-free partnerships.
							Objectively engineer multimedia.</p>
					</div>
					<div class="col-lg-6">
						<form class="newsletter-form"><input class="form-control" type="email" placeholder="Enter Your Email" required=""> <button type="submit" class="as-btn mask-btn"><span class="btn-text">Subscribe</span><span class="btn-text-mask">Subscribe</span></button></form>
					</div>
				</div>
			</div>
		</div>
		<div class="widget-area">
			<div class="container">
				<div class="row justify-content-start justify-content-xl-between">
					<div class="col-xl-auto col-md-4 col-sm-6">
						<div class="widget footer-widget">
							<div class="as-widget-about">
								<div class="about-logo">
									<a href="index.html">
										<img :src="`${url}images/footer-logo.svg`" alt="logo">
									</a>
								</div>
								<p class="about-text">Progressively optimize effective ideas before turnkey process
									improvements. Proactively disseminate.Distinctively revolutionize functional.</p>
								<div class="footer-social">
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
									<a target="_blank" href="https://pinterest.com/">
										<i>
											<icon :icon="['fab', 'pinterest-p']"></icon>
										</i>
									</a>
									<a target="_blank" href="https://linkedin.com/">
										<i>
											<icon :icon="['fab', 'linkedin-in']"></icon>
										</i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-auto col-md-4 col-sm-6">
						<div class="widget footer-widget widget_nav_menu">
							<h4 class="widget_title">Support</h4>
							<div class="menu-all-pages-container">
								<ul class="menu">
									<li>
										<a href="contact.html">
											<i>
												<icon :icon="['fas', 'chevron-down']"></icon>
											</i>
											Help</a>
									</li>
									<li>
										<a href="contact.html">

											<i>
												<icon :icon="['fas', 'chevron-down']"></icon>
											</i>
											Contact Us</a>
									</li>
									<li>
										<a href="contact.html">
											<i>
												<icon :icon="['fas', 'chevron-down']"></icon>
											</i>
											Feedback</a>
									</li>
									<li>
										<a href="contact.html">
											<i>
												<icon :icon="['fas', 'chevron-down']"></icon>
											</i>
											Customer Service</a>
									</li>
									<li>
										<a href="contact.html">
											<i>
												<icon :icon="['fas', 'chevron-down']"></icon>
											</i>
											Store Location</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-lg-auto col-sm-6">
						<div class="widget footer-widget">
							<h4 class="widget_title">Opening Time</h4>
							<div class="as-widget-schedule">
								<table class="footer-table">
									<tbody>
										<tr>
											<th>Mon - Tue:</th>
											<td>8AM - 10PM</td>
										</tr>
										<tr>
											<th>Wed:</th>
											<td>8AM - 7PM</td>
										</tr>
										<tr>
											<th>Fri:</th>
											<td>7AM - 12PM</td>
										</tr>
										<tr>
											<th>Sat:</th>
											<td>9AM - 8PM</td>
										</tr>
										<tr>
											<th>Sun:</th>
											<td>Closed</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-xl-auto col-md-4 col-sm-6">
						<div class="widget footer-widget">
							<h4 class="widget_title">Contact Us</h4>
							<div class="as-widget-contact">
								<p class="footer-info">
									<i>
										<icon :icon="['fas', 'location-dot']"></icon>
									</i>2751 S Parker Rd, Aurora, CO
									80014, United States
								</p>
								<p class="footer-info">
									<i>
										<icon :icon="['fas', 'phone']"></icon>
									</i><a class="text-inherit" href="tel:+18008660286">+18008660286</a>
								</p>
								<p class="footer-info">
									<i>
										<icon :icon="['fas', 'envelope']"></icon>
									</i>
									<a class="text-inherit" href="mailto:info@aytor.com">info@aytor.com</a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
				<div class="row align-items-center justify-content-between">
					<div class="col-md-auto text-center text-md-start">
						<p class="copyright">© 2022 All Rights Reserved By <a href="https://codecanyon.net/user/expresshub/" target="_blank">Express Hub</a></p>
					</div>
					<div class="col-md-auto d-none d-md-block">
						<img :src="`${url}images/payment.png`" alt="payments">
					</div>
				</div>
			</div>
		</div>
	</footer>
</template>
<script>
	export default {};
</script>