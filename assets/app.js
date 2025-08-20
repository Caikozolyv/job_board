import './styles/app.scss';
import { createApp } from 'vue';
import { createBootstrap } from 'bootstrap-vue-next/plugins/createBootstrap';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css';

// Function to mount components dynamically
window.mountVueComponent = function(componentName, selector, props = {}) {
    import(`./vue/components/${componentName}.vue`).then(module => {
        createApp(module.default, props)
            .use(createBootstrap())
            .mount(selector);
    });
};