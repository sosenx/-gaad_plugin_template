(function(window, Vue, VueRouter){
	
	var routes = [
	  { path: '/', component: my_component_1___plugins_main_namespace },
	  { path: '/my-component-2', component: my_component_2___plugins_main_namespace },
	  { path: '/my-component-3', component: my_component_3___plugins_main_namespace }
	];


	var store = new Vuex.Store({
	  state: {
	    count: 0
	  },
	  mutations: {
	    increment: function(state) {
	      state.count++;
	    }
	  }
	});

	var router = new VueRouter({
	  routes : routes
	});

	
	var app = new Vue({
	  store: store,
	  router: router
	}).$mount('#app-plugins_main_namespace');

})(window, Vue, VueRouter);	