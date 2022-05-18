import axios from "axios";
export const state = () => ({
	url: process.env.URL,
	app_name: "",
	sidebar: true,
	photo: {
		modal: false,
		sender_name: "",
		multiple: false,
		collection: [],
	},
	google_analytics: "",
})

export const getters = {
	url: (state) => state.url,
	app_name: (state) => state.app_name,
	sidebar: (state) => state.sidebar,
	photo_modal: (state) => state.photo.modal,
	photo_modal_data: (state) => state.photo,
	google_analytics: (state) => state.google_analytics,
	authentication: (state) => state.auth.loggedIn,
	user: (state) => state.auth.user,
	is_admin: (state) => state.auth.loggedIn ? state.auth.user.role_id === 1 ? true : false : false,
	is_customer: (state) => state.auth.loggedIn ? state.auth.user.role_id === 2 ? true : false : false,
}

export const mutations = {
	// Get Initial Data
	setup(state, response) {
		state.app_name = response.data.app_name;
		state.google_analytics = response.data.google_analytics;
	},

	sidebar(state) {
		state.sidebar = !state.sidebar;
	},

	toggle_photo_modal(state, data) {
		state.photo.collection = data.photo || [];
		state.photo.sender_name = data.name || "";
		state.photo.multiple = data.multiple || state.photo.multiple;
		state.photo.modal = data.modal;
	},
}

export const actions = {
	// Get Initial Data
	async nuxtServerInit(context) {
		const response = await axios.get(`${context.state.url}api/app`);
		context.commit("setup", response);
	},
	nuxtClientInit(context) {
		axios.get(`${context.state.url}api/app`).then(
			(response) => {
				context.commit("setup", response);
			}
		)
	},

	sidebar(context) {
		context.commit("sidebar");
	},

	toggle_photo_modal(context, status) {
		context.commit("toggle_photo_modal", status);
	}
}
