const APP = {
  roomsKey: "pt247_rooms",
  usersKey: "pt247_users",
  authKey: "pt247_auth",
  currentCaptcha: ""
};

const fallbackImg = "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80";
const roomImages = [fallbackImg];

// NOTE: embedded sample data removed to make this a UI-only script.
// Maintain content in HTML files; if you add a backend later, expose
// `/api/rooms` and `/api/users` for dynamic data.

document.addEventListener("DOMContentLoaded", async () => {
  await loadData();
  renderHeaderAuth();
  bindLogout();
  const page = document.body.dataset.page;
  const handlers = {
    index: initHome,
    login: initLogin,
    register: initRegister,
    forgot: initForgot,
    account: initAccount,
    password: initChangePassword,
    manage: initPostManage,
    create: initPostCreate,
    edit: initPostEdit,
    detail: initRoomDetail,
    search: initSearch,
    contact: initContact,
    report: initReport
  };
  if (handlers[page]) handlers[page]();
});

// Load data from backend API (`/api/rooms`, `/api/users`).
// If API is unavailable, fallback to localStorage or embedded sample data.
async function loadData() {
  // For a static site we do not populate demo data in JS. If a backend
  // is available it will be used; otherwise pages should include static
  // content directly in HTML.
  APP.rooms = [];
  APP.users = [];
  try {
    const [roomsRes, usersRes] = await Promise.all([
      fetch('/api/rooms'),
      fetch('/api/users')
    ]);
    if (roomsRes && roomsRes.ok) {
      APP.rooms = await roomsRes.json();
    }
    if (usersRes && usersRes.ok) {
      APP.users = await usersRes.json();
    }
  } catch (err) {
    // API not available — keep arrays empty for static site
  }

  // Allow optional local overrides via localStorage, but do not inject sample data.
  if ((!APP.rooms || APP.rooms.length === 0)) {
    const rawRooms = localStorage.getItem(APP.roomsKey);
    if (rawRooms) {
      try { APP.rooms = JSON.parse(rawRooms); } catch (e) { APP.rooms = []; }
    } else {
      APP.rooms = [];
    }
  }

  if ((!APP.users || APP.users.length === 0)) {
    const rawUsers = localStorage.getItem(APP.usersKey);
    if (rawUsers) {
      try { APP.users = JSON.parse(rawUsers); } catch (e) { APP.users = []; }
    } else {
      APP.users = [];
    }
  }
}

function getRooms() {
  return APP.rooms || JSON.parse(localStorage.getItem(APP.roomsKey) || "[]");
}

function setRooms(rooms) {
  APP.rooms = rooms;
  try {
    localStorage.setItem(APP.roomsKey, JSON.stringify(rooms));
  } catch (err) {}
  // Try to send to backend (optional). If backend endpoint exists, it should accept JSON and persist.
  if (navigator.onLine) {
    fetch('/api/rooms', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(rooms) }).catch(() => {});
  }
}

function getUsers() {
  return APP.users || JSON.parse(localStorage.getItem(APP.usersKey) || "[]");
}

function setUsers(users) {
  APP.users = users;
  try {
    localStorage.setItem(APP.usersKey, JSON.stringify(users));
  } catch (err) {}
  if (navigator.onLine) {
    fetch('/api/users', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(users) }).catch(() => {});
  }
}

function getAuth() {
  return JSON.parse(localStorage.getItem(APP.authKey) || "null");
}

function money(value) {
  return new Intl.NumberFormat("vi-VN").format(value) + " đ/tháng";
}

function shortText(text, length = 95) {
  return text.length > length ? text.slice(0, length) + "..." : text;
}

function statusClass(status) {
  return {
    "Chờ duyệt": "status-pending",
    "Đang hiển thị": "status-active",
    "Đã ẩn": "status-hidden",
    "Đã thuê": "status-rented"
  }[status] || "status-hidden";
}

