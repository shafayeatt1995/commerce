<template>
	<div>
		<div class="section-header d-flex justify-content-between">
			<h1>All Brands</h1>
			<button type="button" class="btn btn-primary" @click="reset">Add Brand</button>
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
							<table class="table table-hover table-responsive-lg">
								<thead>
									<tr class="text-center">
										<th scope="col">#</th>
										<th scope="col">Name</th>
										<th scope="col">Logo</th>
										<th scope="col">Options</th>
									</tr>
								</thead>
								<tbody v-if="loading">
									<td colspan="4" class="pt-4">
										<h2 class="d-flex justify-content-center">
											<Spinner />
										</h2>
									</td>
								</tbody>
								<tbody v-else-if="brands.data && brands.data.length >= 1">
									<tr v-for="(brand, key) in brands.data" :key="brand.id">
										<th class="text-center">
											<Key :data="brands" :index="key" />
										</th>
										<td class="text-center">{{brand.name}}</td>
										<td class="text-center">
											<img :data-src="url + brand.logo.photo" :alt="brand.name" class="img-fluid py-2 max-h100px" v-lazy-load>
										</td>
										<td class="text-center">
											<div class="d-flex justify-content-center">
												<button type="button" class="btn btn-outline-primary btn-sm mx-1 px-2" @click="edit_brand(brand)">
													<i>
														<icon :icon="['fas', 'pen-to-square']"></icon>
													</i>
												</button>
												<button type="button" class="btn btn-outline-danger btn-sm mx-1 px-2" @click="delete_brand(brand.id)">
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
										<DashboardEmpty message="No brand found" />
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
								<div class="custom-file pointer">
									<span class="custom-file-label" @click="toggle_photo_modal">Select Brand Logo</span>
									<p class="invalid-feedback mt-5" v-if="errors.logo">{{errors.logo[0]}}</p>
								</div>
								<div class="gallery gallery-md mt-2" v-if="select">
									<div class="gallery-item" :style="`background-image: url(${url + preview.photo});`" v-for="preview in select" :key="preview.id">
										<i @click="remove_image">
											<icon :icon="['fas', 'xmark']"></icon>
										</i>
									</div>
								</div>
								<div class="text-right mt-4">
									<button class="btn btn-warning ml-2" type="reset" @click="reset">Reset</button>
									<button type="submit" class="btn btn-primary" v-if="edit_mode" :disabled="waiting">
										<transition name="fade" mode="out-in">
											<span v-if="waiting">Loading</span>
											<span v-else>Update Brand</span>
										</transition>
									</button>
									<button type="submit" class="btn btn-primary" :disabled="waiting" v-else>
										<transition name="fade" mode="out-in">
											<span v-if="waiting">Loading</span>
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
				waiting: false,
				brands: {},
				search_option: {
					keyword: "",
					colum: "name",
				},
				edit_mode: false,
				errors: {},
				edit_id: "",
				form: {
					name: "",
					logo: "",
				},
				select: [],
			};
		},

		methods: {
			// Get Brand
			get_results(page = 1) {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios.post(`brand?page=${page}`, this.search_option).then(
						(response) => {
							this.click = true;
							this.brands = response.data.brands;
							this.loading = false;
						},
						(error) => {
							this.click = true;
							$nuxt.$emit("error", error);
							this.loading = false;
						}
					);
				}
			},

			//Create new Brand
			create_brand() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};
					this.$axios.post("create-brand", this.form).then(
						(response) => {
							this.click = true;
							$nuxt.$emit("trigger_brand");
							$nuxt.$emit("trigger_reset");
							$nuxt.$emit("success", response.data.message);
							this.waiting = false;
						},
						(error) => {
							this.click = true;
							this.errors = error.response.data.errors;
							this.waiting = false;
						}
					);
				}
			},

			// Edit Brand
			edit_brand(brand) {
				$nuxt.$emit("trigger_reset");
				this.edit_mode = true;
				this.form.name = brand.name;
				this.form.logo = brand.logo.id;
				this.edit_id = brand.id;
				this.select = [brand.logo];
			},

			//Update new Brand
			update_brand() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};
					this.$axios
						.post(`update-brand/${this.edit_id}`, this.form)
						.then(
							(response) => {
								this.click = true;
								$nuxt.$emit("trigger_brand");
								$nuxt.$emit("trigger_reset");
								$nuxt.$emit("success", response.data.message);
								this.waiting = false;
							},
							(error) => {
								this.click = true;
								this.errors = error.response.data.errors;
								this.waiting = false;
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
								this.$axios.post(`delete-brand/${id}`).then(
									(response) => {
										this.click = true;
										$nuxt.$emit(
											"success",
											response.data.message
										);
										$nuxt.$emit("trigger_brand");
									},
									(error) => {
										this.click = true;
										$nuxt.$emit("error", error);
									}
								);
							} else {
								this.click = true;
							}
						});
				}
			},

			// Reset Brand form
			reset() {
				this.form.name = "";
				this.form.logo = "";
				this.select = [];
				this.edit_id = "";
				this.errors = {};
				this.edit_mode = false;
			},

			// Search brand
			search() {
				$nuxt.$emit("trigger_brand");
			},
			instantSearch() {
				setTimeout(() => {
					$nuxt.$emit("trigger_brand");
				}, 500);
			},

			// Toggle photo modal
			toggle_photo_modal() {
				this.$store.dispatch("toggle_photo_modal", {
					name: "brand",
					modal: true,
					multiple: false,
					photo: this.select,
				});
			},

			//Set Select Image
			set_image(data) {
				if (data.name === "brand") {
					this.select = data.photo;
					this.form.logo = data.photo.length > 0 ? data.photo[0].id : "";
				}
			},

			//Remove Select Image
			remove_image() {
				this.select = [];
				this.form.logo = "";
			},
		},

		created() {
			this.get_results();
			this.$nuxt.$on("trigger_brand", () => {
				this.get_results();
			});
			this.$nuxt.$on("trigger_reset", () => {
				this.reset();
			});
			this.$nuxt.$on("trigger_select_image", (data) => {
				this.set_image(data);
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("trigger_brand");
			this.$nuxt.$off("trigger_reset");
			this.$nuxt.$off("trigger_select_image");
		},
	};
</script>