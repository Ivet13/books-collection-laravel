

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


import './menu';
import './navigation';




//import './formValidate';

import { initNavigation } from "./navigation"; // tu menu + popstate + pinta crudForm/crudTable
import { initCrudForm } from "./formAuthors";
import { initCrudTable } from "./tableAuthors";

initNavigation();
initCrudForm();
initCrudTable();