function toast(message, type = "primary") {
  let wrap = document.querySelector(".toast-container");
  if (!wrap) {
    wrap = document.createElement("div");
    wrap.className = "toast-container position-fixed top-0 end-0 p-3";
    document.body.appendChild(wrap);
  }
  const item = document.createElement("div");
  item.className = `toast align-items-center text-bg-${type} border-0`;
  item.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
  wrap.appendChild(item);
  const bsToast = new bootstrap.Toast(item, { delay: 2600 });
  bsToast.show();
  item.addEventListener("hidden.bs.toast", () => item.remove());
}

function isEmail(value) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
}

function isPhone(value) {
  return /^(0|\+84)(3|5|7|8|9)[0-9]{8}$/.test(value.trim());
}

function renderHeaderAuth() {
  const area = document.querySelector("[data-auth-area]");
  if (!area) return;
  const auth = getAuth();
  if (auth) {
    area.innerHTML = `
      <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-user me-1"></i>${auth.name}</button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="account.html">Tài khoản</a></li>
          <li><a class="dropdown-item" href="post-manage.html">Tin đăng của tôi</a></li>
          <li><a class="dropdown-item" href="admin/">Admin</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><button class="dropdown-item text-danger" data-logout>Đăng xuất</button></li>
        </ul>
      </div>`;
  } else {
    area.innerHTML = `<a class="btn btn-outline-primary me-2" href="login.html">Đăng nhập</a><a class="btn btn-primary" href="register.html">Đăng ký</a>`;
  }
}

function bindLogout() {
  document.addEventListener("click", (e) => {
    if (e.target.closest("[data-logout]")) {
      e.preventDefault();
      localStorage.removeItem(APP.authKey);
      toast("Đã đăng xuất", "success");
      setTimeout(() => location.href = "index.html", 700);
    }
  });
}

function roomCard(room) {
  return `
    <div class="col">
      <div class="room-card">
        <img class="room-img" src="${room.image || fallbackImg}" alt="${room.title}">
        <div class="p-3">
          <div class="d-flex justify-content-between gap-2 mb-2">
            <h5 class="fw-bold mb-0">${room.title}</h5>
            <span class="room-price text-nowrap">${money(room.price)}</span>
          </div>
          <div class="text-muted-small mb-2"><i class="fa-solid fa-ruler-combined me-1"></i>${room.area} m2 <span class="mx-2">|</span><i class="fa-solid fa-location-dot me-1"></i>${room.location}</div>
          <p class="mb-2">${shortText(room.description)}</p>
          <div class="mb-3">${room.utilities.slice(0, 4).map(u => `<span class="utility-badge">${u}</span>`).join("")}</div>
          <a class="btn btn-primary w-100" href="room-detail.html?id=${room.id}">Xem chi tiết</a>
        </div>
      </div>
    </div>`;
}

function initHome() {
  const activeRooms = getRooms().filter(r => r.status === "Đang hiển thị");
  let current = 1;
  const perPage = 6;
  const list = document.querySelector("#roomList");
  const pagination = document.querySelector("#pagination");

  function renderPage() {
    const start = (current - 1) * perPage;
    list.innerHTML = activeRooms.slice(start, start + perPage).map(roomCard).join("");
    const total = Math.ceil(activeRooms.length / perPage);
    pagination.innerHTML = `
      <li class="page-item ${current === 1 ? "disabled" : ""}"><button class="page-link" data-page="${current - 1}">Trang trước</button></li>
      ${Array.from({ length: total }, (_, i) => `<li class="page-item ${current === i + 1 ? "active" : ""}"><button class="page-link" data-page="${i + 1}">${i + 1}</button></li>`).join("")}
      <li class="page-item ${current === total ? "disabled" : ""}"><button class="page-link" data-page="${current + 1}">Trang sau</button></li>`;
  }
  pagination.addEventListener("click", e => {
    const page = Number(e.target.dataset.page);
    if (page) {
      current = page;
      renderPage();
      window.scrollTo({ top: list.offsetTop - 90, behavior: "smooth" });
    }
  });
  document.querySelector("#quickSearchForm").addEventListener("submit", e => {
    e.preventDefault();
    const q = new URLSearchParams(new FormData(e.target)).toString();
    location.href = `search.html?${q}`;
  });
  renderPage();
  renderRooms("#latestRooms", [...activeRooms].sort((a, b) => b.createdAt.localeCompare(a.createdAt)).slice(0, 4));
  renderRooms("#popularRooms", [...activeRooms].sort((a, b) => b.views - a.views).slice(0, 4));
  renderRooms("#nearRooms", activeRooms.filter(r => r.location.includes("Đại học Vinh")).slice(0, 4));
}

