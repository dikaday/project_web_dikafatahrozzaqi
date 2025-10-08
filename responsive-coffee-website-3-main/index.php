<?php session_start(); ?>
<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!--=============== FAVICON ===============-->
      <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

      <!--=============== REMIXICONS ===============-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">

      <!--=============== SWIPER CSS ===============-->
      <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

      <!--=============== CSS ===============-->
      <link rel="stylesheet" href="assets/css/styles.css">

      <script>
         const IS_LOGGED_IN = <?php echo isset($_SESSION['id_pelanggan']) ? 'true' : 'false'; ?>;
      </script>

      <title>Responsive coffee website</title>
   </head>
   <body>
      <!--==================== HEADER (MODIFIKASI) ====================-->
<header class="header" id="header">
         <nav class="nav container">
            <a href="index.php" class="nav__logo">STARCOFFE</a>

            <div class="nav__menu" id="nav-menu">
               <ul class="nav__list">
                  <li><a href="#home" class="nav__link active-link">HOME</a></li>
                  <li><a href="#popular" class="nav__link">POPULAR</a></li>
                  <li><a href="#about" class="nav__link">ABOUT US</a></li>
                  <li><a href="#products" class="nav__link">PRODUCTS</a></li>
                  <li><a href="#contact" class="nav__link">CONTACT</a></li>
                  
                  <?php // BAGIAN LOGIKA LOGIN DIMULAI DI SINI ?>
                  <?php if (isset($_SESSION['id_pelanggan'])): ?>
                     <?php // JIKA SUDAH LOGIN, TAMPILKAN INI ?>
                     <li><a href="riwayat.php" class="nav__link">HISTORY</a></li>
                     <li><a href="logout.php" class="nav__link">LOGOUT</a></li>
                  <?php else: ?>
                     <?php // JIKA BELUM LOGIN, TAMPILKAN INI ?>
                     <li><a href="#" class="nav__link" id="login-link">LOGIN</a></li>
                  <?php endif; ?>
                  <?php // BAGIAN LOGIKA LOGIN SELESAI ?>
               </ul>

               <div class="nav__close" id="nav-close">
                  <i class="ri-close-large-line"></i>
               </div>
            </div>

            <?php // Tampilkan sapaan jika user sudah login ?>
            <?php if (isset($_SESSION['id_pelanggan'])): ?>
               <div style="color: white; margin-right: 1rem; font-family: var(--body-font);">
                  Halo, <?php echo htmlspecialchars($_SESSION['nama_pelanggan']); ?>!
               </div>
            <?php endif; ?>

            <div class="nav__cart">
               <i class="ri-shopping-cart-line" id="cart-toggle"></i>
               <span class="nav__cart-counter" id="cart-counter">0</span>
            </div>

            <div class="nav__toggle" id="nav-toggle">
               <i class="ri-apps-2-fill"></i>
            </div>
         </nav>
      </header>

      <!--==================== MAIN ====================-->
      <main class="main">
         <!--==================== HOME ====================-->
         <section class="home section" id="home">
            <div class="home__container container grid">
               <h1 class="home__title">COLD COFFEE</h1>

               <div class="home__images">
                  <div class="home__shape"></div>
                     <img src="assets/img/home-splash.png" alt="image" class="home__splash">
                     <img src="assets/img/bean-img.png" alt="image" class="home__bean-2">
                     <img src="assets/img/home-coffee.png" alt="image" class="home__coffee">
                     <img src="assets/img/bean-img.png" alt="image" class="home__bean-1">
                     <img src="assets/img/ice-img.png" alt="image" class="home__ice-1">
                     <img src="assets/img/ice-img.png" alt="image" class="home__ice-2">
                     <img src="assets/img/leaf-img.png" alt="image" class="home__leaf">
               </div>

               <img src="assets/img/home-sticker.svg" alt="image" class="home__sticker">

               <div class="home__data">
                  <p class="home__description">
                        Find delicious hot and cold coffees with the 
                        best varieties, calm the pleasure and enjoy 
                        a good coffee, order now.
                  </p>

                  <a href="#about" class="button">Learn More</a>
               </div>
            </div>
         </section>

         <!--==================== POPULAR ====================-->
         <section class="popular section" id="popular">
           <div class="popular__container container">
               <h2 class="section__title">POPULAR <br> CREATIONS</h2>

               <div class="popular__swiper swiper">
                  <div class="swiper-wrapper"> 
                     <article class="popular__card swiper-slide">
                        <div class="popular__images">
                           <div class="popular__shape"></div>
                           <img src="assets/img/bean-img.png" alt="image" class="popular__bean-1">
                           <img src="assets/img/bean-img.png" alt="image" class="popular__bean-2">
                           <img src="assets/img/popular-coffee-1.png" alt="image" class="popular__coffee">
                        </div>

                        <div class="popular__data">
                           <h2 class="popular__name">VANILLA LATTE</h2>

                           <p class="popular__description">
                              A smooth and creamy iced latte perfectly sweetened with a hint of aromatic vanilla.
                           </p>

                           <a href="#products" class="button button-dark">Order now: Rp 14,000</a>
                        </div>
                     </article>
                  
                     <article class="popular__card swiper-slide">
                        <div class="popular__images">
                           <div class="popular__shape"></div>
                           <img src="assets/img/bean-img.png" alt="image" class="popular__bean-1">
                           <img src="assets/img/bean-img.png" alt="image" class="popular__bean-2">
                           <img src="assets/img/popular-coffee-2.png" alt="image" class="popular__coffee">
                        </div>

                        <div class="popular__data">
                           <h2 class="popular__name">CLASSIC COFFEE</h2>

                           <p class="popular__description">
                              A perfectly balanced and refreshing cold brew coffee with a splash of creamy milk. Simple, smooth, and satisfying.
                           </p>

                           <a href="#products" class="button button-dark">Order now: Rp 13,000</a>
                        </div>
                     </article>
                  
                     <article class="popular__card swiper-slide">
                        <div class="popular__images">
                           <div class="popular__shape"></div>
                           <img src="assets/img/bean-img.png" alt="image" class="popular__bean-1">
                           <img src="assets/img/bean-img.png" alt="image" class="popular__bean-2">
                           <img src="assets/img/popular-coffee-3.png" alt="image" class="popular__coffee">
                        </div>

                        <div class="popular__data">
                           <h2 class="popular__name">MOCHA COFFEE</h2>

                           <p class="popular__description">
                              A rich and indulgent blend of our signature coffee and decadent chocolate syrup, topped with creamy milk.
                           </p>

                           <a href="#products" class="button button-dark">Order now: Rp 10,000</a>
                        </div>
                     </article>
                  </div>
               </div>
           </div> 
         </section>

         <!--==================== ABOUT ====================-->
         <section class="about section" id="about">
            <div class="about__container container grid">
               <div class="about__data">
                  <h2 class="section__title">LEARN MORE <br> ABOUT US</h2>

                  <p class="about__description">
                     Welcome to STARCOFFE, where coffee is pure passion. 
                     From bean to cup, we are dedicated to delivering 
                     excellence in every sip. Join us on a journey of 
                     flavor and quality, crafted with love to create the 
                     ultimate coffee experience.
                  </p>

                  <a href="#popular" class="button">The Best Coffees</a>
               </div>

               <div class="about__images">
                  <div class="about__shape"></div>
                  <img src="assets/img/leaf-img.png" alt="image" class="about__leaf-1">
                  <img src="assets/img/leaf-img.png" alt="image" class="about__leaf-2">
                  <img src="assets/img/about-coffee.png" alt="image" class="about__coffee">
               </div>
            </div>
         </section>

         <!--==================== PRODUCTS ====================-->
