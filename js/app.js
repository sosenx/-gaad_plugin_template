(function(window, Vue, VueRouter){
	
	//escape if no holder on page
	if ( document.getElementById( 'app-apii' ) === null ) { return; }

	var store = new Vuex.Store({
	  state: {
	    model: window['apii' + '__app_model']
	  },
	  mutations: { }
	});

	var router = new VueRouter({
	  routes : [
		  { path: '/', component: my_component_1___apii },
		  { path: '/my-component-2', component: my_component_2___apii },
		  { path: '/my-component-3', component: my_component_3___apii }
		]
	});

	
	var app = new Vue({
	  store: store,
	  router: router
	}).$mount('#app-apii');

})(window, Vue, VueRouter);	