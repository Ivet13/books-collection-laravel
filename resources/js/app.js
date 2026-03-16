

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


import './menu';
import './navigation';




//import './formValidate';

import { initNavigation } from "./navigation";
import "./form";
import "./table";
import "./tabs.js";
import "./pagination.js";
import "./langSelector.js";


initNavigation();


