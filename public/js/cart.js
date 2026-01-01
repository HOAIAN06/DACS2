// Cart Page JavaScript - HANZO Shop

document.addEventListener('DOMContentLoaded', function () {
  // Quantity controls
  const qtyButtons = document.querySelectorAll('.hz-qty__btn');
  
  qtyButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      
      const itemId = this.dataset.item;
      const qtyInput = document.querySelector(`.hz-qty__input[data-item="${itemId}"]`);
      
      if (!qtyInput) return;
      
      let currentVal = parseInt(qtyInput.value) || 1;
      
      if (this.classList.contains('hz-qty__minus')) {
        if (currentVal > 1) {
          qtyInput.value = currentVal - 1;
          qtyInput.form.submit();
        }
      } else if (this.classList.contains('hz-qty__plus')) {
        qtyInput.value = currentVal + 1;
        qtyInput.form.submit();
      }
    });
  });
  
  // Smooth scroll to top on page load
  if (window.location.hash) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
});
