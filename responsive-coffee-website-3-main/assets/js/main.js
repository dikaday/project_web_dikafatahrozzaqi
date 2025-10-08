/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/* Menu show */
if(navToggle){
    navToggle.addEventListener('click', () =>{
        navMenu.classList.add('show-menu')
    })
}

/* Menu hidden */
if(navClose){
    navClose.addEventListener('click', () =>{
        navMenu.classList.remove('show-menu')
    })
}
/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav__link')

const linkAction = () =>{
    const navMenu = document.getElementById('nav-menu')
    navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/*=============== ADD SHADOW HEADER ===============*/
const shadowHeader = () =>{
    const header = document.getElementById('header')
    this.scrollY >= 50 ? header.classList.add('shadow-header')
                       : header.classList.remove('shadow-header')
}
window.addEventListener('scroll', shadowHeader)

/*=============== SWIPER POPULAR ===============*/
const swipePopular = new Swiper('.popular__swiper', {
  loop: true,
  grabCursor: true,
  spaceBetween: 32,
  slidesPerView: 'auto',
  centeredSlides: 'auto',
  breakpoints:{
    1150:{
        spaceBetween: 80,
    }
  }
})

/*=============== SHOW SCROLL UP ===============*/
const scrollUp = () =>{
    const scrollUp = document.getElementById('scroll-up')
    this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
                        : scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')

const scrollActive = () =>{
      const scrollDown = window.scrollY
    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id'),
              sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

        if(scrollDown > sectionTop && scrollDown <= sectionTop + sectionHeight){
            sectionsClass.classList.add('active-link')
        }else{
            sectionsClass.classList.remove('active-link')
        }
    })
}
window.addEventListener('scroll', scrollActive)

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 2000,
    delay: 300,
})

sr.reveal('.popular__swiper, .footer__container')
sr.reveal('.home__shape', {origin: 'bottom'})
sr.reveal('.home__coffee', {delay: 1000, distance: '200px', duration: 1500})
sr.reveal('.home__splash', {delay: 1600, scale: 0, duration : 1500})
sr.reveal('.home__bean-1, .home__bean-2', {delay: 2200, scale: 0, duration: 1500, rotate: {z: 180}})
sr.reveal('.home__ice-1, .home__ice-2', {delay: 2600, scale: 0, duration: 1500, rotate: {z: 180}})
sr.reveal('.home__leaf', {delay: 2800, scale: 0, duration: 1500, rotate: {z: 90}})
sr.reveal('.home__title', {delay: 3500})
sr.reveal('.home__data, .home__sticker',  {delay: 4000})
sr.reveal('.about__data', {origin: 'left'})
sr.reveal('.about__images', {origin: 'right'})
sr.reveal('.about__coffee', {delay: 1000})
sr.reveal('.about__leaf-1, .about__leaf-2', {delay: 1400, rotate: {z: 9}})
sr.reveal('.products__card, .contact__info', {interval: 100})
sr.reveal('.contact__shape', {delay: 600, scale: 0})
sr.reveal('.contact__delivery', {delay: 1200,})

