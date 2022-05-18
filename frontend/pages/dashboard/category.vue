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
							<table class="table table-hover table-responsive-lg">
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
									<tr v-for="(category, key) in categories.data" :key="category.id">
										<th class="text-center">
											<Key :data="categories" :index="key" />
										</th>
										<td class="text-center">{{category.name}}</td>
										<td class="text-center">
											<img :src="url + category.image.photo" :alt="category.name" class="img-fluid py-2 max-h100px">
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
							<pagination :data="categories" @pagination-change-page="get_categories" class="justify-content-center mt-3 paginate"></pagination>
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
								<div class="custom-file pointer">
									<span class="custom-file-label" @click="toggle_photo_modal">Select Category Image</span>
									<p class="invalid-feedback mt-5" v-if="errors.image">{{errors.image[0]}}</p>
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
				edit_id: "",
				form: {
					name: "",
					image: "",
				},
				select: [],
			};
		},

		methods: {
			// Get category
			get_categories(page = 1) {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios
						.post(`category?page=${page}`, this.search_option)
						.then(
							(response) => {
								this.click = true;
								this.categories = response.data.categories;
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

			// Reset category form
			reset() {
				this.form.name = "";
				this.form.image = "";
				this.select = [];
				this.edit_id = "";
				this.errors = {};
				this.edit_mode = false;
			},

			//Create new category
			create_category() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};
					this.$axios.post("create-category", this.form).then(
						(response) => {
							this.click = true;
							$nuxt.$emit("trigger_category");
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

			// Edit category
			edit_category(category) {
				$nuxt.$emit("trigger_reset");
				this.edit_mode = true;
				this.form.name = category.name;
				this.form.image = category.image.id;
				this.edit_id = category.id;
				this.select = [category.image];
			},

			//Update new category
			update_category() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};
					this.$axios
						.post(`update-category/${this.edit_id}`, this.form)
						.then(
							(response) => {
								this.click = true;
								$nuxt.$emit("trigger_category");
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
										this.click = true;
										$nuxt.$emit("trigger_category");
										$nuxt.$emit(
											"success",
											response.data.message
										);
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

			// Search category
			search() {
				$nuxt.$emit("trigger_category");
			},
			instantSearch() {
				setTimeout(() => {
					$nuxt.$emit("trigger_category");
				}, 500);
			},

			// Toggle photo modal
			toggle_photo_modal() {
				this.$store.dispatch("toggle_photo_modal", {
					name: "category",
					modal: true,
					multiple: false,
					photo: this.select,
				});
			},

			//Set Select Image
			set_image(data) {
				if (data.name === "category") {
					this.select = data.photo;
					this.form.image = data.photo.length > 0 ? data.photo[0].id : "";
				}
			},

			//Remove Select Image
			remove_image() {
				this.select = [];
				this.form.image = "";
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
			this.$nuxt.$on("trigger_select_image", (data) => {
				this.set_image(data);
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("trigger_category");
			this.$nuxt.$off("trigger_reset");
			this.$nuxt.$off("trigger_select_image");
		},
	};
</script>