function renderRooms(selector, rooms) {
  const el = document.querySelector(selector);
  if (el) el.innerHTML = rooms.map(roomCard).join("");
}

function newCaptcha() {
  APP.currentCaptcha = Math.random().toString(36).slice(2, 7).toUpperCase();
  const el = document.querySelector("#captchaText");
  if (el) el.textContent = APP.currentCaptcha;
}

function initLogin() {
  newCaptcha();
  document.querySelector("#refreshCaptcha").addEventListener("click", newCaptcha);
  document.querySelector("#loginForm").addEventListener("submit", e => {
    e.preventDefault();
    const form = e.target;
    const loginId = form.loginId.value.trim();
    const password = form.password.value;
    const captcha = form.captcha.value.trim().toUpperCase();
    if (!loginId) return toast("Không được bỏ trống email hoặc số điện thoại", "danger");
    if (loginId.includes("@") && !isEmail(loginId)) return toast("Email không đúng định dạng", "danger");
    if (!password) return toast("Không được bỏ trống mật khẩu", "danger");
    if (captcha !== APP.currentCaptcha) return toast("Captcha không đúng", "danger");
    const user = getUsers().find(u => (u.email === loginId || u.phone === loginId) && u.password === password);
    if (!user) return toast("Thông tin đăng nhập chưa đúng", "danger");
    localStorage.setItem(APP.authKey, JSON.stringify({ id: user.id, name: user.name, email: user.email, role: user.role }));
    toast("Đăng nhập thành công", "success");
    setTimeout(() => location.href = "index.html", 900);
  });
}

function initRegister() {
  document.querySelector("#registerForm").addEventListener("submit", e => {
    e.preventDefault();
    const f = e.target;
    if (!f.name.value.trim()) return toast("Họ tên không được rỗng", "danger");
    if (!isEmail(f.email.value.trim())) return toast("Email không đúng định dạng", "danger");
    if (!isPhone(f.phone.value.trim())) return toast("Số điện thoại Việt Nam chưa hợp lệ", "danger");
    if (f.password.value.length < 6) return toast("Mật khẩu tối thiểu 6 ký tự", "danger");
    if (f.password.value !== f.confirmPassword.value) return toast("Nhập lại mật khẩu phải trùng", "danger");
    const users = getUsers();
    if (users.some(u => u.email === f.email.value.trim())) return toast("Email đã tồn tại", "danger");
    users.push({ id: Date.now(), name: f.name.value.trim(), email: f.email.value.trim(), phone: f.phone.value.trim(), role: "Người dùng", status: "Hoạt động", password: f.password.value, address: "" });
    setUsers(users);
    toast("Đăng ký thành công", "success");
    setTimeout(() => location.href = "login.html", 900);
  });
}

function initForgot() {
  document.querySelector("#forgotForm").addEventListener("submit", e => {
    e.preventDefault();
    if (!isEmail(e.target.email.value.trim())) return toast("Email không hợp lệ", "danger");
    toast("Yêu cầu reset mật khẩu đã được gửi. Vui lòng kiểm tra email.", "success");
    e.target.reset();
  });
}

function initAccount() {
  const auth = getAuth();
  const users = getUsers();
  const user = users.find(u => u.id === (auth && auth.id)) || users[1];
  const form = document.querySelector("#accountForm");
  form.name.value = user.name;
  form.email.value = user.email;
  form.phone.value = user.phone;
  form.address.value = user.address || "";
  document.querySelector("#roleText").textContent = user.role;
  const avatar = document.querySelector("#avatarPreview");
  const savedAvatar = localStorage.getItem("pt247_avatar");
  if (savedAvatar) avatar.src = savedAvatar;
  form.avatar.addEventListener("change", () => {
    const file = form.avatar.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => avatar.src = reader.result;
    reader.readAsDataURL(file);
  });
  form.addEventListener("submit", e => {
    e.preventDefault();
    if (!form.name.value.trim()) return toast("Họ tên không được rỗng", "danger");
    if (!isPhone(form.phone.value.trim())) return toast("Số điện thoại chưa hợp lệ", "danger");
    user.name = form.name.value.trim();
    user.phone = form.phone.value.trim();
    user.address = form.address.value.trim();
    setUsers(users);
    localStorage.setItem("pt247_avatar", avatar.src);
    if (auth) localStorage.setItem(APP.authKey, JSON.stringify({ ...auth, name: user.name }));
    toast("Lưu thay đổi thành công", "success");
    renderHeaderAuth();
  });
}

