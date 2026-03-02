

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


import './menu';
import './navigation';




//import './formValidate';

import { initNavigation } from "./navigation"; // tu menu + popstate + pinta crudForm/crudTable
import { initCrudForm } from "./form";
import { initCrudTable } from "./table";

initNavigation();
initCrudForm();
initCrudTable();
