const APP = {
  roomsKey: "pt247_rooms",
  usersKey: "pt247_users",
  authKey: "pt247_auth",
  currentCaptcha: ""
};

const fallbackImg = "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80";
const roomImages = [
  "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80",
  "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=80",
  "https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=900&q=80",
  "https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=900&q=80",
  "https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=900&q=80",
  "https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=900&q=80"
];

const sampleRooms = [
  { id: 1, title: "Phòng trọ mới gần Đại học Vinh", address: "182 Lê Duẩn, TP Vinh", location: "Gần Đại học Vinh", price: 1800000, area: 22, image: roomImages[0], description: "Phòng mới, sạch sẽ, có ban công thoáng, phù hợp sinh viên.", utilities: ["Wifi", "Chỗ để xe", "Nhà vệ sinh riêng"], views: 245, status: "Đang hiển thị", createdAt: "2026-05-02", ownerName: "Nguyễn Minh Anh", ownerPhone: "0912345678" },
  { id: 2, title: "Phòng có gác lửng phường Bến Thủy", address: "55 Nguyễn Du, Bến Thủy", location: "Phường Bến Thủy", price: 2300000, area: 28, image: roomImages[1], description: "Có gác lửng rộng, khu dân cư an ninh, gần chợ và bến xe.", utilities: ["Wifi", "Gác lửng", "Không chung chủ"], views: 198, status: "Đang hiển thị", createdAt: "2026-04-29", ownerName: "Trần Văn Hòa", ownerPhone: "0987654321" },
  { id: 3, title: "Căn hộ mini trung tâm TP Vinh", address: "21 Quang Trung, TP Vinh", location: "Trung tâm TP Vinh", price: 3500000, area: 35, image: roomImages[2], description: "Căn hộ mini nội thất cơ bản, thuận tiện đi lại, giờ giấc tự do.", utilities: ["Wifi", "Máy lạnh", "Chỗ để xe", "Không chung chủ"], views: 321, status: "Đang hiển thị", createdAt: "2026-05-06", ownerName: "Lê Thu Trang", ownerPhone: "0905123456" },
  { id: 4, title: "Phòng giá tốt phường Trường Thi", address: "90 Phong Định Cảng", location: "Phường Trường Thi", price: 1500000, area: 18, image: roomImages[3], description: "Phòng nhỏ gọn, sạch, gần tuyến xe buýt, phù hợp một người ở.", utilities: ["Wifi", "Chỗ để xe"], views: 142, status: "Chờ duyệt", createdAt: "2026-05-09", ownerName: "Phạm Quốc Bảo", ownerPhone: "0933445566" },
  { id: 5, title: "Phòng rộng có máy lạnh Hưng Dũng", address: "33 Nguyễn Sỹ Sách", location: "Phường Hưng Dũng", price: 2700000, area: 30, image: roomImages[4], description: "Phòng thoáng, có máy lạnh, vệ sinh riêng, khu vực yên tĩnh.", utilities: ["Wifi", "Máy lạnh", "Nhà vệ sinh riêng"], views: 276, status: "Đang hiển thị", createdAt: "2026-05-01", ownerName: "Hoàng Gia Hân", ownerPhone: "0977222333" },
  { id: 6, title: "Phòng trọ khép kín gần cổng trường", address: "12 Bạch Liêu, TP Vinh", location: "Gần Đại học Vinh", price: 2100000, area: 24, image: roomImages[5], description: "Khép kín, có sân phơi riêng, cách Đại học Vinh khoảng 700m.", utilities: ["Wifi", "Chỗ để xe", "Nhà vệ sinh riêng", "Không chung chủ"], views: 410, status: "Đang hiển thị", createdAt: "2026-04-25", ownerName: "Bùi Thanh Tùng", ownerPhone: "0966888999" },
  { id: 7, title: "Phòng studio nhỏ đầy đủ tiện ích", address: "8 Hồ Tùng Mậu", location: "Trung tâm TP Vinh", price: 3200000, area: 32, image: roomImages[0], description: "Studio có bếp nhỏ, máy lạnh, wifi mạnh, gần siêu thị.", utilities: ["Wifi", "Máy lạnh", "Chỗ để xe", "Nhà vệ sinh riêng"], views: 189, status: "Đã thuê", createdAt: "2026-04-18", ownerName: "Võ Khánh Linh", ownerPhone: "0911777888" },
  { id: 8, title: "Phòng sinh viên an ninh Bến Thủy", address: "64 Trần Phú", location: "Phường Bến Thủy", price: 1700000, area: 20, image: roomImages[1], description: "Khu nhà trọ có camera, chủ thân thiện, gần hàng quán sinh viên.", utilities: ["Wifi", "Chỗ để xe"], views: 156, status: "Đang hiển thị", createdAt: "2026-05-11", ownerName: "Đặng Thùy Dương", ownerPhone: "0944556677" },
  { id: 9, title: "Phòng cao cấp không chung chủ", address: "17 Lý Thường Kiệt", location: "Phường Trường Thi", price: 2900000, area: 31, image: roomImages[2], description: "Không chung chủ, ra vào bằng vân tay, có khu giặt phơi riêng.", utilities: ["Wifi", "Máy lạnh", "Không chung chủ", "Nhà vệ sinh riêng"], views: 367, status: "Đang hiển thị", createdAt: "2026-05-10", ownerName: "Mai Đức Nam", ownerPhone: "0888123456" },
  { id: 10, title: "Phòng tiết kiệm Hưng Dũng", address: "42 Lê Mao", location: "Phường Hưng Dũng", price: 1300000, area: 16, image: roomImages[3], description: "Phòng cơ bản, sạch, có wifi, phù hợp sinh viên cần tiết kiệm.", utilities: ["Wifi"], views: 94, status: "Đã ẩn", createdAt: "2026-04-16", ownerName: "Ngô Thanh Hà", ownerPhone: "0922111444" },
  { id: 11, title: "Nhà trọ mới xây gần ĐH Vinh", address: "5 Nguyễn Văn Cừ", location: "Gần Đại học Vinh", price: 2500000, area: 27, image: roomImages[4], description: "Nhà mới xây, phòng sáng, gần trường, có chỗ để xe rộng.", utilities: ["Wifi", "Chỗ để xe", "Gác lửng", "Nhà vệ sinh riêng"], views: 233, status: "Chờ duyệt", createdAt: "2026-05-12", ownerName: "Phan Bảo Ngọc", ownerPhone: "0909888777" },
  { id: 12, title: "Phòng trung tâm có ban công", address: "70 Nguyễn Thị Minh Khai", location: "Trung tâm TP Vinh", price: 2600000, area: 26, image: roomImages[5], description: "Ban công rộng, thoáng mát, đầy đủ tiện ích cơ bản.", utilities: ["Wifi", "Máy lạnh", "Chỗ để xe"], views: 298, status: "Đang hiển thị", createdAt: "2026-05-07", ownerName: "Đỗ Tuấn Kiệt", ownerPhone: "0933999888" }
];