/*=============== CART & AUTH FUNCTIONS ===============*/
const CartManager = {
    isProcessing: false,
    showNotification: function(message, type = 'success') {
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.classList.add('show'), 100);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 400);
        }, 3000);
    },
    updateCartCounter: function() {
        fetch('cart.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', }, body: 'action=get_cart' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const counter = document.getElementById('cart-counter');
                const totalItems = data.data.total_item;
                counter.textContent = totalItems;
                if (totalItems > 0) { counter.classList.add('show'); } 
                else { counter.classList.remove('show'); }
            }
        });
    },
    addToCart: function(productId, quantity = 1) {
        fetch('cart.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', }, body: `action=add&id_produk=${productId}&jumlah=${quantity}` })
        .then(response => response.json())
        .then(data => {
            if (data.success) { this.showNotification(data.message, 'success'); this.updateCartCounter(); } 
            else { this.showNotification(data.message, 'error'); }
        });
    },
    showCartModal: function() {
        const existingModal = document.querySelector('.cart-modal');
        if (existingModal) existingModal.remove();
        fetch('cart.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', }, body: 'action=get_cart' })
        .then(response => response.json())
        .then(data => {
            if (data.success) { this.createCartModal(data.data); }
        })
    },
    createCartModal: function(cartData) {
        const modal = document.createElement('div');
        modal.className = 'cart-modal';
        let cartItemsHTML = '';
        if (cartData.items.length === 0) {
            cartItemsHTML = '<p style="text-align: center; color: var(--text-color); padding: 2rem;">Shopping cart is empty.</p>';
        } else {
            cartData.items.forEach(item => {
                cartItemsHTML += `
                    <div class="cart-item">
                        <div class="cart-item-image"><img src="${item.gambar}.png" alt="${item.nama_produk}"></div>
                        <div class="cart-item-info">
                            <h4 class="cart-item-name">${item.nama_produk}</h4>
                            <p class="cart-item-price">Rp ${item.harga.toLocaleString('id-ID')}</p>
                        </div>
                        <div class="cart-item-controls">
                            <button class="cart-btn" onclick="CartManager.updateQuantity(${item.id_keranjang}, ${item.jumlah - 1})">-</button>
                            <span class="cart-quantity">${item.jumlah}</span>
                            <button class="cart-btn" onclick="CartManager.updateQuantity(${item.id_keranjang}, ${item.jumlah + 1})">+</button>
                            <i class="ri-delete-bin-line cart-remove" onclick="CartManager.removeItem(${item.id_keranjang})"></i>
                        </div>
                    </div>`;
            });
        }
        modal.innerHTML = `
            <div class="cart-content">
                <div class="cart-header"><h3 class="cart-title">Shopping cart</h3><i class="ri-close-line cart-close" onclick="CartManager.closeCartModal()"></i></div>
                <div class="cart-items">${cartItemsHTML}</div>
                ${cartData.items.length > 0 ? `
                    <div class="cart-summary">
                        <div class="cart-total"><span>Total:</span><span>Rp ${cartData.total_harga.toLocaleString('id-ID')}</span></div>
                        <div class="cart-actions">
                            <button class="cart-btn-secondary" onclick="CartManager.clearCart()">Clear Cart </button>
                            <button class="button" onclick="CartManager.checkout()">Checkout</button>
                        </div>
                    </div>` : ''}
            </div>`;
        document.body.appendChild(modal);
        setTimeout(() => modal.classList.add('show'), 100);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) this.closeCartModal();
        });
    },
    closeCartModal: function() {
        const modal = document.querySelector('.cart-modal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 400);
        }
    },
    updateQuantity: function(cartId, newQuantity) {
        if (newQuantity < 1) { this.removeItem(cartId); return; }
        fetch('cart.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', }, body: `action=update&id_keranjang=${cartId}&jumlah=${newQuantity}` })
        .then(response => response.json())
        .then(data => {
            if (data.success) { this.showCartModal(); this.updateCartCounter(); } 
            else { this.showNotification(data.message, 'error'); }
        });
    },
    removeItem: function(cartId) {
        fetch('cart.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', }, body: `action=remove&id_keranjang=${cartId}` })
        .then(response => response.json())
        .then(data => {
            if (data.success) { this.showNotification(data.message, 'success'); this.showCartModal(); this.updateCartCounter(); } 
            else { this.showNotification(data.message, 'error'); }
        });
    },
    clearCart: function() {
        if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
            fetch('cart.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', }, body: 'action=clear_cart' })
            .then(response => response.json())
            .then(data => {
                if (data.success) { this.showNotification(data.message, 'success'); this.closeCartModal(); this.updateCartCounter(); } 
                else { this.showNotification(data.message, 'error'); }
            });
        }
    },
    checkout: function() {
    if (IS_LOGGED_IN) {
        // Jika sudah login, langsung proses seperti biasa
        this.processCheckout();
    } else {
        // Tutup modal keranjang terlebih dahulu agar tidak tumpang tindih
        this.closeCartModal();

        // Baru tampilkan modal untuk mengisi data diri
        const guestModal = document.getElementById('guest-checkout-modal');
        guestModal.classList.add('show-modal');
    }
},
processCheckout: function(formData = null) {
    // --- LANGKAH PENTING 1: PERIKSA KUNCI ---
    // Jika sedang memproses, hentikan panggilan fungsi baru manapun.
    if (this.isProcessing) {
        return; 
    }
    // --- KUNCI FUNGSI AGAR TIDAK BISA DIJALANKAN LAGI ---
    this.isProcessing = true; 

    let buttonToUpdate;
    if (formData) {
        buttonToUpdate = document.querySelector('#guest-checkout-form button');
    } else {
        buttonToUpdate = document.querySelector('.cart-actions .button');
    }

    if (!buttonToUpdate) {
        this.isProcessing = false; // Buka kunci jika tombol tidak ditemukan
        return; 
    }

    const originalButtonText = buttonToUpdate.innerHTML;
    buttonToUpdate.disabled = true;
    buttonToUpdate.innerHTML = 'Memproses...';

    fetch('checkout.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            this.showNotification(data.message, 'success');
            this.updateCartCounter();
            setTimeout(() => {
                window.location.href = `struk.php?id_pesanan=${data.order_id}`;
                // Kunci akan otomatis terbuka saat halaman berpindah, jadi tidak perlu di-reset di sini.
            }, 1500);
        } else {
            this.showNotification(data.message, 'error');
            buttonToUpdate.disabled = false;
            buttonToUpdate.innerHTML = originalButtonText;
            this.isProcessing = false; // BUKA KUNCI JIKA GAGAL
        }
    })
    .catch(error => {
        this.showNotification('Terjadi kesalahan koneksi. Coba lagi.', 'error');
        buttonToUpdate.disabled = false;
        buttonToUpdate.innerHTML = originalButtonText;
        this.isProcessing = false; // BUKA KUNCI JIKA ADA ERROR
    });
}
};

