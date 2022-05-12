import Vue from 'vue';
import { mapGetters } from 'vuex'


const GlobalData = {
	install(Vue) {
		Vue.mixin({
			computed: {
				...mapGetters({
					url: 'url',
					app_name: 'app_name',
					sidebar: 'sidebar',
					google_analytics: 'google_analytics',
					auth_check: 'authentication',
					user: 'user',
					admin: 'is_admin',
					customer: 'is_customer',
				})
			}
		})
	}
}

Vue.use(GlobalData)