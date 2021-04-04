/**
 *  @description contains definition of vue root component
 */
import './styles/app.css';

import axios              from 'axios';
import VueAxios           from "vue-axios";
import ContainerComponent from "./vue/components/page/base/container";
import Router             from './scripts/libs/vue/Router'

var Vue = require('vue');

var router = new Router();
Vue.createApp(ContainerComponent)
    .use(Router.getRouter())
    .use(VueAxios, axios)
    .mount('#vueApp');