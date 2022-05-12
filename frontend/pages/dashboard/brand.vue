<template>
	<div>
		<div class="section-header d-flex justify-content-between">
			<h1>All Brands</h1>
			<button type="button" class="btn btn-primary" @click="edit_mode = !edit_mode">Add Brand</button>
		</div>

		<div class="section-body">
			<div class="row">
				<div class="col-lg-7">
					<div class="card card-primary">
						<div class="card-header">
							<h4>Brands</h4>
							<form class="card-header-form" @submit.prevent="search">
								<div class="input-group">
									<input type="text" class="form-control" v-model="search_option.keyword" placeholder="Search" @keyup="instantSearch">
									<div class="input-group-btn">
										<button class="btn btn-primary btn-icon">
											<i>
												<icon :icon="['fas', 'magnifying-glass']"></icon>
											</i>
										</button>
									</div>
								</div>
							</form>
						</div>
						<div class="card-body">
							<table class="table table-hover">
								<thead>
									<tr class="text-center">
										<th scope="col">#</th>
										<th scope="col">Name</th>
										<th scope="col">Logo</th>
										<th scope="col">Options</th>
									</tr>
								</thead>
								<tbody v-if="brands.data && brands.data.length >= 1">
									<tr v-for="(brand, key) in brands.data" :key="brand.data">
										<th class="text-center">
											<Key :data="brands" :index="key" />
										</th>
										<td class="text-center">{{brand.name}}</td>
										<td class="text-center">
											<img :src="url + brand.logo" :alt="brand.name" class="img-fluid py-2 max-h100px">
										</td>
										<td class="text-center">
											<div class="d-flex justify-content-center">
												<button type="button" class="btn btn-outline-primary btn-sm mx-1" @click="edit_brand(brand)">
													<i>
														<icon :icon="['fas', 'pen-to-square']"></icon>
													</i>
												</button>
												<button type="button" class="btn btn-outline-danger btn-sm mx-1" @click="delete_brand(brand.id)">
													<i>
														<icon :icon="['far', 'trash-can']"></icon>
													</i>
												</button>
											</div>
										</td>
									</tr>
								</tbody>
								<tbody v-else>
									<td colspan="4" class="pt-3">
										<h2 class="text-center">No brand found</h2>
									</td>
								</tbody>
							</table>
							<pagination :data="brands" @pagination-change-page="get_results" class="justify-content-center mt-3 paginate"></pagination>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="card" :class="edit_mode ? 'card-warning' : 'card-primary'">
						<div class="card-header">
							<h4 v-if="edit_mode">Edit Brand</h4>
							<h4 v-else>Create Brand</h4>
						</div>
						<div class="card-body">
							<form @submit.prevent="edit_mode ? update_brand() : create_brand()">
								<div class="form-group">
									<label for="name">Brand Name</label>
									<input type="text" class="form-control" id="name" v-model="form.name" required>
									<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
								</div>
								<div class="image-form">
									<div class="image-frame" v-if="edit_mode">
										<img :src="preview" class="img-fluid max-h250" alt="logo" v-if="form.logo">
										<img :src="url + old_logo" class="img-fluid max-h250" alt="logo" v-else>
									</div>
									<div class="image-frame" v-else>
										<img :src="preview" class="img-fluid max-h250" alt="logo" v-if="form.logo">
										<label for="logo" class="image-frame-content" v-else>
											<i>
												<icon :icon="['fas', 'cloud-arrow-up']"></icon>
											</i>
											<span>Select and upload your logo</span>
										</label>
									</div>
									<label for="logo" class="image-select">
										Chose Logo
									</label>
									<input type="file" class="form-control" id="logo" accept="image/*" @change="image($event)">
									<p class="invalid-feedback" v-if="errors.logo">{{errors.logo[0]}}</p>
								</div>
								<div class="text-right mt-4">
									<button class="btn btn-warning ml-2" type="reset" @click="reset">Reset</button>
									<button type="submit" class="btn btn-primary" v-if="edit_mode">
										<transition name="fade" mode="out-in">
											<span v-if="loading">Loading</span>
											<span v-else>Update Brand</span>
										</transition>
									</button>
									<button type="submit" class="btn btn-primary" v-else>
										<transition name="fade" mode="out-in">
											<span v-if="loading">Loading</span>
											<span v-else>Create Brand</span>
										</transition>
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
		name: "brand",
		auth: true,
		middleware: "admin",
		head() {
			return {
				title: `Brand - ${this.app_name}`,
			};
		},

		data() {
			return {
				click: true,
				loading: false,
				brands: {},
				search_option: {
					keyword: "",
					colum: "name",
				},
				edit_mode: false,
				errors: {},
				preview: "",
				edit_id: "",
				old_logo: "",
				form: {
					name: "",
					logo: "",
				},
			};
		},

		methods: {
			// Get Brand
			get_brands() {
				this.$axios.post("brand", this.search_option).then(
					(response) => {
						this.brands = response.data.brands;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			get_results(page = 1) {
				this.$axios
					.post(`brand?page=${page}`, this.search_option)
					.then((response) => {
						this.brands = response.data.brands;
					});
			},

			// Reset Brand form
			reset() {
				this.form.name = "";
				this.form.logo = "";
				this.preview = "";
				this.edit_id = "";
				this.errors = {};
				this.edit_mode = false;
			},

			// show Selected image
			image(event) {
				if (event.target.files.length > 0) {
					let file = event.target.files[0];
					let reader = new FileReader();
					reader.onloadend = () => {
						this.preview = reader.result;
					};
					reader.readAsDataURL(file);
					this.form.logo = file;
				}
			},

			//Create new Brand
			create_brand() {
				if (this.click) {
					this.click = false;
					this.error = {};
					const config = {
						headers: { "content-type": "multipart/form-data" },
					};

					let formData = new FormData();
					formData.append("name", this.form.name);
					formData.append("logo", this.form.logo);
					this.$axios.post("create-brand", formData, config).then(
						(response) => {
							$nuxt.$emit("trigger_brand");
							$nuxt.$emit("trigger_reset");
							$nuxt.$emit("success", response.data);
							this.click = true;
						},
						(error) => {
							this.errors = error.response.data.errors;
							this.click = true;
						}
					);
				}
			},

			// Edit Brand
			edit_brand(brand) {
				$nuxt.$emit("trigger_reset");
				this.edit_mode = true;
				this.form.name = brand.name;
				this.old_logo = brand.logo;
				this.edit_id = brand.id;
			},

			//Update new Brand
			update_brand() {
				if (this.click) {
					this.click = false;
					this.error = {};
					const config = {
						headers: { "content-type": "multipart/form-data" },
					};

					let formData = new FormData();
					formData.append("name", this.form.name);
					formData.append("logo", this.form.logo);
					this.$axios
						.post(`update-brand/${this.edit_id}`, formData, config)
						.then(
							(response) => {
								$nuxt.$emit("trigger_brand");
								$nuxt.$emit("trigger_reset");
								$nuxt.$emit("success", response.data.message);
								this.click = true;
							},
							(error) => {
								this.errors = error.response.data.errors;
								this.click = true;
							}
						);
				}
			},

			// Delete Brand
			delete_brand(id) {
				if (this.click) {
					this.click = false;
					this.$swal
						.fire({
							title: "Are you sure?",
							text: "You won't be able to revert this!",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#6777ef",
							cancelButtonColor: "#fc544b",
							confirmButtonText: "Yes, delete it!",
						})
						.then((result) => {
							if (result.isConfirmed) {
								let list = id ? [id] : this.select;
								this.$axios.post(`delete-brand/${id}`).then(
									(response) => {
										$nuxt.$emit("trigger_brand");
										$nuxt.$emit(
											"success",
											response.data.message
										);
										this.click = true;
									},
									(error) => {
										$nuxt.$emit("error", error);
										this.click = true;
									}
								);
							} else {
								this.click = true;
							}
						});
				}
			},

			// Search brand
			search() {
				if (this.click) {
					this.click = false;
					this.$axios.post("brand", this.search_option).then(
						(response) => {
							this.brands = response.data.brands;
							this.click = true;
						},
						(error) => {
							$nuxt.$emit("error", error);
							this.click = true;
						}
					);
				}
			},
			instantSearch() {
				if (this.click) {
					this.click = false;
					setTimeout(() => {
						this.$axios.post("brand", this.search_option).then(
							(response) => {
								this.brands = response.data.brands;
								this.click = true;
							},
							(error) => {
								$nuxt.$emit("error", error);
								this.click = true;
							}
						);
					}, 500);
				}
			},
		},

		created() {
			this.get_brands();
			this.$nuxt.$on("trigger_brand", () => {
				this.get_brands();
			});
			this.$nuxt.$on("trigger_reset", () => {
				this.reset();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("trigger_brand");
			this.$nuxt.$off("trigger_reset");
		},
	};
</script>