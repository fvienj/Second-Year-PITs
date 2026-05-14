<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us | Bag-Ang Dental Clinic</title>
  <link rel="stylesheet" href="{{ asset('css/about.css') }}" />
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <a class="brand" href="{{ url('/home') }}">Bag-ang Dental Clinic
      </a>
      <nav class="site-nav" aria-label="Primary">
        <a href="{{ url('/home') }}">Home</a>
        <a href="{{ url('/about') }}">About Us</a>
        <a href="{{ url('/services') }}">Services</a>
        <a href="{{ url('/book') }}">Book Appointment</a>
        <a href="{{ url('/contact') }}">Contact</a>
      </nav>
    </header>

    <section class="about-section">
      <div class="about-grid">
        <div class="about-copy">
          <p class="eyebrow">Our story</p>
          <h2 class="about-title">Where advanced dentistry meets calm, reassuring care.</h2>
          <p class="about-lead">We combine refined aesthetics and precise treatment planning to make each smile transformation feel effortless and approachable.</p>
        </div>

        <ul class="feature-list">
          <p><img src="featurelist1.png" alt="Tailored treatment plans" /></p>
          <li>Premium orthodontic care made accessible</li>
          <li>Comfort-first service from start to finish</li>
        </ul>
      </div>

      <div class="about-grid">
        <div class="about-stats">
          <div class="stat-card">
            <strong>15+</strong>
            <p>Years of collective experience in orthodontics and aesthetic dentistry.</p>
          </div>
          <div class="stat-card">
            <strong>98%</strong>
            <p>Patient satisfaction from families and professionals who trust our care.</p>
          </div>
        </div>

        <div class="about-quote">
          <blockquote>"We believe great dental care should feel luxurious, reliable, and deeply human — from every checkup to every smile reveal."</blockquote>
          <cite>— Dr. Monica Empleo, Founder</cite>
        </div>
      </div>

      <div class="about-details">
        <p class="eyebrow">Our specialists</p>
        <div class="team-grid">
          <div class="team-card">
            <h3>Dr. Monica Empleo</h3>
            <p>Lead orthodontist focused on smile architecture and precision aligner therapy.</p>
          </div>
          <div class="team-card">
            <h3>Fvienj Nopuente</h3>
            <p>Clinical aesthetic dentist specializing in cosmetic smile enhancement.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>
</html>