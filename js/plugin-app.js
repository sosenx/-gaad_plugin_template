(function(window, Vue, VueRouter){


	const Base = { template: '#template-plugins_main_namespace-main' }
	const Foo = { template: '<div>foo</div>' }
	const Bar = { template: '<div>bar</div>' }

	
	const routes = [
	  { path: '/', component: Base },
	  { path: '/foo', component: Foo },
	  { path: '/bar', component: Bar }
	]


	const store = new Vuex.Store({
	  state: {
	    count: 0
	  },
	  mutations: {
	    increment (state) {
	      state.count++
	    }
	  }
	})

	const router = new VueRouter({
	  routes
	})

	
	const app = new Vue({
	  store,
	  router
	}).$mount('#app-plugins_main_namespace');

})(window, Vue, VueRouter);	