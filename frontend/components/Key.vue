<template>
	<span>
		{{((data.current_page - 1) * data.per_page) + (index + 1) }}
	</span>
</template>
<script>
	export default {
		props: {
			data: {},
			index: 0,
		},
	};
</script>