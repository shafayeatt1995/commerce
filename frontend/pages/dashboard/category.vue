<template>
	<div>
		<div class="section-header d-flex justify-content-between">
			<h1>All Categories</h1>
			<button type="button" class="btn btn-primary" @click="reset">Add Category</button>
		</div>

		<div class="section-body">
			<div class="row">
				<div class="col-lg-8">
					<div class="card card-primary">
						<div class="card-header">
							<h4>Categories</h4>
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
							<table class="table table-hover table-responsive">
								<thead>
									<tr class="text-center">
										<th scope="col">#</th>
										<th scope="col">Name</th>
										<th scope="col">image</th>
										<th scope="col">sub Categories</th>
										<th scope="col">Options</th>
									</tr>
								</thead>
								<tbody v-if="loading">
									<td colspan="5" class="pt-4">
										<h2 class="d-flex justify-content-center">
											<Spinner />
										</h2>
									</td>
								</tbody>
								<tbody v-else-if="categories.data && categories.data.length >= 1">
									<tr v-for="(category, key) in categories.data" :key="category.data">
										<th class="text-center">
											<Key :data="categories" :index="key" />
										</th>
										<td class="text-center">{{category.name}}</td>
										<td class="text-center">
											<img :src="url + category.image" :alt="category.name" class="img-fluid py-2 max-h100px">
										</td>
										<td class="py-3">
											<span class="badge badge-light m-1" v-for="sub in category.sub_categories" :key="`sub-${sub.id}`">{{sub.name}}</span>
										</td>
										<td class="text-center">
											<div class="d-flex justify-content-center">
												<button type="button" class="btn btn-outline-primary btn-sm mx-1 px-2" @click="edit_category(category)">
													<i>
														<icon :icon="['fas', 'pen-to-square']"></icon>
													</i>
												</button>
												<button type="button" class="btn btn-outline-danger btn-sm mx-1 px-2" @click="delete_category(category.id)">
													<i>
														<icon :icon="['far', 'trash-can']"></icon>
													</i>
												</button>
											</div>
										</td>
									</tr>
								</tbody>
								<tbody v-else>
									<td colspan="5" class="pt-3">
										<h2 class="text-center">No category found</h2>
									</td>
								</tbody>
							</table>
							<pagination :data="categories" @pagination-change-page="get_results" class="justify-content-center mt-3 paginate"></pagination>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card" :class="edit_mode ? 'card-warning' : 'card-primary'">
						<div class="card-header">
							<h4 v-if="edit_mode">Edit Category</h4>
							<h4 v-else>Create Category</h4>
						</div>
						<div class="card-body">
							<form @submit.prevent="edit_mode ? update_category() : create_category()">
								<div class="form-group">
									<label for="name">Category Name</label>
									<input type="text" class="form-control" id="name" v-model="form.name" required>
									<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
								</div>
								<div class="image-form">
									<div class="image-frame" v-if="edit_mode">
										<img :src="preview" class="img-fluid max-h250" alt="image" v-if="form.image">
										<img :src="url + old_image" class="img-fluid max-h250" alt="image" v-else>
									</div>
									<div class="image-frame" v-else>
										<img :src="preview" class="img-fluid max-h250" alt="image" v-if="form.image">
										<label for="image" class="image-frame-content" v-else>
											<i>
												<icon :icon="['fas', 'cloud-arrow-up']"></icon>
											</i>
											<span>Select and upload your category image</span>
										</label>
									</div>
									<label for="image" class="image-select">
										Chose Image
									</label>
									<input type="file" class="form-control" id="image" accept="image/*" @change="image($event)">
									<p class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</p>
								</div>
								<div class="text-right mt-4">
									<button class="btn btn-warning ml-2" type="reset" @click="reset">Reset</button>
									<button type="submit" class="btn btn-primary" v-if="edit_mode" :disabled="waiting">
										<transition name="fade" mode="out-in">
											<span v-if="waiting">Loading</span>
											<span v-else>Update Category</span>
										</transition>
									</button>
									<button type="submit" class="btn btn-primary" :disabled="waiting" v-else>
										<transition name="fade" mode="out-in">
											<span v-if="waiting">Loading</span>
											<span v-else>Create Category</span>
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
		name: "category",
		auth: true,
		middleware: "admin",
		head() {
			return {
				title: `Category - ${this.app_name}`,
			};
		},

		data() {
			return {
				click: true,
				loading: false,
				waiting: false,
				categories: {},
				search_option: {
					keyword: "",
					colum: "name",
				},
				edit_mode: false,
				errors: {},
				preview: "",
				edit_id: "",
				old_image: "",
				form: {
					name: "",
					image: "",
				},
			};
		},

		methods: {
			// Get category
			get_categories() {
				this.loading = true;
				this.$axios.post("category", this.search_option).then(
					(response) => {
						this.categories = response.data.categories;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
						this.loading = false;
					}
				);
			},
			get_results(page = 1) {
				this.$axios
					.post(`category?page=${page}`, this.search_option)
					.then((response) => {
						this.categories = response.data.categories;
					});
			},

			// Reset category form
			reset() {
				this.form.name = "";
				this.form.image = "";
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
					this.form.image = file;
				}
			},

			//Create new category
			create_category() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};
					const config = {
						headers: { "content-type": "multipart/form-data" },
					};

					let formData = new FormData();
					formData.append("name", this.form.name);
					formData.append("image", this.form.image);
					this.$axios.post("create-category", formData, config).then(
						(response) => {
							$nuxt.$emit("trigger_category");
							$nuxt.$emit("trigger_reset");
							$nuxt.$emit("success", response.data.message);
							this.waiting = false;
							this.click = true;
						},
						(error) => {
							this.errors = error.response.data.errors;
							this.waiting = false;
							this.click = true;
						}
					);
				}
			},

			// Edit category
			edit_category(category) {
				$nuxt.$emit("trigger_reset");
				this.edit_mode = true;
				this.form.name = category.name;
				this.old_image = category.image;
				this.edit_id = category.id;
			},

			//Update new category
			update_category() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};
					const config = {
						headers: { "content-type": "multipart/form-data" },
					};

					let formData = new FormData();
					formData.append("name", this.form.name);
					formData.append("image", this.form.image);
					this.$axios
						.post(`update-category/${this.edit_id}`, formData, config)
						.then(
							(response) => {
								$nuxt.$emit("trigger_category");
								$nuxt.$emit("trigger_reset");
								$nuxt.$emit("success", response.data.message);
								this.waiting = false;
								this.click = true;
							},
							(error) => {
								this.errors = error.response.data.errors;
								this.waiting = false;
								this.click = true;
							}
						);
				}
			},

			// Delete category
			delete_category(id) {
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
								this.$axios.post(`delete-category/${id}`).then(
									(response) => {
										$nuxt.$emit("trigger_category");
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

			// Search category
			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios.post("category", this.search_option).then(
						(response) => {
							this.categories = response.data.categories;
							this.loading = false;
							this.click = true;
						},
						(error) => {
							$nuxt.$emit("error", error);
							this.loading = false;
							this.click = true;
						}
					);
				}
			},
			instantSearch() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					setTimeout(() => {
						this.$axios.post("category", this.search_option).then(
							(response) => {
								this.categories = response.data.categories;
								this.loading = false;
								this.click = true;
							},
							(error) => {
								$nuxt.$emit("error", error);
								this.loading = false;
								this.click = true;
							}
						);
					}, 500);
				}
			},
		},

		created() {
			this.get_categories();
			this.$nuxt.$on("trigger_category", () => {
				this.get_categories();
			});
			this.$nuxt.$on("trigger_reset", () => {
				this.reset();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("trigger_category");
			this.$nuxt.$off("trigger_reset");
		},
	};
</script>