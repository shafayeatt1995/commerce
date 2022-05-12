export default function ({ store, redirect }) {
	if (!store.getters.is_admin) {
		return redirect('/')
	}
}