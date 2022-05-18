<template>
	<div>
		<div class="modal fade show overflow-y-auto" style="display: block; padding-right: 17px;">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<ul class="nav nav-tabs w-100">
							<li class="nav-item">
								<button type="button" class="nav-link outline-0" :class="active === 'image' ? 'active show' : ''" @click="active = 'image'">Select Image</button>
							</li>
							<li class="nav-item">
								<button type="button" class="nav-link outline-0" :class="active === 'upload' ? 'active show' : ''" @click="active = 'upload'">Upload Image</button>
							</li>
						</ul>
						<button type="button" class="close" @click="close_modal">
							<span>×</span>
						</button>
					</div>
					<div class="modal-body">
						<transition name="fade" mode="out-in">
							<div class="card" v-if="active === 'image'" key="1">
								<div class=" row">
									<div class="col-lg-3">
										<select class="form-control mb-2" v-model="search.sort" @change="get_image">
											<option value="new">Sort by newest</option>
											<option value="old">Sort by oldest</option>
											<option value="small">Sort by smallest</option>
											<option value="large">Sort by biggest</option>
										</select>
									</div>
									<div class="offset-lg-6 col-lg-3">
										<div class="form-group">
											<div class="input-group mb-2">
												<input type="text" class="form-control" id="name" v-model="search.keyword" placeholder="Search your file" @keyup="instant_search">
												<div class="input-group-append">
													<button class="btn btn-primary" type="button" @click="img_search">
														<i>
															<icon :icon="['fas', 'magnifying-glass']"></icon>
														</i>
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="d-flex justify-content-center m-5" v-if="loading">
									<Spinner />
								</div>
								<div class="card-body  mt--20" v-else-if="collections.data && collections.data.length > 0">
									<p>{{select.length}} image selected</p>
									<div class="select-image-grid">
										<div class="text-center single-select-image p-1 pointer" :class="select.some(data => data.id === image.id) ? 'image-selected' : ''" v-for="(image, key) in collections.data" :key="`img-${key}`" @click="select_image(image)">
											<img :data-src="url+image.photo" class="img-fluid thumbnail" :alt="image.name" v-lazy-load>
											<div class="d-flex justify-content-around align-items-center w-100 p-2">
												<div>
													<p class="small m-0"><strong>Name:</strong> {{image.name}}</p>
													<p class="small m-0"><strong>Size:</strong> {{image.size | size}}</p>
												</div>
												<button type="button" class="btn btn-outline-danger" @click="delete_image(image.id)">
													<i>
														<icon :icon="['far', 'trash-can']"></icon>
													</i>
												</button>
											</div>
										</div>
									</div>
									<pagination :data="collections" @pagination-change-page="get_image" class="justify-content-center mt-3 paginate"></pagination>
								</div>
								<div class="card-body" v-else>
									<DashboardEmpty message="No image found" />
								</div>
							</div>
							<div class="image-form" key="2" v-else>
								<div class="image-frame grid" v-if="previews.length > 0">
									<div class="image-position" v-for="(preview, key) in previews" :key="`preview-${key}`">
										<p class="upload-success m-0" v-if="(status.done - 1) >= key">
											<i>
												<icon :icon="['fas', 'check']"></icon>
											</i>
										</p>
										<button type="button" class="image-remove pointer" @click="remove_image(key)" v-else>
											<i>
												<icon :icon="['fas', 'xmark']"></icon>
											</i>
										</button>
										<img :src="preview.preview" class="img-fluid thumbnail" alt="image">
										<div>
											<p class="small m-0"><strong>Name:</strong> {{preview.name}}</p>
											<p class="small m-0"><strong>Size:</strong> {{preview.size | size}}</p>
										</div>
									</div>
								</div>
								<div class="image-frame" v-else>
									<label for="image" class="image-frame-content">
										<i>
											<icon :icon="['fas', 'cloud-arrow-up']"></icon>
										</i>
										<span>Select and upload image</span>
									</label>
								</div>
								<label for="image" class="image-select">
									<i>
										<icon :icon="['fas', 'cloud-arrow-up']"></icon>
									</i>
									Chose Image
								</label>
								<input type="file" class="form-control" id="image" accept="image/*" @change="image($event)" multiple>
								<p class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</p>
								<div class="mt-3" v-if="status.ready">
									<p class="m-0">Upload status</p>
									<div class="progress mt-1">
										<div class="progress-bar" :style="`width: ${(status.done * 100) / previews.length}%;`">{{status.done}}/{{previews.length}}</div>
									</div>
								</div>
							</div>
						</transition>
					</div>
					<div class="modal-footer bg-whitesmoke br">
						<button type="button" class="btn btn-primary" @click="select_complete" v-if="active === 'image'">Select</button>
						<button type="button" class="btn btn-primary" @click="upload" v-if="active === 'upload'">Upload</button>
						<button type="button" class="btn btn-danger" @click="clear_image" v-if="active === 'upload'">Clear Image</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-backdrop fade show"></div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				click: true,
				loading: false,
				active: "image",
				previews: [],
				collections: {},
				images: [],
				errors: {},
				search: {
					keyword: "",
					sort: "new",
				},
				status: {
					ready: false,
					complete: 0,
					failed: 0,
					done: 0,
				},
				select: [],
			};
		},

		methods: {
			get_image(page = 1) {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios.post(`photo?page=${page}`, this.search).then(
						(response) => {
							this.collections = response.data.photos;
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

			// Add Multiple Image
			image(event) {
				if (event.target.files.length > 0 && this.click) {
					for (let file of Object.entries(event.target.files)) {
						let reader = new FileReader();
						reader.onloadend = () => {
							this.previews.push({
								name: file[1].name,
								size: file[1].size,
								preview: reader.result,
							});
						};
						file !== undefined ? reader.readAsDataURL(file[1]) : "";
						this.images.push({
							name: file[1].name.substring(
								0,
								file[1].name.lastIndexOf(".")
							),
							size: file[1].size,
							photo: file[1],
						});
					}
				}
			},

			//Remove Image
			remove_image(key) {
				if (this.click) {
					this.previews.splice(key, 1);
					this.images.splice(key, 1);
				}
			},

			//Clear All Image
			clear_image() {
				this.status.ready = false;
				this.status.complete = 0;
				this.status.failed = 0;
				this.status.done = 0;
				this.previews = [];
				this.images = [];
			},

			//Upload image
			upload() {
				if (this.click && this.images.length > 0) {
					this.click = false;
					this.status.ready = true;

					const config = {
						headers: { "content-type": "multipart/form-data" },
					};
					let formData = new FormData();
					formData.append("name", this.images[0].name);
					formData.append("size", this.images[0].size);
					formData.append("photo", this.images[0].photo);

					this.$axios.post("upload-photo", formData, config).then(
						(response) => {
							this.click = true;
							this.images.splice(0, 1);
							this.status.complete++;
							this.status.done++;
							$nuxt.$emit("trigger_upload");
						},
						(error) => {
							this.click = true;
							this.images.splice(0, 1);
							this.status.failed++;
							this.status.done++;
							$nuxt.$emit("trigger_upload");
						}
					);
				} else {
					$nuxt.$emit("success", "Upload Complete");
					$nuxt.$emit("trigger_image");
				}
			},

			// Delete Image
			delete_image(id) {
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
								this.$axios.post(`delete-photo/${id}`).then(
									(response) => {
										this.click = true;
										$nuxt.$emit(
											"success",
											response.data.message
										);
										$nuxt.$emit("trigger_update_image");
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

			// Search image
			img_search() {
				$nuxt.$emit("trigger_image");
			},
			instant_search() {
				setTimeout(() => {
					$nuxt.$emit("trigger_image");
				}, 500);
			},

			//Select image
			select_image(image) {
				if (this.photo_modal_data.multiple) {
					if (this.select.some((data) => data.id === image.id)) {
						let key = this.select.findIndex(
							(data) => data.id === image.id
						);
						this.select.splice(key, 1);
					} else {
						this.select.push(image);
					}
				} else {
					if (this.select.some((data) => data.id === image.id)) {
						let key = this.select.findIndex(
							(data) => data.id === image.id
						);
						this.select.splice(key, 1);
					} else {
						this.select = [];
						this.select.push(image);
					}
				}
			},

			//Close Modal
			close_modal() {
				if (this.click) {
					this.$store.dispatch("toggle_photo_modal", { modal: false });
				}
			},

			//Select complete
			select_complete() {
				if (this.click) {
					$nuxt.$emit("trigger_select_image", {
						name: this.photo_modal_data.sender_name,
						photo: this.select,
					});
					this.$store.dispatch("toggle_photo_modal", { modal: false });
				}
			},

			// Set Select image
			set_image() {
				this.photo_modal_data.collection.forEach((image) => {
					this.select.push(image);
				});
			},
		},

		created() {
			this.get_image();
			this.set_image();
			this.$nuxt.$on("trigger_image", () => {
				this.get_image();
			});
			this.$nuxt.$on("trigger_update_image", () => {
				this.get_image();
			});
			this.$nuxt.$on("trigger_upload", () => {
				this.upload();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("trigger_update_image");
			this.$nuxt.$off("trigger_image");
			this.$nuxt.$off("trigger_upload");
		},
	};
</script>