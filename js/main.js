// Loading overlay
const loader = document.getElementById('page-loader');
if (loader) {
  window.addEventListener('load', () => {
    loader.classList.add('hidden');
    setTimeout(() => loader.remove(), 600);
  });
}

// Mobile navigation toggle
const navToggle = document.querySelector('.nav-toggle');
const siteNav = document.querySelector('.site-nav');
if (navToggle && siteNav) {
  navToggle.addEventListener('click', () => {
    const isOpen = siteNav.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });
}

// Smooth scrolling for in-page links
const anchorLinks = document.querySelectorAll('a[href^="#"]');
anchorLinks.forEach((link) => {
  link.addEventListener('click', (event) => {
    const targetId = link.getAttribute('href');
    const targetEl = document.querySelector(targetId);
    if (targetEl) {
      event.preventDefault();
      targetEl.scrollIntoView({ behavior: 'smooth' });
    }
  });
});

// Product category filter
const filterButtons = document.querySelectorAll('.filter-btn');
const productCards = document.querySelectorAll('.product-card');
if (filterButtons.length) {
  filterButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      filterButtons.forEach((button) => button.classList.remove('active'));
      btn.classList.add('active');
      const category = btn.dataset.category;
      productCards.forEach((card) => {
        if (category === 'all' || card.dataset.category === category) {
          card.style.display = 'grid';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
}

// Simple client-side form validation
const validateEmail = (value) => /\S+@\S+\.\S+/.test(value);
const validatePhone = (value) => /^\+?\d{8,14}$/.test(value.trim());

const formHandlers = document.querySelectorAll('form[data-validate]');
formHandlers.forEach((form) => {
  form.addEventListener('submit', (event) => {
    let isValid = true;
    const successBox = form.querySelector('.form-success');
    if (successBox) successBox.style.display = 'none';

    const fields = form.querySelectorAll('[data-rule]');
    fields.forEach((field) => {
      const rule = field.dataset.rule;
      const error = field.closest('.form-field')?.querySelector('.error-msg');
      let message = '';

      if (rule === 'text' && field.value.trim().length < 2) {
        message = 'Please enter at least 2 characters.';
      }
      if (rule === 'email' && !validateEmail(field.value)) {
        message = 'Please enter a valid email address.';
      }
      if (rule === 'phone' && !validatePhone(field.value)) {
        message = 'Please enter a valid phone number.';
      }
      if (rule === 'password' && field.value.length < 6) {
        message = 'Password must be at least 6 characters.';
      }
      if (rule === 'confirm') {
        const passwordField = form.querySelector('[data-rule="password"]');
        if (passwordField && field.value !== passwordField.value) {
          message = 'Passwords do not match.';
        }
      }

      if (error) error.textContent = message;
      if (message) isValid = false;
    });

    if (!isValid) {
      event.preventDefault();
      return;
    }

    const action = form.getAttribute('action');
    if (!action) {
      event.preventDefault();
      if (successBox) {
        successBox.style.display = 'block';
        form.reset();
      }
    }
  });
});

const flashMessages = {
  login: {
    invalid: 'Invalid email or password.',
    required: 'Please enter both email and password.',
    login_required: 'Please log in to continue.',
    logged_out: 'You have been logged out.',
    registered: 'Account created! Please log in.'
  },
  register: {
    email_exists: 'That email is already registered.',
    invalid_email: 'Please enter a valid email address.',
    invalid_phone: 'Please enter a valid phone number.',
    password_short: 'Password must be at least 6 characters.',
    password_mismatch: 'Passwords do not match.',
    required: 'Please complete all required fields.'
  }
};

const urlParams = new URLSearchParams(window.location.search);
const flashType = urlParams.get('type');
const flashCode = urlParams.get('code');
if (flashType && flashCode && flashMessages[flashType]) {
  const message = flashMessages[flashType][flashCode];
  const target = document.getElementById(`${flashType}-message`);
  if (target && message) {
    target.textContent = message;
    target.style.display = 'block';
    if (flashCode !== 'registered' && flashCode !== 'logged_out') {
      target.classList.add('error');
    }
  }
}


// --- Scroll Reveal Animations ---
document.addEventListener('DOMContentLoaded', () => {
    const revealTargets = document.querySelectorAll('.card, .product-card, .section-header, .about-panel, .hero-grid > div, .form-card, .cart-table, .checkout-grid > div, .mini-card');
    
    // Auto-inject .reveal class
    revealTargets.forEach(el => el.classList.add('reveal'));
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    // Add existing hardcoded .reveal if any, plus auto-injected ones
    document.querySelectorAll('.reveal').forEach(el => {
        revealObserver.observe(el);
    });
});

