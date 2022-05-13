<template>
	<div>
		<div class="section-header d-flex justify-content-between">
			<h1>All Sub Categories</h1>
			<button type="button" class="btn btn-primary" @click="reset">Add Sub Category</button>
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
										<th scope="col">Sub-Category Name</th>
										<th scope="col">Parent Category</th>
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
											{{category.category.name}}
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
										<h2 class="text-center">No sub category found</h2>
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
							<h4 v-if="edit_mode">Edit sub Category</h4>
							<h4 v-else>Create Sub Category</h4>
						</div>
						<div class="card-body">
							<form @submit.prevent="edit_mode ? update_category() : create_category()">
								<div class="form-group">
									<label for="category">Category List</label>
									<select class="form-control" id="category" v-model="form.category">
										<option value="">Select a category</option>
										<option :value="category.id" v-for="category in category_list" :key="category.id">{{category.name}}</option>
									</select>
									<p class="invalid-feedback" v-if="errors.category">{{errors.category[0]}}</p>
								</div>
								<div class="form-group">
									<label for="name">Sub Category Name</label>
									<input type="text" class="form-control" id="name" v-model="form.name" required>
									<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
								</div>
								<div class="text-right mt-4">
									<button class="btn btn-warning ml-2" type="reset" @click="reset">Reset</button>
									<button type="submit" class="btn btn-primary" v-if="edit_mode" :disabled="waiting">
										<transition name="fade" mode="out-in">
											<span v-if="waiting">Loading</span>
											<span v-else>Update Sub Category</span>
										</transition>
									</button>
									<button type="submit" class="btn btn-primary" :disabled="waiting" v-else>
										<transition name="fade" mode="out-in">
											<span v-if="waiting">Loading</span>
											<span v-else>Create Sub Category</span>
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
		name: "sub-category",
		auth: true,
		middleware: "admin",
		head() {
			return {
				title: `Sub Category - ${this.app_name}`,
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
				old_image: "",
				form: {
					category: "",
					name: "",
				},
				category_list: [],
			};
		},

		methods: {
			// Get category
			get_categories() {
				this.loading = true;
				this.$axios.post("sub-category", this.search_option).then(
					(response) => {
						this.categories = response.data.sub_categories;
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
					.post(`sub-category?page=${page}`, this.search_option)
					.then((response) => {
						this.categories = response.data.sub_categories;
					});
			},

			// Get Category List
			get_category_list() {
				this.$axios.get("category-list").then(
					(response) => {
						this.category_list = response.data.categories;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			// Reset category form
			reset() {
				this.form.name = "";
				this.form.category = "";
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

					this.$axios.post("create-sub-category", this.form).then(
						(response) => {
							$nuxt.$emit("trigger_sub_category");
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
				this.form.category = category.category_id;
				this.edit_id = category.id;
			},

			//Update new category
			update_category() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};

					this.$axios
						.post(`update-sub-category/${this.edit_id}`, this.form)
						.then(
							(response) => {
								$nuxt.$emit("trigger_sub_category");
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
								this.$axios.post(`delete-sub-category/${id}`).then(
									(response) => {
										$nuxt.$emit("trigger_sub_category");
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
					this.$axios.post("sub-category", this.search_option).then(
						(response) => {
							this.categories = response.data.sub_categories;
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
						this.$axios.post("sub-category", this.search_option).then(
							(response) => {
								this.categories = response.data.sub_categories;
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
			this.get_category_list();
			this.$nuxt.$on("trigger_sub_category", () => {
				this.get_categories();
			});
			this.$nuxt.$on("trigger_reset", () => {
				this.reset();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("trigger_sub_category");
			this.$nuxt.$off("trigger_reset");
		},
	};
</script>