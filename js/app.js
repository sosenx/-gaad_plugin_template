(function(window, Vue, VueRouter){
	


	var store = new Vuex.Store({
	  state: {
	    model: window['plugins_main_namespace' + '__app_model']
	  },
	  mutations: { }
	});

	var router = new VueRouter({
	  routes : [
		  { path: '/', component: my_component_1___plugins_main_namespace },
		  { path: '/my-component-2', component: my_component_2___plugins_main_namespace },
		  { path: '/my-component-3', component: my_component_3___plugins_main_namespace }
		]
	});

	
	var app = new Vue({
	  store: store,
	  router: router
	}).$mount('#app-plugins_main_namespace');

})(window, Vue, VueRouter);	