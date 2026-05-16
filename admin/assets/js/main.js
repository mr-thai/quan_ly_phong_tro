
document.addEventListener("DOMContentLoaded", () => {
  const sections = document.querySelectorAll(".admin-section");
  document.querySelectorAll("[data-admin-tab]").forEach(btn => btn.addEventListener("click", () => {
    document.querySelectorAll("[data-admin-tab]").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    sections.forEach(s => s.classList.toggle("d-none", s.id !== btn.dataset.adminTab));
  }));

  // Open add-user modal (no data handling here)
  const addBtn = document.getElementById("addUserBtn");
  if (addBtn) addBtn.addEventListener("click", () => {
    const modalEl = document.querySelector("#userModal");
    if (modalEl) new bootstrap.Modal(modalEl).show();
  });

  // Keep logout link working (static redirect to site root)
  document.addEventListener("click", (e) => {
    if (e.target.closest("[data-logout]")) {
      e.preventDefault();
      window.location.href = "../index.html";
    }
  });
});
