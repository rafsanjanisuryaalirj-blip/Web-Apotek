// Custom JavaScript untuk Go Healty

document.addEventListener("DOMContentLoaded", function () {
  // Initialize tooltips and popovers
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]'),
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Confirm delete actions
  const deleteButtons = document.querySelectorAll('[onclick*="confirm"]');
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      if (!confirm(this.getAttribute("onclick").match(/'([^']*)'/)[1])) {
        e.preventDefault();
      }
    });
  });

  // Format currency input
  const currencyInputs = document.querySelectorAll("[data-currency]");
  currencyInputs.forEach((input) => {
    input.addEventListener("input", function () {
      let value = this.value.replace(/[^0-9]/g, "");
      if (value) {
        value = parseInt(value).toLocaleString("id-ID");
        this.value = value;
      }
    });
  });

  // Add to cart button loading state
  const addToCartForm = document.querySelector('form[action*="add_to_cart"]');
  if (addToCartForm) {
    addToCartForm.addEventListener("submit", function () {
      const submitBtn = this.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML =
          '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
      }
    });
  }

  // Check cart quantity before checkout
  const checkoutForm = document.querySelector('form[action*="checkout"]');
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function (e) {
      const quantities = document.querySelectorAll('input[name^="quantity"]');
      let valid = true;

      quantities.forEach((input) => {
        if (parseInt(input.value) <= 0) {
          valid = false;
          input.classList.add("is-invalid");
        }
      });

      if (!valid) {
        e.preventDefault();
        alert("Silakan periksa jumlah produk yang Anda pesan!");
      }
    });
  }

  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert:not(.alert-permanent)");
  alerts.forEach((alert) => {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });

  // Active navigation link
  const currentPage = window.location.pathname;
  const navLinks = document.querySelectorAll(".navbar-nav a.nav-link");
  navLinks.forEach((link) => {
    if (link.href === window.location.href) {
      link.classList.add("active");
    }
  });

  // Search form validation
  const searchForm = document.querySelector('form[action*="search"]');
  if (searchForm) {
    searchForm.addEventListener("submit", function (e) {
      const searchInput = this.querySelector('input[name="search"]');
      if (searchInput && searchInput.value.trim() === "") {
        e.preventDefault();
        searchInput.classList.add("is-invalid");
      }
    });
  }

  // Password visibility toggle
  const togglePasswordButtons = document.querySelectorAll(
    "[data-toggle-password]",
  );
  togglePasswordButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const input = document.querySelector(
        this.getAttribute("data-toggle-password"),
      );
      if (input) {
        input.type = input.type === "password" ? "text" : "password";
        this.innerHTML =
          input.type === "password"
            ? '<i class="fas fa-eye"></i>'
            : '<i class="fas fa-eye-slash"></i>';
      }
    });
  });
});

// Utility function: Format currency
function formatCurrency(amount) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
  }).format(amount);
}

// Utility function: Check if email is valid
function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

// Utility function: Check if phone is valid
function isValidPhone(phone) {
  const re = /^(\+62|0)[0-9]{9,12}$/;
  return re.test(phone);
}

// Function to show loading state
function showLoading(element) {
  if (element) {
    element.disabled = true;
    element.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
  }
}

// Function to hide loading state
function hideLoading(element, text) {
  if (element) {
    element.disabled = false;
    element.innerHTML = text;
  }
}

// Function to show notification
function showNotification(message, type = "info") {
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
  alertDiv.role = "alert";
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

  const container = document.querySelector("main") || document.body;
  container.insertBefore(alertDiv, container.firstChild);

  setTimeout(() => {
    const bsAlert = new bootstrap.Alert(alertDiv);
    bsAlert.close();
  }, 5000);
}

// Function to debounce
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Export functions for use in other scripts
window.apotekUtils = {
  formatCurrency,
  isValidEmail,
  isValidPhone,
  showLoading,
  hideLoading,
  showNotification,
  debounce,
};