function initChangePassword() {
  document.querySelector("#changePasswordForm").addEventListener("submit", e => {
    e.preventDefault();
    const f = e.target;
    if (!f.currentPassword.value || !f.newPassword.value || !f.confirmPassword.value) return toast("Vui lòng nhập đầy đủ thông tin", "danger");
    if (f.newPassword.value.length < 6) return toast("Mật khẩu mới tối thiểu 6 ký tự", "danger");
    if (f.newPassword.value !== f.confirmPassword.value) return toast("Nhập lại mật khẩu mới phải trùng", "danger");
    toast("Đổi mật khẩu thành công", "success");
    f.reset();
  });
}

function initPostManage() {
  const body = document.querySelector("#postTableBody");
  function render() {
    body.innerHTML = getRooms().map((r, i) => `
      <tr>
        <td>${i + 1}</td><td><img class="table-thumb" src="${r.image}" alt=""></td><td>${r.title}</td><td>${money(r.price)}</td><td>${r.area} m2</td><td>${r.location}</td>
        <td><span class="status-badge ${statusClass(r.status)}">${r.status}</span></td>
        <td class="text-nowrap">
          <a class="btn btn-sm btn-outline-primary" href="room-detail.html?id=${r.id}"><i class="fa-solid fa-eye"></i></a>
          <a class="btn btn-sm btn-outline-warning" href="post-edit.html?id=${r.id}"><i class="fa-solid fa-pen"></i></a>
          <button class="btn btn-sm btn-outline-danger" data-delete="${r.id}"><i class="fa-solid fa-trash"></i></button>
          <button class="btn btn-sm btn-outline-secondary" data-toggle="${r.id}">${r.status === "Đã ẩn" ? "Hiện" : "Ẩn"}</button>
        </td>
      </tr>`).join("");
  }
  body.addEventListener("click", e => {
    const del = e.target.closest("[data-delete]");
    const tog = e.target.closest("[data-toggle]");
    let rooms = getRooms();
    if (del && confirm("Bạn có chắc muốn xóa tin đăng này?")) {
      rooms = rooms.filter(r => r.id !== Number(del.dataset.delete));
      setRooms(rooms);
      toast("Đã xóa tin đăng", "success");
      render();
    }
    if (tog) {
      const room = rooms.find(r => r.id === Number(tog.dataset.toggle));
      room.status = room.status === "Đã ẩn" ? "Đang hiển thị" : "Đã ẩn";
      setRooms(rooms);
      toast("Đã cập nhật trạng thái tin", "success");
      render();
    }
  });
  render();
}

function fillRoomForm(form, room) {
  form.title.value = room.title || "";
  form.address.value = room.address || "";
  form.location.value = room.location || "";
  form.price.value = room.price || "";
  form.area.value = room.area || "";
  form.phone.value = room.ownerPhone || "";
  form.description.value = room.description || "";
  form.querySelectorAll("[name='utilities']").forEach(cb => cb.checked = (room.utilities || []).includes(cb.value));
}

function readRoomForm(form) {
  return {
    title: form.title.value.trim(),
    address: form.address.value.trim(),
    location: form.location.value,
    price: Number(form.price.value),
    area: Number(form.area.value),
    ownerPhone: form.phone.value.trim(),
    description: form.description.value.trim(),
    utilities: [...form.querySelectorAll("[name='utilities']:checked")].map(i => i.value)
  };
}

