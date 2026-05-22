<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>Book Appointment | Bag-Ang Dental Clinic</title>
  <link rel="stylesheet" href="{{ asset('css/book.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <a class="brand" href="{{ url('/home') }}">Bag-ang Dental Clinic</a>
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
            <div class="form-group">
              <label for="first_name">First Name *</label>
              <input type="text" id="first_name" name="first_name" required />
            </div>

            <div class="form-group">
              <label for="last_name">Last Name *</label>
              <input type="text" id="last_name" name="last_name" required />
            </div>

            <div class="form-group">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" required />
            </div>

            <div class="form-group">
              <label for="contact">Contact Number *</label>
              <input type="text" id="contact" name="contact" required />
            </div>

            <div class="form-group full-width">
              <label for="dob">Date of Birth *</label>
              <input type="date" id="dob" name="dob" required />
            </div>

            <div class="form-group full-width">
              <label for="service">Service Type *</label>
              <select id="service" name="service" required>
                <option value="">Select a service</option>
                @foreach($services as $service)
                  <option value="{{ $service->services_id }}" data-cost="{{ $service->service_cost }}">
                    {{ $service->service_name }} - Php {{ number_format($service->service_cost) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group full-width">
              <label for="dentist">Dentist *</label>
              <select id="dentist" name="dentist" required>
                <option value="">Select a dentist</option>
                @foreach($dentists as $dentist)
                  <option value="{{ $dentist->dentist_id }}">
                    Dr. {{ $dentist->dentist_firstname }} {{ $dentist->dentist_lastname }} ({{ $dentist->dentist_specialization }})
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="appointment_date">Preferred Date *</label>
              <input type="date" id="appointment_date" name="appointment_date" required />
            </div>

            <div class="form-group">
              <label for="appointment_time">Preferred Time *</label>
              <select id="appointment_time" name="appointment_time" required>
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

          <div class="payment-section">
            <h3>Payment Information</h3>
            <p>Please present cash at the clinic upon your appointment.</p>
            <div class="payment-methods">
              <label class="payment-method">
                <input type="radio" name="payment_method" value="full" checked />
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
    </main>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const serviceSelect = document.getElementById('service');
  const appointmentDate = document.getElementById('appointment_date');
  const dentistSelect = document.getElementById('dentist');
  const timeSelect = document.getElementById('appointment_time');
  
  serviceSelect.addEventListener('change', updatePayment);
  appointmentDate.addEventListener('change', checkBookedSlots);
  dentistSelect.addEventListener('change', checkBookedSlots);

  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  appointmentDate.min = tomorrow.toISOString().split('T')[0];

  function updatePayment() {
    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
    const cost = selectedOption.getAttribute('data-cost') ? parseFloat(selectedOption.getAttribute('data-cost')) : 0;
    
    document.getElementById('serviceCost').textContent = `Php ${cost.toLocaleString()}`;
    document.getElementById('totalAmount').textContent = `Php ${cost.toLocaleString()}`;
  }

  function checkBookedSlots() {
    const dateValue = appointmentDate.value;
    const dentistValue = dentistSelect.value;
    if (!dateValue || !dentistValue) return;

    if (new Date(dateValue).getDay() === 0) { 
        alert('❌ Clinic is CLOSED on Sundays.\nPlease select Monday - Saturday.');
        appointmentDate.value = '';
        return;
    }

    Array.from(timeSelect.options).forEach(option => {
        if (option.value !== "") {
            option.disabled = false;
            option.textContent = option.textContent.replace(' (Booked)', '');
        }
    });

    fetch(`/check-availability?date=${dateValue}&dentist_id=${dentistValue}`)
        .then(response => response.json())
        .then(bookedTimes => {
            Array.from(timeSelect.options).forEach(option => {
                if (bookedTimes.includes(option.value)) {
                    option.disabled = true;
                    option.textContent = option.textContent + ' (Booked)';
                }
            });
        });
  }

  document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('.primary-button');
    const originalText = submitBtn.textContent;
    submitBtn.innerHTML = '⏳ Processing...';
    submitBtn.disabled = true;

    const formData = new FormData(this);
    const bookingData = Object.fromEntries(formData);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/book-appointment', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: formData
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        showBookingSuccess(bookingData);
        this.reset();
        updatePayment();
      } else {
        alert('Error: ' + result.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred. Please try again.');
    })
    .finally(() => {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    });
  });

  function showBookingSuccess(data) {
    const serviceText = document.getElementById('service').options[document.getElementById('service').selectedIndex].text;
    const dentistText = document.getElementById('dentist').options[document.getElementById('dentist').selectedIndex].text;

    const modal = document.createElement('div');
    modal.className = 'booking-success-modal';
    modal.innerHTML = `
      <div class="success-content">
        <div class="success-icon">✅</div>
        <h2>Appointment Confirmed!</h2>
        <p class="success-subtitle">Your booking has been successfully scheduled.</p>
        
        <div class="booking-summary">
          <div class="summary-row">
            <span>👤 ${data.first_name} ${data.last_name}</span>
            <span>📧 ${data.email || 'N/A'}</span>
          </div>
          <div class="summary-row">
            <span>📞 ${data.contact}</span>
            <span>🎂 ${data.dob}</span>
          </div>
          <div class="summary-row">
            <span>🦷 ${serviceText.split(' -')[0]}</span>
            <span>👩‍⚕️ ${dentistText.split(' -')[0]}</span>
          </div>
          <div class="summary-row highlight">
            <span>📅 ${data.appointment_date}</span>
            <span>🕒 ${data.appointment_time}</span>
          </div>
        </div>
        
        <div class="success-actions">
          <button onclick="this.closest('.booking-success-modal').remove()" class="primary-btn">Done</button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
    setTimeout(() => modal.remove(), 15000);
  }
});
</script>
@include('chatbot')
</body>
</html>