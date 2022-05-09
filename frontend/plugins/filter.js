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


// Number Format
Vue.filter("number", (value) => {
	return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
});