function validateRoom(data) {
  if (!data.title) return "Tiêu đề không được rỗng";
  if (data.price <= 0) return "Giá phải là số dương";
  if (data.area <= 0) return "Diện tích phải là số dương";
  if (!isPhone(data.ownerPhone)) return "Số điện thoại liên hệ chưa hợp lệ";
  if (!data.description) return "Mô tả không được rỗng";
  return "";
}

function initPostCreate() {
  document.querySelector("#postForm").addEventListener("submit", e => {
    e.preventDefault();
    const data = readRoomForm(e.target);
    const error = validateRoom(data);
    if (error) return toast(error, "danger");
    const rooms = getRooms();
    rooms.unshift({ ...data, id: Date.now(), image: fallbackImg, views: 0, status: "Chờ duyệt", createdAt: new Date().toISOString().slice(0, 10), ownerName: (getAuth() || {}).name || "Người dùng mới" });
    setRooms(rooms);
    toast("Đăng tin thành công", "success");
    setTimeout(() => location.href = "post-manage.html", 900);
  });
}

function initPostEdit() {
  const rooms = getRooms();
  const id = Number(new URLSearchParams(location.search).get("id")) || rooms[0].id;
  const room = rooms.find(r => r.id === id) || rooms[0];
  const form = document.querySelector("#postForm");
  fillRoomForm(form, room);
  form.addEventListener("submit", e => {
    e.preventDefault();
    const data = readRoomForm(form);
    const error = validateRoom(data);
    if (error) return toast(error, "danger");
    Object.assign(room, data);
    setRooms(rooms);
    toast("Cập nhật tin đăng thành công", "success");
  });
}

function initRoomDetail() {
  const rooms = getRooms();
  const id = Number(new URLSearchParams(location.search).get("id")) || rooms[0].id;
  const room = rooms.find(r => r.id === id) || rooms[0];
  room.views += 1;
  setRooms(rooms);
  document.querySelector("#detailContent").innerHTML = `
    <div class="row g-4">
      <div class="col-lg-7">
        <img id="mainDetailImg" class="detail-main-img" src="${room.image}" alt="${room.title}">
        <div class="d-flex gap-2 mt-3 flex-wrap">${roomImages.slice(0, 5).map(img => `<img class="gallery-img" src="${img}" alt="Ảnh phòng">`).join("")}</div>
      </div>
      <div class="col-lg-5">
        <div class="info-card p-4 h-100">
          <h2 class="fw-bold">${room.title}</h2>
          <div class="room-price fs-4 mb-3">${money(room.price)}</div>
          <p><i class="fa-solid fa-ruler-combined me-2 text-primary"></i>${room.area} m2</p>
          <p><i class="fa-solid fa-location-dot me-2 text-primary"></i>${room.address}</p>
          <p><i class="fa-regular fa-calendar me-2 text-primary"></i>Ngày đăng: ${room.createdAt}</p>
          <p><i class="fa-solid fa-eye me-2 text-primary"></i>Lượt xem: ${room.views}</p>
          <p><span class="status-badge ${statusClass(room.status)}">${room.status}</span></p>
          <div class="mb-3">${room.utilities.map(u => `<span class="utility-badge">${u}</span>`).join("")}</div>
          <h5 class="fw-bold">Thông tin người đăng</h5>
          <p class="mb-1">${room.ownerName}</p>
          <p>${room.ownerPhone}</p>
          <a class="btn btn-success me-2" href="tel:${room.ownerPhone}"><i class="fa-solid fa-phone me-1"></i>Gọi điện</a>
          <a class="btn btn-outline-danger" href="report.html?room=${room.id}"><i class="fa-solid fa-flag me-1"></i>Gửi báo cáo đã thuê</a>
        </div>
      </div>
      <div class="col-12"><div class="info-card p-4"><h4 class="fw-bold">Mô tả chi tiết</h4><p class="mb-0">${room.description}</p></div></div>
    </div>`;
  document.querySelectorAll(".gallery-img").forEach(img => img.addEventListener("click", () => document.querySelector("#mainDetailImg").src = img.src));
  renderRooms("#relatedRooms", rooms.filter(r => r.id !== room.id && r.status === "Đang hiển thị").slice(0, 3));
}

