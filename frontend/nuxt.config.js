export default {
  head: {
    title: '-',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'E-Commerce Site' },
      { hid: "keywords", name: "keywords", content: "commerce, e-commerce" },
      { hid: "author", name: "author", content: "Express Hub" },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
	},

	pageTransition: {
		name: 'fade',
		mode: 'out-in'
	},

  plugins: [
		'./plugins/mixin.js',
		"./plugins/pagination.js",
		"./plugins/filter.js",
		"./plugins/tooltip.js",
		{ src: './plugins/toast.js', ssr: false },
		{ src: './plugins/sweetAlert.js', ssr: false },
		{ src: './plugins/ckEditor.js', ssr: false },
	],

  components: true,

  buildModules: [
  ],

  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/dotenv',
    'nuxt-client-init-module',
		[
			'nuxt-fontawesome',
			{
				component: 'icon',
				imports: [
					{
						set: '@fortawesome/free-solid-svg-icons',
						icons: ['fas'],
					},
					{
						set: '@fortawesome/free-regular-svg-icons',
						icons: ['far'],
					},
					{
						set: '@fortawesome/free-brands-svg-icons',
						icons: ['fab'],
					},
				],
			},
		],
		['nuxt-lazy-load', {
			directiveOnly: true,
			defaultImage: `${process.env.URL}images/preloader.svg`,
			loadingClass: 'isLoading',
			loadedClass: 'isLoaded',
		}]
  ],

  axios: {
    baseURL: '/',
  },

  build: {
  }
}
