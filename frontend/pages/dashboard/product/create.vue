<template>
	<div>
		<div class="section-header d-flex justify-content-between">
			<h1>Create Product</h1>
		</div>

		<div class="section-body">
			<div class="row">
				<div class="col-lg-8">
					<div class="card card-primary">
						<div class="card-body">
							<div class="form-group">
								<label for="name">Product Name</label>
								<input type="text" class="form-control" id="name" v-model="form.name" placeholder="Product Name">
								<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
							</div>
							<div class="form-group">
								<label for="unit">Unit</label>
								<input type="text" class="form-control" id="unit" v-model="form.unit" placeholder="Unit (e. g. Gram, liter, Pics etc)">
								<p class="invalid-feedback" v-if="errors.unit">{{errors.unit[0]}}</p>
							</div>
							<div class="form-group">
								<label for="min_purchase">Minimum Purchase</label>
								<input type="number" class="form-control" id="min_purchase" v-model="form.min_purchase">
								<span class="small">Customer need to purchase this minimum quantity for this product. Minimum should be 1.</span>
								<p class="invalid-feedback" v-if="errors.min_purchase">{{errors.min_purchase[0]}}</p>
							</div>
							<div class="form-group">
								<label for="max_purchase">Maximum Purchase</label>
								<input type="number" class="form-control" id="max_purchase" v-model="form.max_purchase">
								<span class="small">Customer will be able to purchase this maximum quantity for this product. Default 0 for unlimited.</span>
								<p class="invalid-feedback" v-if="errors.max_purchase">{{errors.max_purchase[0]}}</p>
							</div>
							<div class="image-form">
								<div class="image-frame">
									<label for="logo" class="image-frame-content">
										<i>
											<icon :icon="['fas', 'cloud-arrow-up']"></icon>
										</i>
										<span>Select and upload product thumbnail</span>
									</label>
								</div>
								<label for="logo" class="image-select"><i>
										<icon :icon="['fas', 'image']"></icon>
									</i>
									Chose Thumbnail
								</label>
								<input type="file" class="form-control" id="logo" accept="image/*" @change="image($event)">
								<p class="invalid-feedback" v-if="errors.logo">{{errors.logo[0]}}</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card card-primary">
						<div class="card-body">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "create-product",
		auth: true,
		middleware: "admin",
		head() {
			return {
				title: `Create Product - ${this.app_name}`,
			};
		},

		data() {
			return {
				click: true,
				form: {
					name: "",
					unit: "",
				},
				errors: {},
			};
		},

		methods: {
			// show Selected image
			image(event) {
				if (event.target.files.length > 0) {
					let file = event.target.files[0];
					if (file.size < 2097153) {
						let reader = new FileReader();
						reader.onloadend = () => {
							this.name = reader.result;
						};
						reader.readAsDataURL(file);
						this.form.logo = file;
					} else {
						$nuxt.$emit("error", "Maximum Image Size 2 MB");
					}
				}
			},
		},

		created() {},

		beforeDestroy() {},
	};
</script>