function filterRooms() {
  const f = document.querySelector("#searchForm");
  const fd = new FormData(f);
  const keyword = (fd.get("keyword") || "").toLowerCase();
  const locationText = (fd.get("location") || "").toLowerCase();
  const priceRange = fd.get("priceRange");
  const areaRange = fd.get("areaRange");
  const utilities = fd.getAll("utilities");
  let rooms = getRooms().filter(r => r.status === "Đang hiển thị");
  rooms = rooms.filter(r => {
    const text = `${r.title} ${r.address} ${r.location} ${r.description}`.toLowerCase();
    const priceOk = !priceRange || (priceRange === "low" && r.price < 2000000) || (priceRange === "mid" && r.price >= 2000000 && r.price <= 3000000) || (priceRange === "high" && r.price > 3000000);
    const areaOk = !areaRange || (areaRange === "small" && r.area < 20) || (areaRange === "medium" && r.area >= 20 && r.area <= 30) || (areaRange === "large" && r.area > 30);
    const utilityOk = utilities.every(u => r.utilities.includes(u));
    return (!keyword || text.includes(keyword)) && (!locationText || r.location.toLowerCase().includes(locationText) || r.address.toLowerCase().includes(locationText)) && priceOk && areaOk && utilityOk;
  });
  const sort = document.querySelector("#sortSelect").value;
  if (sort === "newest") rooms.sort((a, b) => b.createdAt.localeCompare(a.createdAt));
  if (sort === "priceAsc") rooms.sort((a, b) => a.price - b.price);
  if (sort === "priceDesc") rooms.sort((a, b) => b.price - a.price);
  if (sort === "areaDesc") rooms.sort((a, b) => b.area - a.area);
  return rooms;
}

function renderSearchResults() {
  const rooms = filterRooms();
  document.querySelector("#resultCount").textContent = `${rooms.length} kết quả tìm thấy`;
  document.querySelector("#searchResults").innerHTML = rooms.length ? rooms.map(roomCard).join("") : `<div class="col-12"><div class="alert alert-warning">Không tìm thấy phòng trọ phù hợp.</div></div>`;
}

function initSearch() {
  const params = new URLSearchParams(location.search);
  document.querySelector("#searchForm").keyword.value = params.get("keyword") || "";
  document.querySelector("#searchForm").location.value = params.get("location") || "";
  document.querySelector("#searchForm").priceRange.value = params.get("priceRange") || "";
  document.querySelector("#searchForm").areaRange.value = params.get("areaRange") || "";
  document.querySelector("#searchForm").addEventListener("submit", e => {
    e.preventDefault();
    renderSearchResults();
  });
  document.querySelector("#sortSelect").addEventListener("change", renderSearchResults);
  document.querySelector("#clearFilters").addEventListener("click", () => {
    document.querySelector("#searchForm").reset();
    renderSearchResults();
  });
  renderSearchResults();
}

function validateContactForm(form) {
  if (!form.name.value.trim()) return "Họ tên không được rỗng";
  if (!isEmail(form.email.value.trim())) return "Email không hợp lệ";
  if (!isPhone(form.phone.value.trim())) return "Số điện thoại không hợp lệ";
  if (!form.message.value.trim()) return "Nội dung không được rỗng";
  return "";
}

function initContact() {
  document.querySelector("#contactForm").addEventListener("submit", e => {
    e.preventDefault();
    const error = validateContactForm(e.target);
    if (error) return toast(error, "danger");
    toast("Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất.", "success");
    e.target.reset();
  });
}

function initReport() {
  const select = document.querySelector("#reportRoom");
  select.innerHTML = getRooms().map(r => `<option value="${r.id}">${r.title}</option>`).join("");
  const roomId = new URLSearchParams(location.search).get("room");
  if (roomId) select.value = roomId;
  document.querySelector("#reportForm").addEventListener("submit", e => {
    e.preventDefault();
    const error = validateContactForm(e.target);
    if (error) return toast(error, "danger");
    toast("Cảm ơn bạn đã gửi báo cáo. Chúng tôi sẽ kiểm tra thông tin.", "success");
    e.target.reset();
  });
}

// Admin tab logic removed — admin UI now uses independent HTML pages
