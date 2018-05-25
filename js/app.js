(function(window, Vue, VueRouter){
	
	//escape if no holder on page
	if ( document.getElementById( 'app-kamadmin' ) === null ) { return; }

	var store = new Vuex.Store({
	  state: {
	    model: window['kamadmin' + '__app_model']
	  },
	  mutations: { }
	});

	var router = new VueRouter({
	  routes : [
		  { path: '/', component: my_component_1___kamadmin },
		  { path: '/my-component-2', component: my_component_2___kamadmin },
		  { path: '/my-component-3', component: my_component_3___kamadmin }
		]
	});

	
	var app = new Vue({
	  store: store,
	  router: router
	}).$mount('#app-kamadmin');

})(window, Vue, VueRouter);	