<section class="products section" id="products">
  <h2 class="section__title">THE MOST <br> REQUESTED</h2>
  
  <div class="products__container container grid">
<?php
// Memanggil file koneksi
include 'assets/koneksi.php';

// Query SQL untuk mengambil semua produk
$sql = "SELECT * FROM produk";
$result = mysqli_query($koneksi, $sql);

// Memeriksa apakah ada produk
if (!$result) {
    die("Query eror: " . mysqli_error($koneksi));
}
    // Perulangan untuk setiap baris data produk
    while($product = mysqli_fetch_assoc($result)) {
        ?>
        <article class="products__card" data-product-id="<?php echo $product['id_produk']; ?>">
            <div class="products__images">
                <div class="products__shape"></div>
                <img src="assets/img/ice-img.png" alt="image" class="products__ice-1">
                <img src="assets/img/ice-img.png" alt="image" class="products__ice-2">
                <img src="<?php echo $product['gambar']; ?>.png" alt="<?php echo $product['nama_produk']; ?>" class="products__coffee">
            </div>

            <div class="products__data">
                <h3 class="products__name"><?php echo $product['nama_produk']; ?></h3>
                
                <span class="products__price">Rp <?php echo number_format($product['harga'], 0, '.', ','); ?></span>
                
                <button class="products__button" data-product-id="<?php echo $product['id_produk']; ?>" title="Tambah ke keranjang">
                   <i class="ri-shopping-bag-3-fill"></i>
                </button>
                
                <!-- Info stok (opsional, bisa ditampilkan atau disembunyikan) -->
                <div class="products__stock" style="font-size: 0.75rem; color: var(--text-color); margin-top: 0.5rem;">
                    Stok: <?php echo $product['stok']; ?>
                </div>
            </div>
        </article>
        <?php
    }
