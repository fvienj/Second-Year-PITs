<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Services | Bag-Ang Dental Clinic</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/services.css') }}" />
</head>

<body>
  <div class="page-shell">
    <header class="site-header">
      <a class="brand" href="index.html">Bag-ang Dental Clinic
      </a>
      <nav class="site-nav" aria-label="Primary">
        <a href="{{ url('/home') }}">Home</a>
        <a href="{{ url('/about') }}">About Us</a>
        <a href="{{ url('/services') }}">Services</a>
        <a href="{{ url('/book') }}">Book Appointment</a>
        <a href="{{ url('/contact') }}">Contact</a>
      </nav>
    </header>

    <main class="services-main">
      <section class="services-header">
        <p class="eyebrow">BAG-ANG DENTAL CLINIC</p>
        <h1>Our Services</h1>
        <p class="lead">We offer a comprehensive range of dental services to keep your smile healthy and beautiful.</p>
      </section>

      <section class="services-grid">
        <div class="service-card">
          <div class="service-icon">🦷</div>
          <h3>Dental Bonding</h3>
          <p class="service-description">Restore and enhance your smile with tooth-colored resin bonding.</p>
          <div class="service-details">
            <div class="detail-item">
              <span class="label">Cost:</span>
              <span class="value">Php 150k</span>
            </div>
            <div class="detail-item">
              <span class="label">Duration:</span>
              <span class="value">30 - 60 minutes</span>
            </div>
          </div>
        </div>

        <div class="service-card">
          <div class="service-icon">👑</div>
          <h3>Dental Crowns</h3>
          <p class="service-description">Protect and strengthen damaged teeth with durable dental crowns.</p>
          <div class="service-details">
            <div class="detail-item">
              <span class="label">Cost:</span>
              <span class="value">Php 20k - Php 40k</span>
            </div>
            <div class="detail-item">
              <span class="label">Duration:</span>
              <span class="value">2 - 3 appointments</span>
            </div>
          </div>
        </div>

        <div class="service-card">
          <div class="service-icon">😁</div>
          <h3>Dentures</h3>
          <p class="service-description">Replace missing teeth with comfortable and natural-looking dentures.</p>
          <div class="service-details">
            <div class="detail-item">
              <span class="label">Cost:</span>
              <span class="value">Php 5k - Php 12k</span>
            </div>
            <div class="detail-item">
              <span class="label">Duration:</span>
              <span class="value">3 - 5 appointments</span>
            </div>
          </div>
        </div>

        <div class="service-card">
          <div class="service-icon">✨</div>
          <h3>Teeth Cleaning</h3>
          <p class="service-description">Professional cleaning to remove plaque and tartar buildup.</p>
          <div class="service-details">
            <div class="detail-item">
              <span class="label">Cost:</span>
              <span class="value">Php 800 - Php 1.2k</span>
            </div>
            <div class="detail-item">
              <span class="label">Duration:</span>
              <span class="value">45 - 60 minutes</span>
            </div>
          </div>
        </div>

        <div class="service-card">
          <div class="service-icon">🔧</div>
          <h3>Tooth Extractions</h3>
          <p class="service-description">Safe and painless removal of problematic or damaged teeth.</p>
          <div class="service-details">
            <div class="detail-item">
              <span class="label">Cost:</span>
              <span class="value">Php 500 - Php 1.5k</span>
            </div>
            <div class="detail-item">
              <span class="label">Duration:</span>
              <span class="value">20 - 40 minutes</span>
            </div>
          </div>
        </div>

        <div class="service-card">
          <div class="service-icon">⛓</div>
          <h3>Dental Braces</h3>
          <p class="service-description">Gradually guide your teeth into their ideal position.</p>
          <div class="service-details">
            <div class="detail-item">
              <span class="label">Cost:</span>
              <span class="value">Php 28k - 300k</span>
            </div>
            <div class="detail-item">
              <span class="label">Duration:</span>
              <span class="value">12 - 14 months</span>
            </div>
          </div>
        </div>
      </section>

      <section class="dentists-section">
        <h2>Our Dentists</h2>
        <div class="dentists-grid">
          <div class="dentist-card">
            <div class="dentist-avatar">ME</div>
            <h3>Monica Empleo</h3>
            <p class="dentist-title">Licensed Dental Specialist</p>
            <p class="dentist-bio">Experienced in cosmetic and restorative dentistry with over 10 years of practice.</p>
            <ul class="dentist-info-list">
              <li><strong>Specialization:</strong> Cosmetic & restorative dentistry</li>
              <li><strong>Email:</strong> monica.empleo@bagangdental.com</li>
              <li><strong>Status:</strong> Active</li>
              <li><strong>License No.:</strong> DENT-102948</li>
              <li><strong>Date of Birth:</strong> April 12, 1986</li>
              <li><strong>Schedule:</strong> Monday - Saturday, 8:00 AM - 5:00 PM</li>
            </ul>
          </div>
          <div class="dentist-card">
            <div class="dentist-avatar">FN</div>
            <h3>Fvienj Nopuente</h3>
            <p class="dentist-title">General & Oral Surgery Dentist</p>
            <p class="dentist-bio">Specializes in surgical extractions and complex dental procedures with precision and care.</p>
            <ul class="dentist-info-list">
              <li><strong>Specialization:</strong> Oral surgery & extractions</li>
              <li><strong>Email:</strong> fvienj.nopuente@bagangdental.com</li>
              <li><strong>Status:</strong> Active</li>
              <li><strong>License No.:</strong> DENT-207531</li>
              <li><strong>Date of Birth:</strong> September 3, 1984</li>
              <li><strong>Schedule:</strong> Monday - Saturday, 8:00 AM - 5:00 PM</li>
            </ul>
          </div>
        </div>
      </section>

      <section class="availability-section">
        <h2>Hours of Availability</h2>
        <div class="availability-container">
          <div class="availability-info">
            <p><strong>Monday - Saturday:</strong> 8:00 AM - 5:00 PM</p>
            <p><strong>Sunday:</strong> Closed</p>
            <p class="note">We're ready to serve you during all business hours. Walk-ins are welcome!</p>
          </div>
        </div>
      </section>

      <section class="booking-section">
        <h2>Ready to Smile?</h2>
        <p>Select a service and proceed to book an appointment.</p>
        <div class="booking-buttons">
          <a href="book.html" class="primary-button">Book an Appointment</a>
        </div>
      </section>
    </main>
  </div>

</body>
</html>