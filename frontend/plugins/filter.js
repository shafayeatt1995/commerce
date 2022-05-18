import Vue from "vue";
import moment from "moment";

// Date Format
Vue.filter("date", (value) => {
	return moment(value).startOf("hour").fromNow();
});

Vue.filter("normalDate", (value) => {
	return moment(value).format("ll");
});

Vue.filter("fullDate", (value) => {
	return moment(value).format("LLLL");
});

Vue.filter("year", (value) => {
	return moment(value).format("YYYY");
});

// Currency Format
Vue.filter("currency", (value) => {
	return isNaN(value) ? value : parseFloat(value).toFixed(2).replace(/\.00$/, "");
});

// Kilo Byte Format
Vue.filter("size", (value) => {
	const kb = 1024;
	if (value > ((kb ** 3) - 1) && !isNaN(value)) {
		return (parseFloat(value) / (kb ** 3)).toFixed(2).replace(/\.00$/, "") + "GB";
	} else if (value > ((kb ** 2) - 1) && !isNaN(value)) {
		return (parseFloat(value) / (kb ** 2)).toFixed(2).replace(/\.00$/, "") + "MB";
	} else if (!isNaN(value)) {
		return (parseFloat(value) / kb).toFixed(2).replace(/\.00$/, "") + "KB";
	}
});


// Number Format
Vue.filter("number", (value) => {
	return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
});

