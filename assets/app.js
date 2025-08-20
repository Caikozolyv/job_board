import './styles/app.scss';
import { createApp } from 'vue';

// Fonction pour monter des composants dynamiquement
window.mountVueComponent = function(componentName, selector, props = {}) {
    console.log(componentName, selector, props);
    import(`./vue/components/${componentName}.vue`).then(module => {
        createApp(module.default, props).mount(selector);
    });
};