document.addEventListener('DOMContentLoaded', function() {
    CartManager.updateCartCounter();
    
    const cartToggle = document.getElementById('cart-toggle');
    if (cartToggle) {
        cartToggle.addEventListener('click', () => CartManager.showCartModal());
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.products__button')) {
            e.preventDefault();
            const productCard = e.target.closest('.products__card');
            if (productCard) {
                const productId = productCard.dataset.productId;
                if (productId) CartManager.addToCart(productId);
            }
        }

    // Tambahkan ini di dalam document.addEventListener('DOMContentLoaded', ...);

// GUEST CHECKOUT MODAL LOGIC
const guestModal = document.getElementById('guest-checkout-modal');
const guestForm = document.getElementById('guest-checkout-form');
const closeGuestModal = document.getElementById('guest-modal-close');

if (guestModal) {
    closeGuestModal.addEventListener('click', () => {
        guestModal.classList.remove('show-modal');
    });

    guestModal.addEventListener('click', (e) => {
        if (e.target === guestModal) {
            guestModal.classList.remove('show-modal');
        }
    });

    guestForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        CartManager.processCheckout(formData);
    });
}
    });

    // AUTH MODAL LOGIC
    const authModal = document.getElementById('auth-modal');
    const loginLink = document.getElementById('login-link');
    const closeButtons = document.querySelectorAll('.auth__modal-close');
    const loginContainer = document.getElementById('login-container');
    const registerContainer = document.getElementById('register-container');
    const showRegisterLink = document.getElementById('show-register-link');
    const showLoginLink = document.getElementById('show-login-link');

    if (loginLink) {
        loginLink.addEventListener('click', (e) => {
            e.preventDefault();
            authModal.classList.add('show-modal');
            loginContainer.classList.remove('hidden');
            registerContainer.classList.add('hidden');
        });
    }
    
    closeButtons.forEach(button => {
        button.addEventListener('click', () => authModal.classList.remove('show-modal'));
    });

    authModal.addEventListener('click', (e) => {
        if (e.target === authModal) authModal.classList.remove('show-modal');
    });

    if (showRegisterLink) {
        showRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            loginContainer.classList.add('hidden');
            registerContainer.classList.remove('hidden');
        });
    }

    if (showLoginLink) {
        showLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            registerContainer.classList.add('hidden');
            loginContainer.classList.remove('hidden');
        });
    }

    // Login Form Submission
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('login-message');

            fetch('proses_login.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'auth__message error';
                    messageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                messageDiv.textContent = 'Terjadi kesalahan. Coba lagi.';
                messageDiv.className = 'auth__message error';
                messageDiv.style.display = 'block';
            });
        });
    }

    // Register Form Submission
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('register-message');

            fetch('proses_register.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                messageDiv.textContent = data.message;
                if (data.success) {
                    messageDiv.className = 'auth__message success';
                    setTimeout(() => {
                         registerContainer.classList.add('hidden');
                         loginContainer.classList.remove('hidden');
                    }, 2000);
                } else {
                    messageDiv.className = 'auth__message error';
                }
                messageDiv.style.display = 'block';
            })
            .catch(error => {
                messageDiv.textContent = 'Terjadi kesalahan. Coba lagi.';
                messageDiv.className = 'auth__message error';
                messageDiv.style.display = 'block';
            });
        });
    }
});