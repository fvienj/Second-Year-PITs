<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Appointment | Bag-Ang Dental Clinic</title>
  <link rel="stylesheet" href="{{ asset('css/book.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <a class="brand" href="index.html">Bag-ang Dental Clinic</a>
      <nav class="site-nav" aria-label="Primary">
        <a href="{{ url('/home') }}">Home</a>
        <a href="{{ url('/about') }}">About Us</a>
        <a href="{{ url('/services') }}">Services</a>
        <a href="#booking">Book Appointment</a>
        <a href="{{ url('/contact') }}">Contact</a>
      </nav>
    </header>

    <main class="booking-main">
      <section class="booking-header">
        <p class="eyebrow">BAG-ANG DENTAL CLINIC</p>
        <h1>Book Your Appointment</h1>
        <p class="lead">Complete the form below to schedule your dental appointment with our expert team.</p>
      </section>

      <section class="booking-form-section">
        <form class="booking-form" id="bookingForm">
          <div class="form-grid">
            <!-- Patient Information -->
            <div class="form-group">
              <label for="pfirstName">First Name *</label>
              <input name="first_name" type="text" id="pfirstName" name="pfirstName" required /> <!-- FIRST NAME -->
            </div>

            <div class="form-group">
              <label for="plastName">Last Name *</label>
              <input name="last_name" type="text" id="plastName" name="plastName" required /> <!-- LAST NAME -->
            </div>

            <div class="form-group">
              <label for="patientemail">Email *</label>
              <input name="patient_email" type="email" id="patientemail" name="patientemail" required /> <!-- EMAIL -->
            </div>

            <div class="form-group">
              <label for="patientcontact">Contact Number *</label>
              <input name="patient_contact" type="text" id="patientcontact" name="patientcontact" required /> <!-- CONTACT -->
            </div>

            <div class="form-group full-width">
              <label for="dateOfBirth">Date of Birth *</label>
              <input name="patient_dob" type="date" id="dateOfBirth" name="dateOfBirth" required /> <!-- DOB -->
            </div>

            <!-- Service Selection -->
            <div class="form-group full-width">
              <label for="service">Service Type *</label>
              <select name="service" id="service" name="service" required>
                <option value="">Select a service</option>
                <option value="Dental Bonding - Php 150k">Dental Bonding - Php 150k</option>
                <option value="Dental Crowns - Php 20k-40k">Dental Crowns - Php 20k-40k</option>
                <option value="Dentures - Php 5k-12k">Dentures - Php 5k-12k</option>
                <option value="Teeth Cleaning - Php 800-1.2k">Teeth Cleaning - Php 800-1.2k</option>
                <option value="Tooth Extractions - Php 500-1.5k">Tooth Extractions - Php 500-1.5k</option>
                <option value="Orthodontic Braces - Php 28k-300k">Orthodontic Braces - Php 28k-300k</option>    
              </select>
            </div>

            <!-- Dentist Selection -->
            <div class="form-group full-width">
              <label for="dentist">Dentist *</label>
              <select name="dentist" id="dentist" name="dentist" required>
                <option value="">Select a dentist</option>
                <option value="Monica Empleo - Cosmetic & Restorative">Monica Empleo - Cosmetic & Restorative</option>
                <option value="Fvienj Nopuente - Oral Surgery">Fvienj Nopuente - Oral Surgery</option>
              </select>
            </div>

            <!-- Appointment Date -->
            <div class="form-group">
              <label for="appointmentDate">Preferred Date *</label>
              <input name="appointment_date" type="date" id="appointmentDate" name="appointmentDate" required min="2024-01-15" />
            </div>

            <div class="form-group">
              <label for="appointmentTime">Preferred Time *</label>
              <select name="appointment_time" id="appointmentTime" name="appointmentTime" required>
                <option value="">Select time</option>
                <option value="08:00">8:00 AM</option>
                <option value="09:00">9:00 AM</option>
                <option value="10:00">10:00 AM</option>
                <option value="11:00">11:00 AM</option>
                <option value="13:00">1:00 PM</option>
                <option value="14:00">2:00 PM</option>
                <option value="15:00">3:00 PM</option>
                <option value="16:00">4:00 PM</option>
              </select>
            </div>
          </div>

          <!-- Payment Section -->
          <div class="payment-section">
            <h3>Payment Information</h3>
            <p>Please present cash at the clinic upon your appointment.</p>
            <div class="payment-methods">
              <label class="payment-method">
                <input name="payment_method" type="radio" name="paymentMethod" value="full" checked />
                <span>Cash</span>
              </label>
            </div>
            <div class="payment-summary" id="paymentSummary">
              <div class="summary-item">
                <span>Service Cost:</span>
                <span id="serviceCost">Php 0</span>
              </div>
              <div class="summary-item total">
                <span>Total Amount:</span>
                <span id="totalAmount">Php 0</span>
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="primary-button">Confirm Booking</button>
          </div>
        </form>
      </section>

      <section class="booking-info">
        <div class="info-card">
          <h3>Clinic Hours</h3>
          <p><strong>Monday - Saturday:</strong> 8:00 AM - 5:00 PM</p>
          <p><strong>Sunday:</strong> Closed</p>
        </div>
        <div class="info-card">
          <h3>Confirmation</h3>
          <p>We'll send you a confirmation email and SMS within 24 hours. Walk-ins also welcome!</p>
        </div>
      </section>
    </main>
  </div>

  <script>
