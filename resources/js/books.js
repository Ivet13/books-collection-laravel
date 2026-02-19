document.addEventListener("DOMContentLoaded", () => {
  // ✅ Root para evitar choques con authors.js
  const root = document.querySelector(".books-page");
  if (!root) return;

  console.log("books.js activo");

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  const listEl = root.querySelector(".js-list");
  const paginationEl = root.querySelector(".js-pagination");
  const filterForm = root.querySelector(".js-filter-form");
  const filterResetBtn = root.querySelector(".js-filter-reset");

  const form = root.querySelector(".js-book-form");
  if (!form || !listEl) return;

  const storeUrl = form.dataset.storeUrl;
  const showBase = form.dataset.showUrlBase;

  const deleteBtn = form.querySelector(".delete-btn");
  const errorsEl = form.querySelector(".js-errors");

  const fields = {
    book_id: form.querySelector("#book_id"),
    method: form.querySelector("#method"),
    title: form.querySelector("#title"),
    isbn: form.querySelector("#isbn"),
    description: form.querySelector("#description"),
  };

  const meta = {
    authors: root.querySelector("#meta-authors"),
    publisher: root.querySelector("#meta-publisher"),
    year: root.querySelector("#meta-year"),
    genres: root.querySelector("#meta-genres"),
  };

  // --- Headers separados: JSON vs HTML ---
  const jsonHeaders = () => ({
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": csrf,
    "Accept": "application/json",
  });

  const htmlHeaders = () => ({
    "X-Requested-With": "XMLHttpRequest",
    "Accept": "text/html",
  });

  function setErrors(msgs = []) {
    if (!errorsEl) return;
    errorsEl.innerHTML = msgs.length
      ? `<ul>${msgs.map(m => `<li>${escapeHtml(m)}</li>`).join("")}</ul>`
      : "";
  }

  function setDeleteVisible(visible) {
    if (!deleteBtn) return;
    deleteBtn.style.display = visible ? "inline-block" : "none";
  }

  function clearSelected() {
    root.querySelectorAll(".edit-tab.selected").forEach(el => el.classList.remove("selected"));
  }

  function enterCreateMode() {
    form.action = storeUrl;
    if (fields.book_id) fields.book_id.value = "";
    if (fields.method) fields.method.value = "POST";
    if (fields.title) fields.title.value = "";
    if (fields.isbn) fields.isbn.value = "";
    if (fields.description) fields.description.value = "";

    setDeleteVisible(false);
    setErrors([]);

    if (meta.authors) meta.authors.textContent = "—";
    if (meta.publisher) meta.publisher.textContent = "—";
    if (meta.year) meta.year.textContent = "—";
    if (meta.genres) meta.genres.textContent = "—";

    clearSelected();
  }

  function escapeHtml(s) {
    return String(s)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  function renderMeta(data) {
    const authors = (data.authors || []).map(a => a.name).filter(Boolean);
    const genres = (data.genres || []).map(g => g.name).filter(Boolean);

    if (meta.authors) {
      meta.authors.innerHTML = authors.length
        ? authors.map(n => `<div><strong>${escapeHtml(n)}</strong></div>`).join("")
        : "<em>Sin autores</em>";
    }

    if (meta.publisher) meta.publisher.textContent = data.publisher?.name || "Sin editorial";
    if (meta.year) meta.year.textContent = data.published_year ?? "-";

    if (meta.genres) {
      meta.genres.innerHTML = genres.length
        ? genres.map(n => `<div><strong>${escapeHtml(n)}</strong></div>`).join("")
        : "<em>Sin géneros</em>";
    }
  }

  async function loadBook(id, tabEl) {
    if (!id) return;
    setErrors([]);

    const url = `${showBase}/${id}`;

    const res = await fetch(url, { headers: jsonHeaders() });
    if (!res.ok) {
      setErrors([`No se pudo cargar el libro (HTTP ${res.status}).`]);
      return;
    }

    const data = await res.json();

    if (fields.book_id) fields.book_id.value = data.id;
    if (fields.title) fields.title.value = data.title || "";
    if (fields.isbn) fields.isbn.value = data.isbn || "";
    if (fields.description) fields.description.value = data.description || "";

    form.action = url;
    if (fields.method) fields.method.value = "PUT";
    setDeleteVisible(true);

    renderMeta(data);

    clearSelected();
    tabEl?.classList.add("selected");
  }

  function currentListUrl() {
    // Si tienes filtros en la URL, esto mantiene page, q, author_id, etc.
    return window.location.href;
  }

  async function refreshList(url = currentListUrl()) {
    const res = await fetch(url, { headers: htmlHeaders() });

    if (!res.ok) {
      setErrors([`No se pudo refrescar la lista (HTTP ${res.status}).`]);
      return;
    }

    const html = await res.text();
    const tmp = document.createElement("div");
    tmp.innerHTML = html;

    const newList = tmp.querySelector(".js-list");
    const newPagination = tmp.querySelector(".js-pagination");

    if (newList) listEl.innerHTML = newList.innerHTML;
    if (newPagination && paginationEl) paginationEl.innerHTML = newPagination.innerHTML;

    // Mantén la URL sincronizada (muy útil para back/refresh)
    window.history.pushState({}, "", url);
  }

  async function saveBook() {
    setErrors([]);

    const data = new FormData(form);
    data.set("_method", fields.method.value);

    const res = await fetch(form.action, {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": csrf,
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "text/html",
      },
      body: data,
    });

    if (res.status === 422) {
      const json = await res.json().catch(() => null);
      const msgs = json?.errors ? Object.values(json.errors).flat() : ["Error de validación."];
      setErrors(msgs);
      return;
    }

    if (!res.ok) {
      setErrors([`Error guardando (HTTP ${res.status}).`]);
      return;
    }

    const html = await res.text();

    const tmp = document.createElement("div");
    tmp.innerHTML = html;

    const newList = tmp.querySelector(".js-list");
    const newPagination = tmp.querySelector(".js-pagination");

    if (newList) listEl.innerHTML = newList.innerHTML;
    if (newPagination && paginationEl) paginationEl.innerHTML = newPagination.innerHTML;
  }

  async function deleteBook() {
    const id = fields.book_id?.value;
    if (!id) return;
    if (!confirm("¿Eliminar este libro?")) return;

    setErrors([]);

    const data = new FormData();
    data.set("_token", form.querySelector('input[name="_token"]')?.value || "");
    data.set("_method", "DELETE");

    const url = `${showBase}/${id}`;

    const res = await fetch(url, {
      method: "POST",
      headers: jsonHeaders(),
      body: data,
    });

    if (!res.ok) {
      setErrors([`Error eliminando (HTTP ${res.status}).`]);
      return;
    }

    await refreshList();
    enterCreateMode();
  }

  // --- EVENT DELEGATION (clave para que no se rompa al refrescar DOM) ---

  // Click en item de lista
  root.addEventListener("click", (e) => {
    const tab = e.target.closest(".edit-tab");
    if (!tab || !root.contains(tab)) return;
    const id = tab.dataset.id;
    loadBook(id, tab);
  });

  // Click en paginación (solo dentro del contenedor)
  root.addEventListener("click", (e) => {
    const a = e.target.closest(".js-pagination a");
    if (!a) return;
    e.preventDefault();
    refreshList(a.href);
  });

  // Submit del form (guardar)
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    saveBook();
  });

  // Reset del form (modo crear)
  form.addEventListener("reset", () => {
    setTimeout(enterCreateMode, 0);
  });

  // Delete
  deleteBtn?.addEventListener("click", deleteBook);

  // Filtros
  filterForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    const qs = new URLSearchParams(new FormData(filterForm)).toString();
    const url = qs ? `${filterForm.action}?${qs}` : filterForm.action;
    refreshList(url);
  });

  filterResetBtn?.addEventListener("click", (e) => {
    e.preventDefault();
    filterForm?.reset();
    refreshList(filterForm.action);
  });

  // Si el usuario da atrás/adelante en el navegador, recarga listado acorde
  window.addEventListener("popstate", () => {
    refreshList(window.location.href);
  });

  // Init
  enterCreateMode();
});
