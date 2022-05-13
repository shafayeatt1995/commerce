<template>
	<div>
		<div class="section-header d-flex justify-content-between">
			<h1>All Materials</h1>
			<button type="button" class="btn btn-primary" @click="reset">Add Material</button>
		</div>

		<div class="section-body">
			<div class="row">
				<div class="col-lg-7">
					<div class="card card-primary">
						<div class="card-header">
							<h4>Materials</h4>
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
										<th scope="col">Options</th>
									</tr>
								</thead>
								<tbody v-if="loading">
									<td colspan="3" class="pt-4">
										<h2 class="d-flex justify-content-center">
											<Spinner />
										</h2>
									</td>
								</tbody>
								<tbody v-else-if="materials.data && materials.data.length >= 1">
									<tr v-for="(material, key) in materials.data" :key="material.id">
										<th class="text-center">
											<Key :data="materials" :index="key" />
										</th>
										<td class="text-center">{{material.name}}</td>
										<td class="text-center">
											<div class="d-flex justify-content-center">
												<button type="button" class="btn btn-outline-primary btn-sm mx-1 px-2" @click="edit_material(material)">
													<i>
														<icon :icon="['fas', 'pen-to-square']"></icon>
													</i>
												</button>
												<button type="button" class="btn btn-outline-danger btn-sm mx-1 px-2" @click="delete_material(material.id)">
													<i>
														<icon :icon="['far', 'trash-can']"></icon>
													</i>
												</button>
											</div>
										</td>
									</tr>
								</tbody>
								<tbody v-else>
									<td colspan="3" class="pt-3">
										<h2 class="text-center">No material found</h2>
									</td>
								</tbody>
							</table>
							<pagination :data="materials" @pagination-change-page="get_results" class="justify-content-center mt-3 paginate"></pagination>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="card" :class="edit_mode ? 'card-warning' : 'card-primary'">
						<div class="card-header">
							<h4 v-if="edit_mode">Edit Material</h4>
							<h4 v-else>Create Material</h4>
						</div>
						<div class="card-body">
							<form @submit.prevent="edit_mode ? update_material() : create_material()">
								<div class="form-group">
									<label for="name">Material Name</label>
									<input type="text" class="form-control" id="name" v-model="form.name" required>
									<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
								</div>
								<div class="text-right mt-4">
									<button class="btn btn-warning ml-2" type="reset" @click="reset">Reset</button>
									<button type="submit" class="btn btn-primary" v-if="edit_mode" :disabled="waiting">
										<transition name="fade" mode="out-in">
											<span v-if="waiting">Loading</span>
											<span v-else>Update Material</span>
										</transition>
									</button>
									<button type="submit" class="btn btn-primary" :disabled="waiting" v-else>
										<transition name="fade" mode="out-in">
											<span v-if="waiting">Loading</span>
											<span v-else>Create Material</span>
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
		name: "material",
		auth: true,
		middleware: "admin",
		head() {
			return {
				title: `Material - ${this.app_name}`,
			};
		},

		data() {
			return {
				click: true,
				loading: false,
				waiting: false,
				materials: {},
				search_option: {
					keyword: "",
					colum: "name",
				},
				edit_mode: false,
				errors: {},
				edit_id: "",
				form: {
					name: "",
				},
			};
		},

		methods: {
			// Get material
			get_materials() {
				this.loading = true;
				this.$axios.post("material", this.search_option).then(
					(response) => {
						this.materials = response.data.materials;
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
					.post(`material?page=${page}`, this.search_option)
					.then((response) => {
						this.materials = response.data.materials;
					});
			},

			// Reset material form
			reset() {
				this.form.name = "";
				this.edit_id = "";
				this.errors = {};
				this.edit_mode = false;
			},

			//Create new material
			create_material() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};

					this.$axios.post("create-material", this.form).then(
						(response) => {
							$nuxt.$emit("trigger_material");
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

			// Edit material
			edit_material(material) {
				$nuxt.$emit("trigger_reset");
				this.edit_mode = true;
				this.form.name = material.name;
				this.edit_id = material.id;
			},

			//Update new material
			update_material() {
				if (this.click) {
					this.click = false;
					this.waiting = true;
					this.error = {};

					this.$axios
						.post(`update-material/${this.edit_id}`, this.form)
						.then(
							(response) => {
								$nuxt.$emit("trigger_material");
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

			// Delete material
			delete_material(id) {
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
								this.$axios.post(`delete-material/${id}`).then(
									(response) => {
										$nuxt.$emit("trigger_material");
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

			// Search material
			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios.post("material", this.search_option).then(
						(response) => {
							this.materials = response.data.materials;
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
						this.$axios.post("material", this.search_option).then(
							(response) => {
								this.materials = response.data.materials;
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
			this.get_materials();
			this.$nuxt.$on("trigger_material", () => {
				this.get_materials();
			});
			this.$nuxt.$on("trigger_reset", () => {
				this.reset();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("trigger_material");
			this.$nuxt.$off("trigger_reset");
		},
	};
</script>