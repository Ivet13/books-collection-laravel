import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


import './menu';
import './navigation';
//import './formValidate';
/*
import './books';
import './authors';
import './genres';
import './publishers';
import './customers';
*/


// resources/js/app.js
import { initCrudPage } from './crudPage';

initCrudPage({
    rootSelector: '.authors-page',
    formSelector: '.js-author-form',
    listSelector: '.js-list',
    paginationSelector: '.js-pagination',
    deleteBtnSelector: '.delete-btn',
    // si tienes un form de filtros:
    // filtersFormSelector: '.js-authors-filters',
    resetSelector: '.js-author-reset',

    // Estas URLs suelen venir de data-* en el root:
    listUrl: (root) => root.dataset.listUrl,
    showUrl: (id, root) => `${root.dataset.showUrl}/${id}`,
    storeUrl: (root) => root.dataset.storeUrl,
    destroyUrl: (id, root) => `${root.dataset.destroyUrl}/${id}`,
    updateUrl: (id, root) => `${root.dataset.showUrl}/${id}`, // /admin/authors/{id}

    // Si tu JSON del show viene como { author: {...} } ajusta aquí:
    onLoadToForm: (data, ctx) => {
        const author = data.author ?? data;
        const form = ctx.form;
        if (!form) return;
        form.querySelector('[name="id"]').value = author.id ?? '';
        form.querySelector('[name="name"]').value = author.name ?? '';
        form.querySelector('[name="bio"]').value = author.bio ?? '';
        // etc...
    },

    activeClass: 'is-active',
    debug: true,
});