const sampleUsers = [
  { id: 1, name: "Admin PHONGTRO247", email: "admin@phongtro247.vn", phone: "0900000001", role: "Admin", status: "Hoạt động", password: "123456", address: "TP Vinh" },
  { id: 2, name: "Nguyễn Minh Anh", email: "minhanh@gmail.com", phone: "0912345678", role: "Người dùng", status: "Hoạt động", password: "123456", address: "Gần Đại học Vinh" },
  { id: 3, name: "Trần Văn Hòa", email: "vanhoa@gmail.com", phone: "0987654321", role: "Người dùng", status: "Hoạt động", password: "123456", address: "Bến Thủy" },
  { id: 4, name: "Lê Thu Trang", email: "thutrang@gmail.com", phone: "0905123456", role: "Người dùng", status: "Hoạt động", password: "123456", address: "Trường Thi" },
  { id: 5, name: "Phạm Quốc Bảo", email: "quocbao@gmail.com", phone: "0933445566", role: "Người dùng", status: "Đã khóa", password: "123456", address: "Hưng Dũng" },
  { id: 6, name: "Hoàng Gia Hân", email: "giahan@gmail.com", phone: "0977222333", role: "Người dùng", status: "Hoạt động", password: "123456", address: "TP Vinh" }
];

document.addEventListener("DOMContentLoaded", () => {
  seedData();
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
    report: initReport,
    admin: initAdmin
  };
  if (handlers[page]) handlers[page]();
});