?>
</div>
</section>

         <!--==================== CONTACT ====================-->
         <section class="contact section" id="contact">
            <h2 class="section__title">CONTACT US</h2>

            <div class="contact__container container grid">
               <div class="contact__info grid">
                  <div>
                     <h3 class="contact__title">Write Us</h3>

                     <div class="contact__social">
                        <a href="https://api.whatsapp.com/send?phone=081914830714&text=Hello,%20more%20information!" target="_blank" class="contact__social-link">
                           <i class="ri-whatsapp-fill"></i>
                        </a>
                        
                        <a href="https://m.me/bedimcode" target="_blank" class="contact__social-link">
                           <i class="ri-messenger-fill"></i>
                        </a>
                        <a href="https://t.me/telegram" target="_blank" class="contact__social-link">
                           <i class="ri-telegram-2-fill"></i>
                        </a>

                     </div>
                  </div>

                  <div>
                     <h3 class="contact__title">Location</h3>

                     <address class="contact__address">
                        Jawa Timur - Kota Kediri - Kabupaten Kediri <br>
                        Jl. Bandar Ngalim Gg. 3
                     </address>

                     <a href="https://maps.app.goo.gl/MAmMDxUBFXBSUzLH7" class="contact__map">
                        <i class="ri-map-pin-fill"></i>
                        <span>View On Map</span>
                     </a>
                  </div>
               </div>

               <div class="contact__info grid">
                  <div>
                     <h3 class="contact__title">Delivery</h3>

                     <address class="contact__address">
                        0819-1483-0714 <br>
                        +62 819-1483-0714
                     </address>
                  </div>
                  
                  <div>
                     <h3 class="contact__title">Attention</h3>

                     <address class="contact__address">
                        Monday - Saturday <br> 9AM - 10PM
                     </address>
                  </div>

               </div>
               
               <div class="contact__images">
                  <div class="contact__shape"></div>
                  <img src="assets/img/contact-delivery.png" alt="image" class="contact__delivery">
               </div>
            </div>
         </section>
      </main>

      <div class="auth__modal" id="auth-modal">
         <div class="auth__modal-content">
            <div class="auth__form-container" id="login-container">
               <div class="auth__form-header">
                  <h2 class="auth__title">Customers Login</h2>
                  <i class="ri-close-line auth__modal-close" id="auth-modal-close"></i>
               </div>
               <div id="login-message" class="auth__message"></div> <form id="login-form" class="auth-form" method="POST">
                     <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" name="email" id="login-email" required>
                     </div>
                     <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" name="password" id="login-password" required>
                     </div>
                     <button type="submit" class="button" style="width: 100%;">Login</button>
               </form>
               <a href="#" class="auth-link" id="show-register-link">Don't have account? Register now</a>

               <a href="admin/login.php" class="auth-link" style="margin-top: 1rem; font-size: 0.8rem; color: var(--first-color);">Login as Admin</a>
            </div>

            <div class="auth__form-container hidden" id="register-container">
               <div class="auth__form-header">
                  <h2 class="auth__title">Register</h2>
                  <i class="ri-close-line auth__modal-close"></i>
               </div>
               <div id="register-message" class="auth__message"></div> <form id="register-form" class="auth-form" method="POST">
                  <div class="form-group">
                     <label for="register-nama">Full Name</label>
                     <input type="text" name="nama" id="register-nama" required>
                  </div>
                  <div class="form-group">
                     <label for="register-email">Email</label>
                     <input type="email" name="email" id="register-email" required>
                  </div>
                  <div class="form-group">
                     <label for="register-password">Password</label>
                     <input type="password" name="password" id="register-password" required>
                  </div>
                   <div class="form-group">
                    <label for="register-alamat">Address</label>
                    <input type="text" name="alamat" id="register-alamat" required>
                </div>
                <div class="form-group">
                    <label for="register-no_telpon">phone number</label>
                    <input type="text" name="no_telpon" id="register-no_telpon" required>
                </div>
                  <button type="submit" class="button" style="width: 100%;">Register</button>
               </form>
               <a href="#" class="auth-link" id="show-login-link">Already have account? Login in here</a>
            </div>
         </div>
      </div>

      <div class="auth__modal" id="guest-checkout-modal">
    <div class="auth__modal-content">
        <div class="auth__form-container">
            <div class="auth__form-header">
                <h2 class="auth__title">Guest Checkout</h2>
                <i class="ri-close-line auth__modal-close" id="guest-modal-close"></i>
            </div>
            <div id="guest-message" class="auth__message"></div>
            <form id="guest-checkout-form" class="auth-form" method="POST">
                <div class="form-group">
                    <label for="guest-nama">Full Name</label>
                    <input type="text" name="nama" id="guest-nama" required>
                </div>
                <div class="form-group">
                    <label for="guest-email">Email</label>
                    <input type="email" name="email" id="guest-email" required>
                </div>
                <div class="form-group">
                    <label for="guest-no_telpon">Phone Number</label>
                    <input type="text" name="no_telpon" id="guest-no_telpon" required>
                </div>
                <div class="form-group">
                    <label for="guest-alamat">Address</label>
                    <textarea name="alamat" id="guest-alamat" rows="3" required style="width: 100%; padding: .75rem; border-radius: 4px; background-color: var(--body-color); color: var(--white-color); border: none; font-family: var(--body-font);"></textarea>
                </div>
                <button type="submit" class="button" style="width: 100%;">Proceed to Payment</button>
            </form>
        </div>
    </div>
