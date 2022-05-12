export default function ({ store, redirect }) {
	if (!store.getters.is_customer) {
		return redirect('/')
	}
}