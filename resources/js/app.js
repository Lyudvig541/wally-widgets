import { createApp } from 'vue';
import router from './router/router.js'; // Import the router
import AppComponent from './App.vue';

// Create Vue app
const app = createApp({
    components:{
        AppComponent,
    }
});

// Use the router
app.use(router);

// Mount the app
app.mount('#app');
