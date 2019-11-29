import Vue from 'vue'
import VueAxios from 'vue-axios'
import VueAuthenticate from 'vue-authenticate'
import axios from 'axios';

Vue.use(VueAxios, axios);
Vue.use(VueAuthenticate, {
  baseUrl: 'http://school.thenextceo.org/', // Your API domain

  providers: {
    google: {
      clientId: '380346935662-60adamq08ovpkjv4qccs685ctk3elma6.apps.googleusercontent.com',
      redirectUri: 'http://school.thenextceo.org/auth/callback' // Your client app URL
    },
    live: {
      clientId: 'e531598b-ddac-46dc-aa96-a4695caaca1d',
      redirectUri: 'http://school.thenextceo.org/auth/callback' // Your client app URL
    }
  }
})
