document.addEventListener("DOMContentLoaded", () => {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  const listEl = document.querySelector(".js-list");
  const paginationEl = document.querySelector(".js-pagination");
  const filterForm = document.querySelector(".js-filter-form"); // pon esta clase en tu form de filtros
  const form = document.querySelector(".js-book-form");
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
    authors: document.getElementById("meta-authors"),
    publisher: document.getElementById("meta-publisher"),
    year: document.getElementById("meta-year"),
    genres: document.getElementById("meta-genres"),
  };

  const headers = () => ({
    "X-CSRF-TOKEN": csrf,
    "Accept": "application/json",
  });

  function setErrors(msgs = []) {
    if (!errorsEl) return;
    errorsEl.innerHTML = msgs.length ? `<ul>${msgs.map(m => `<li>${m}</li>`).join("")}</ul>` : "";
  }

  function setDeleteVisible(visible) {
    if (!deleteBtn) return;
    deleteBtn.style.display = visible ? "inline-block" : "none";
  }

  function clearSelected() {
    document.querySelectorAll(".edit-tab.selected").forEach(el => el.classList.remove("selected"));
  }

  function enterCreateMode() {
    form.action = storeUrl;
    fields.book_id.value = "";
    fields.method.value = "POST";
    fields.title.value = "";
    fields.isbn.value = "";
    fields.description.value = "";
    setDeleteVisible(false);
    setErrors([]);

    if (meta.authors) meta.authors.textContent = "—";
    if (meta.publisher) meta.publisher.textContent = "—";
    if (meta.year) meta.year.textContent = "—";
    if (meta.genres) meta.genres.textContent = "—";

    clearSelected();
  }

  function renderMeta(data) {
    // Ajusta según lo que devuelva tu JSON
    const authors = (data.authors || []).map(a => a.name).filter(Boolean);
    const genres = (data.genres || []).map(g => g.name).filter(Boolean);

    if (meta.authors) meta.authors.innerHTML = authors.length
      ? authors.map(n => `<div><strong>${escapeHtml(n)}</strong></div>`).join("")
      : "<em>Sin autores</em>";

    if (meta.publisher) meta.publisher.textContent =
      data.publisher?.name || "Sin editorial";

    if (meta.year) meta.year.textContent =
      data.published_year ?? "-";

    if (meta.genres) meta.genres.innerHTML = genres.length
      ? genres.map(n => `<div><strong>${escapeHtml(n)}</strong></div>`).join("")
      : "<em>Sin genres</em>";
  }

  function escapeHtml(s) {
    return String(s)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  async function loadBook(id, tabEl) {
    setErrors([]);
    const url = `${showBase}/${id}`;

    const res = await fetch(url, { headers: headers() });
    if (!res.ok) {
      setErrors([`No se pudo cargar el libro (HTTP ${res.status}).`]);
      return;
    }

    const data = await res.json();

    fields.book_id.value = data.id;
    fields.title.value = data.title || "";
    fields.isbn.value = data.isbn || "";
    fields.description.value = data.description || "";

    form.action = url;
    fields.method.value = "PUT";
    setDeleteVisible(true);

    renderMeta(data);

    clearSelected();
    tabEl?.classList.add("selected");
  }

  async function saveBook() {
    setErrors([]);

    const data = new FormData(form);

    // IMPORTANTE: Laravel espera POST + _method cuando usas routes resource
    data.set("_method", fields.method.value);

    const res = await fetch(form.action, {
      method: "POST",
      headers: { ...headers() },
      body: data,
    });

    // Validación
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

    const json = await res.json();
    // Esperamos que devuelva el libro guardado
    // Opcional: actualizar lista sin recargar
    await refreshList();        // refresca lista + paginación
    await loadBook(json.id);    // deja el form en edit-mode del libro guardado
  }

  async function deleteBook() {
    if (!fields.book_id.value) return;
    if (!confirm("¿Eliminar este libro?")) return;

    setErrors([]);

    const data = new FormData();
    data.set("_token", form.querySelector('input[name="_token"]')?.value || "");
    data.set("_method", "DELETE");

    const url = `${showBase}/${fields.book_id.value}`;

    const res = await fetch(url, {
      method: "POST",
      headers: { ...headers() },
      body: data,
    });

    if (!res.ok) {
      setErrors([`Error eliminando (HTTP ${res.status}).`]);
      return;
    }

    await refreshList();
    enterCreateMode();
  }

  // --- LISTA + FILTROS + PAGINACIÓN POR AJAX (HTML parcial) ---
  async function refreshList(url = window.location.href) {
    // pedimos HTML parcial (lista + paginación) desde index
    const res = await fetch(url, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "text/html",
      },
    });

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

    bindListClicks();       // reenganchar eventos en los nuevos nodos
    bindPaginationClicks(); // idem

    // Mantener URL sin recargar
    window.history.pushState({}, "", url);
  }

  function bindListClicks() {
    document.querySelectorAll(".edit-tab").forEach(tab => {
      tab.addEventListener("click", () => loadBook(tab.dataset.id, tab));
    });
  }

  function bindPaginationClicks() {
    if (!paginationEl) return;
    paginationEl.querySelectorAll("a").forEach(a => {
      a.addEventListener("click", (e) => {
        e.preventDefault();
        refreshList(a.href);
      });
    });
  }

  // Eventos del form
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    saveBook();
  });

  form.addEventListener("reset", () => {
    setTimeout(enterCreateMode, 0);
  });

  deleteBtn?.addEventListener("click", deleteBook);

  // Filtros por AJAX
  filterForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    const url = `${window.location.pathname}?${new URLSearchParams(new FormData(filterForm)).toString()}`;
    refreshList(url);
  });

  // Inicial
  bindListClicks();
  bindPaginationClicks();
  enterCreateMode();
});
