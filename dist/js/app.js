var test1  = 'comp1';
var test1  = 'comp2';
(function(window, Vue, VueRouter){


	var Base = { template: '#template-kamadmin-main' };
	var Foo = { template: '<div>foo</div>' };
	var Bar = { template: '<div>bar</div>' };

	
	var routes = [
	  { path: '/', component: Base },
	  { path: '/foo', component: Foo },
	  { path: '/bar', component: Bar }
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
	}).$mount('#app-kamadmin');

})(window, Vue, VueRouter);	