</div>

      <!--==================== FOOTER ====================-->
      <footer class="footer">
         <div class="footer__container container grid">
            <div>
               <h3 class="footer__title">Social</h3>
               
               <div class="footer__social">
                  <a href="https://www.facebook.com/dikafatah.rozaqi.7" target="_blank" class="footer__social-link">
                     <i class="ri-facebook-circle-fill"></i>
                  </a>

                  <a href="https://www.instagram.com/esmilokotak/" target="_blank" class="footer__social-link">
                     <i class="ri-instagram-fill"></i>
                  </a>

                  <a href="https://x.com/dikafatahrozaqi" target="_blank" class="footer__social-link">
                     <i class="ri-twitter-x-line"></i>
                  </a>
               </div>
            </div>
            
            <div>
               <h3 class="footer__title">Payment Methods</h3>
               
               <div class="footer__pay">
                  <img src="assets/img/footer-card-1.png" alt="image" class="footer__pay-card">
                  <img src="assets/img/footer-card-2.png" alt="image" class="footer__pay-card">
                  <img src="assets/img/footer-card-3.png" alt="image" class="footer__pay-card">
                  <img src="assets/img/footer-card-4.png" alt="image" class="footer__pay-card">
               </div>
            </div>
            
            <div>
               <h3 class="footer__title">Subscribe For Discounts</h3>
               
               <form action="" class="footer__form">
                  <input type="email" placeholder="Email" class="footer__input">
                  <button type="button" class="footer__button button">Subscribe</button>
               </form>
            </div>
         </div>

         <span class="footer__copy">
            &#169; All Rights Reserved By Dikafr
         </span>
      </footer>

      <!--========== SCROLL UP ==========-->
      <a href="#" class="scrollup" id="scroll-up">
         <i class="ri-arrow-up-line"></i>
      </a>

      <!--=============== SCROLLREVEAL ===============-->
      <script src="assets/js/scrollreveal.min.js"></script>

      <!--=============== SWIPER JS ===============-->
      <script src="assets/js/swiper-bundle.min.js"></script>

      <!--=============== MAIN JS ===============-->
      <script src="assets/js/main.js"></script>
   </body>
</html>