// Enhanced booking system for updated form (with email, contact, cash payment)
document.addEventListener('DOMContentLoaded', function() {
  // Dynamic payment calculation (updated for new services)
  const serviceSelect = document.getElementById('service');
  const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
  
  serviceSelect.addEventListener('change', updatePayment);
  paymentRadios.forEach(radio => radio.addEventListener('change', updatePayment));

  // Sunday blocking + min date
  function setupDateRestrictions() {
    const appointmentDate = document.getElementById('appointmentDate');
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    // Set minimum date to tomorrow
    appointmentDate.min = tomorrow.toISOString().split('T')[0];
    
    // Block Sundays
    appointmentDate.addEventListener('change', function() {
      const selectedDate = new Date(this.value);
      if (selectedDate.getDay() === 0) { // Sunday
        alert('❌ Clinic is CLOSED on Sundays.\nPlease select Monday - Saturday.');
        this.value = '';
        this.focus();
      }
    });
  }

  function updatePayment() {
    const serviceValue = serviceSelect.value;
    
    let cost = 0;
    if (serviceValue.includes('150k')) cost = 150000;
    else if (serviceValue.includes('20k') || serviceValue.includes('40k')) cost = 30000;
    else if (serviceValue.includes('5k') || serviceValue.includes('12k')) cost = 8500;
    else if (serviceValue.includes('800') || serviceValue.includes('1.2k')) cost = 1000;
    else if (serviceValue.includes('500') || serviceValue.includes('1.5k')) cost = 1000;
    else if (serviceValue.includes('28k') || serviceValue.includes('300k')) cost = 50000; // Braces

    document.getElementById('serviceCost').textContent = `Php ${cost.toLocaleString()}`;
    document.getElementById('totalAmount').textContent = `Php ${cost.toLocaleString()}`; // Cash = full amount
  }

    document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Final Sunday check
    const appointmentDate = document.getElementById('appointmentDate').value;
    const selectedDate = new Date(appointmentDate);
    if (selectedDate.getDay() === 0) {
      alert('❌ Sunday bookings not allowed. Clinic closed.');
      return;
    }

    // Get all form data (matches your NEW field names)
    const formData = new FormData(this);
    const bookingData = Object.fromEntries(formData);
    
    // 1. Show loading state
    const submitBtn = this.querySelector('.primary-button');
    const originalText = submitBtn.textContent;
    submitBtn.innerHTML = '⏳ Processing...';
    submitBtn.disabled = true;

    // 2. Simulate server processing (2 seconds)
    setTimeout(() => {
      showBookingSuccess(bookingData);
      
      // 3. Reset form for next booking
      this.reset();
      updatePayment();
      
      // 4. Restore button
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }, 2000);
  });

  // Beautiful Success Modal (Updated for your fields)
  function showBookingSuccess(data) {
    const modal = document.createElement('div');
    modal.className = 'booking-success-modal';
    modal.innerHTML = `
      <div class="success-content">
        <div class="success-icon">✅</div>
        <h2>Appointment Confirmed!</h2>
        <p class="success-subtitle">Your booking has been successfully scheduled.</p>
        
        <div class="booking-summary">
          <div class="summary-row">
            <span>👤 ${data.pfirstName} ${data.plastName}</span>
            <span>📧 ${data.patientemail}</span>
          </div>
          <div class="summary-row">
            <span>📞 ${data.patientcontact}</span>
            <span>🎂 ${data.dateOfBirth}</span>
          </div>
          <div class="summary-row">
            <span>🦷 ${data.service}</span>
            <span>👩‍⚕️ ${data.dentist}</span>
          </div>
          <div class="summary-row highlight">
            <span>📅 ${data.appointmentDate}</span>
            <span>🕒 ${data.appointmentTime}</span>
          </div>
          <div class="summary-row highlight">
            <span>💰 Cash Payment</span>
            <span>Php ${document.getElementById('totalAmount').textContent}</span>
          </div>
        </div>
        
        <div class="confirmation-details">
          <p><strong>📧 Confirmation Email:</strong> Sent to ${data.patientemail}</p>
          <p><strong>📱 SMS Confirmation:</strong> Sent to ${data.patientcontact}</p>
          <p><strong>⏰ Expected Reply:</strong> Within 24 hours</p>
        </div>
        
        <div class="success-actions">
          <button onclick="window.location.href='contact.html'" class="secondary-btn">
            Contact Us
          </button>
          <button onclick="this.closest('.booking-success-modal').remove()" class="primary-btn">
            Book Another
          </button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
    
    // Auto-close after 15 seconds
    setTimeout(() => modal.remove(), 15000);
  }

  // Initialize everything
  setupDateRestrictions();
  updatePayment();
});
</script>
</body>
</html>