function seedData() {
  if (!localStorage.getItem(APP.roomsKey)) localStorage.setItem(APP.roomsKey, JSON.stringify(sampleRooms));
  if (!localStorage.getItem(APP.usersKey)) localStorage.setItem(APP.usersKey, JSON.stringify(sampleUsers));
}

function getRooms() {
  return JSON.parse(localStorage.getItem(APP.roomsKey) || "[]");
}

function setRooms(rooms) {
  localStorage.setItem(APP.roomsKey, JSON.stringify(rooms));
}

function getUsers() {
  return JSON.parse(localStorage.getItem(APP.usersKey) || "[]");
}

function setUsers(users) {
  localStorage.setItem(APP.usersKey, JSON.stringify(users));
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
          <li><a class="dropdown-item" href="admin.html">Admin</a></li>
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

function initAdmin() {
  const sections = document.querySelectorAll(".admin-section");
  document.querySelectorAll("[data-admin-tab]").forEach(btn => btn.addEventListener("click", () => {
    document.querySelectorAll("[data-admin-tab]").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    sections.forEach(s => s.classList.toggle("d-none", s.id !== btn.dataset.adminTab));
    updateAdminStats();
  }));
  renderAdminUsers();
  renderAdminPosts();
  updateAdminStats();
  initAdminModals();
}

function updateAdminStats() {
  const rooms = getRooms();
  const users = getUsers();
  const counts = {
    totalUsers: users.length,
    totalRooms: rooms.length,
    activeRooms: rooms.filter(r => r.status === "Đang hiển thị").length,
    pendingRooms: rooms.filter(r => r.status === "Chờ duyệt").length,
    hiddenRooms: rooms.filter(r => r.status === "Đã ẩn").length
  };
  Object.entries(counts).forEach(([k, v]) => {
    document.querySelectorAll(`[data-stat="${k}"]`).forEach(el => el.textContent = v);
  });
  const statTable = document.querySelector("#adminStatsTable");
  if (statTable) {
    const statuses = ["Đang hiển thị", "Chờ duyệt", "Đã ẩn", "Đã thuê"];
    const total = rooms.length || 1;
    statTable.innerHTML = statuses.map(s => `<tr><td>${s}</td><td>${rooms.filter(r => r.status === s).length}</td><td><div class="progress"><div class="progress-bar" style="width:${Math.max(8, rooms.filter(r => r.status === s).length / total * 100)}%"></div></div></td></tr>`).join("");
  }
  const top = document.querySelector("#topViews");
  if (top) top.innerHTML = [...rooms].sort((a, b) => b.views - a.views).slice(0, 5).map(r => `<li class="list-group-item d-flex justify-content-between"><span>${r.title}</span><strong>${r.views}</strong></li>`).join("");
}

function renderAdminUsers() {
  const body = document.querySelector("#adminUsersBody");
  body.innerHTML = getUsers().map((u, i) => `
    <tr><td>${i + 1}</td><td>${u.name}</td><td>${u.email}</td><td>${u.phone}</td><td>${u.role}</td><td>${u.status}</td>
    <td class="text-nowrap"><button class="btn btn-sm btn-outline-primary" data-edit-user="${u.id}">Sửa</button> <button class="btn btn-sm btn-outline-warning" data-reset-user="${u.id}">Reset</button> <button class="btn btn-sm btn-outline-danger" data-lock-user="${u.id}">${u.status === "Đã khóa" ? "Mở khóa" : "Khóa"}</button></td></tr>`).join("");
  body.onclick = e => {
    const users = getUsers();
    const edit = e.target.closest("[data-edit-user]");
    const reset = e.target.closest("[data-reset-user]");
    const lock = e.target.closest("[data-lock-user]");
    if (edit) openUserModal(users.find(u => u.id === Number(edit.dataset.editUser)));
    if (reset && confirm("Reset mật khẩu tài khoản này?")) toast("Reset mật khẩu thành công", "success");
    if (lock) {
      const user = users.find(u => u.id === Number(lock.dataset.lockUser));
      user.status = user.status === "Đã khóa" ? "Hoạt động" : "Đã khóa";
      setUsers(users);
      renderAdminUsers();
      updateAdminStats();
    }
  };
}

function renderAdminPosts() {
  const body = document.querySelector("#adminPostsBody");
  body.innerHTML = getRooms().map((r, i) => `
    <tr><td>${i + 1}</td><td>${r.title}</td><td>${r.ownerName}</td><td>${money(r.price)}</td><td>${r.location}</td><td><span class="status-badge ${statusClass(r.status)}">${r.status}</span></td><td>${r.createdAt}</td>
    <td><select class="form-select form-select-sm" data-room-status="${r.id}">${["Chờ duyệt", "Đang hiển thị", "Đã ẩn", "Đã thuê"].map(s => `<option ${s === r.status ? "selected" : ""}>${s}</option>`).join("")}</select></td>
    <td class="text-nowrap"><a class="btn btn-sm btn-outline-primary" href="post-edit.html?id=${r.id}">Sửa</a> <button class="btn btn-sm btn-outline-secondary" data-admin-hide="${r.id}">${r.status === "Đã ẩn" ? "Hiện" : "Ẩn"}</button></td></tr>`).join("");
  body.onchange = e => {
    const select = e.target.closest("[data-room-status]");
    if (!select) return;
    const rooms = getRooms();
    const room = rooms.find(r => r.id === Number(select.dataset.roomStatus));
    room.status = select.value;
    setRooms(rooms);
    renderAdminPosts();
    updateAdminStats();
    toast("Đã cập nhật trạng thái tin đăng", "success");
  };
  body.onclick = e => {
    const btn = e.target.closest("[data-admin-hide]");
    if (!btn) return;
    const rooms = getRooms();
    const room = rooms.find(r => r.id === Number(btn.dataset.adminHide));
    room.status = room.status === "Đã ẩn" ? "Đang hiển thị" : "Đã ẩn";
    setRooms(rooms);
    renderAdminPosts();
    updateAdminStats();
    toast("Đã cập nhật trạng thái tin đăng", "success");
  };
}

function initAdminModals() {
  document.querySelector("#addUserBtn").addEventListener("click", () => openUserModal());
  document.querySelector("#adminUserForm").addEventListener("submit", e => {
    e.preventDefault();
    const f = e.target;
    if (!f.name.value.trim() || !isEmail(f.email.value.trim()) || !isPhone(f.phone.value.trim())) return toast("Vui lòng nhập đúng thông tin tài khoản", "danger");
    const users = getUsers();
    const id = Number(f.userId.value);
    if (id) {
      const user = users.find(u => u.id === id);
      Object.assign(user, { name: f.name.value.trim(), email: f.email.value.trim(), phone: f.phone.value.trim(), role: f.role.value, status: f.status.value });
    } else {
      users.push({ id: Date.now(), name: f.name.value.trim(), email: f.email.value.trim(), phone: f.phone.value.trim(), role: f.role.value, status: f.status.value, password: "123456", address: "" });
    }
    setUsers(users);
    bootstrap.Modal.getInstance(document.querySelector("#userModal")).hide();
    renderAdminUsers();
    updateAdminStats();
    toast("Đã lưu tài khoản", "success");
  });
}

function openUserModal(user) {
  const f = document.querySelector("#adminUserForm");
  f.reset();
  f.userId.value = user ? user.id : "";
  f.name.value = user ? user.name : "";
  f.email.value = user ? user.email : "";
  f.phone.value = user ? user.phone : "";
  f.role.value = user ? user.role : "Người dùng";
  f.status.value = user ? user.status : "Hoạt động";
  document.querySelector("#userModalTitle").textContent = user ? "Sửa tài khoản" : "Thêm tài khoản";
  new bootstrap.Modal(document.querySelector("#userModal")).show();
}
