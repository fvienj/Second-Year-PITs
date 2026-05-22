<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Contact Us | Bag-Ang Dental Clinic</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/contact.css') }}" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <a class="brand" href="index.html">Bag-ang Dental Clinic</a>
      <nav class="site-nav" aria-label="Primary">
        <a href="{{ url('/home') }}">Home</a>
        <a href="{{ url('/about') }}">About Us</a>
        <a href="{{ url('/services') }}">Services</a>
        <a href="{{ url('/book') }}">Book Appointment</a>
        <a href="{{ url('/contact') }}">Contact</a>
      </nav>
    </header>

    <main class="contact-main">
      <section class="contact-hero">
        <div class="contact-copy">
          <p class="eyebrow">GET IN TOUCH</p>
          <h1>Ready to start your<br>smile journey?</h1>
          <p class="lead">We're here to help. Reach out and let's create your perfect smile together.</p>
        </div>
      </section>

      <section class="contact-grid">
        <!-- Contact Details -->
        <div class="contact-details">
          <div class="contact-card phone">
            <div class="contact-icon">📞</div>
            <h3>Call Us</h3>
            <p class="contact-value">
              <a href="tel:+639123456789">+63 912 345 6789</a>
            </p>
            <p class="contact-hours">Mon-Sat 8AM-5PM</p>
          </div>

          <div class="contact-card email">
            <div class="contact-icon">✉️</div>
            <h3>Email Us</h3>
            <p class="contact-value">
              <a href="mailto:hello@bagangdental.com">hello@bagangdental.com</a>
            </p>
            <p class="contact-hours">Response within 24hrs</p>
          </div>

          <div class="contact-card social">
            <div class="contact-icon">📱</div>
            <h3>Follow Us</h3>
            <ul class="social-links">
              <li><a href="https://facebook.com/bagangdental" target="_blank" aria-label="Facebook">Facebook</a></li>
              <li><a href="https://instagram.com/bagangdental" target="_blank" aria-label="Instagram">Instagram</a></li>
              <li><a href="https://tiktok.com/@bagangdental" target="_blank" aria-label="TikTok">TikTok</a></li>
            </ul>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-container">
          <div class="contact-card form-card">
            <h3>Send us a message</h3>
            <form class="contact-form">
              <div class="form-row">
                <div class="form-group">
                  <label for="contactName">Full Name</label>
                  <input type="text" id="contactName" name="name" required />
                </div>
              </div>
              
              <div class="form-group">
                <label for="contactPhone">Phone (Optional)</label>
                <input type="tel" id="contactPhone" name="phone" />
              </div>

              <div class="form-group">
                <label for="contactSubject">Subject</label>
                <select id="contactSubject" name="subject" required>
                  <option value="">Choose a subject</option>
                  <option value="Appointment Booking">Appointment Booking</option>
                  <option value="Service Inquiry">Service Inquiry</option>
                  <option value="Price Quote">Price Quote</option>
                  <option value="General Question">General Question</option>
                </select>
              </div>

              <div class="form-group">
                <label for="contactMessage">Message</label>
                <textarea id="contactMessage" name="message" rows="5" required 
                  placeholder="Tell us about your dental needs..."></textarea>
              </div>

              <button type="submit" class="primary-button">Send Message</button>
            </form>
          </div>
        </div>
      </section>

      <!-- Location & Map -->
      <!-- Location & Map Section - UPDATED -->
      <section class="location-section">
        <div class="location-header">
        <h2>Visit Us</h2>
        <p>Bag-ang Dental Clinic<br>
       University of Science and Technology of Southern Philippines (USTP)<br>
       C.M. Recto, Lapasan, Cagayan de Oro City<br>
       Misamis Oriental 9000, Philippines</p>
  </div>

  <div class="map-container" id="map"></div>
</section>
    </main>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Updated to USTP CDO Coordinates: 8.4851, 124.6565
    const map = L.map('map').setView([8.4851, 124.6565], 16);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Clinic marker at USTP CDO
    L.marker([8.4851, 124.6565])
      .addTo(map)
      .bindPopup(`
        <div style="text-align: center; font-family: 'Inter', sans-serif;">
          <b style="color: #2d0e64;">🦷 Bag-ang Dental Clinic</b><br>
          <small>USTP Campus, C.M. Recto, Lapasan</small><br>
          Cagayan de Oro City 9000<br>
          <strong>Mon-Sat: 8AM-5PM</strong><br><br>
          <a href="tel:+639123456789" style="color: #10b981; text-decoration: none;">
            📞 +63 912 345 6789
          </a>
        </div>
      `)
      .openPopup();

    // Form submission
    document.querySelector('.contact-form').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('✅ Message sent successfully!\n\nWe\'ll get back to you within 24 hours. 😊');
      this.reset();
    });
  });
</script>
@include('chatbot')
</body>
</html>