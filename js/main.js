// Loading overlay & Entrance Sequence
const loader = document.getElementById('page-loader');
if (loader) {
  window.addEventListener('load', () => {
    loader.classList.add('hidden');
    
    // Trigger hero entrance after loader is gone
    setTimeout(() => {
      const heroContent = document.querySelector('.hero-content');
      if (heroContent) heroContent.style.opacity = '1';
      loader.remove();
    }, 600);
  });
}


// Mobile navigation toggle
const navToggle = document.querySelector('.nav-toggle');
const siteNav = document.querySelector('.site-nav');
if (navToggle && siteNav) {
  navToggle.addEventListener('click', () => {
    const isOpen = siteNav.classList.toggle('open');
    navToggle.classList.toggle('open', isOpen);
    document.body.classList.toggle('no-scroll', isOpen);
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });

  // Close menu when clicking a link
  const navLinks = siteNav.querySelectorAll('a');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      siteNav.classList.remove('open');
      navToggle.classList.remove('open');
      document.body.classList.remove('no-scroll');
    });
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

// --- Enhanced Scroll Reveal System ---
document.addEventListener('DOMContentLoaded', () => {
    // 1. Identify grid containers to apply automatic staggered delays
    const containers = document.querySelectorAll('.grid, .cards-3, .cards-2, .hero-badges, .why-grid, .footer-grid');
    containers.forEach(container => {
        const children = container.children;
        Array.from(children).forEach((child, index) => {
            child.classList.add('reveal');
            // Apply a staggered delay using CSS variables
            child.style.setProperty('--delay', `${(index % 4) * 0.15}s`);
        });
    });

    // 2. Identify standalone reveal targets
    const standaloneTargets = document.querySelectorAll('.section-header, .about-panel, .form-card, .cta-inner, .map-frame');
    standaloneTargets.forEach(el => el.classList.add('reveal'));

    // 3. Initialize IntersectionObserver
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                // We keep observing if we want it to animate every time, 
                // but usually "once" feels more premium for content.
                revealObserver.unobserve(entry.target); 
            }
        });
    }, { 
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px' // Trigger slightly before it hits the viewport
    });

    // 4. Observe all reveal elements
    document.querySelectorAll('.reveal').forEach(el => {
        revealObserver.observe(el);
    });
});

