/**
 * Studio Homepage: Fast Client-Side Category Filtering & Live Search.
 *
 * Native vanilla JS — zero dependencies.
 */
document.addEventListener('DOMContentLoaded', () => {
  const filterPills = document.querySelectorAll('[data-studio-filter]');
  const searchInput = document.getElementById('bai-project-search');
  const clearBtn = document.getElementById('bai-search-clear');
  const cards = document.querySelectorAll('.bai-project-card');
  const countDisplay = document.getElementById('bai-filter-count');
  const emptyState = document.getElementById('bai-empty-state');
  const resetBtn = document.getElementById('bai-reset-filter');

  if (!cards.length) return;

  let currentCategory = 'all';
  let currentQuery = '';

  function applyFilter() {
    let visibleCount = 0;
    const query = currentQuery.trim().toLowerCase();

    cards.forEach((card) => {
      const cardCategory = card.getAttribute('data-category') || '';
      const cardSearch = (card.getAttribute('data-search') || '').toLowerCase();

      const categoryMatch = currentCategory === 'all' || cardCategory === currentCategory;
      const searchMatch = !query || cardSearch.includes(query);

      if (categoryMatch && searchMatch) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    if (countDisplay) {
      countDisplay.textContent = `Showing ${visibleCount} of ${cards.length} systems`;
    }

    if (emptyState) {
      if (visibleCount === 0) {
        emptyState.style.display = 'block';
      } else {
        emptyState.style.display = 'none';
      }
    }

    if (clearBtn) {
      clearBtn.style.display = currentQuery.length > 0 ? 'inline-flex' : 'none';
    }
  }

  // Category pill handlers
  filterPills.forEach((pill) => {
    pill.addEventListener('click', (e) => {
      e.preventDefault();
      filterPills.forEach((p) => p.classList.remove('is-active'));
      pill.classList.add('is-active');
      currentCategory = pill.getAttribute('data-studio-filter') || 'all';
      applyFilter();
    });
  });

  // Search input handler
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      currentQuery = e.target.value;
      applyFilter();
    });
  }

  // Clear search button
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      if (searchInput) {
        searchInput.value = '';
        currentQuery = '';
        searchInput.focus();
        applyFilter();
      }
    });
  }

  // Reset button in empty state
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      currentCategory = 'all';
      currentQuery = '';
      if (searchInput) searchInput.value = '';
      filterPills.forEach((p) => {
        if (p.getAttribute('data-studio-filter') === 'all') {
          p.classList.add('is-active');
        } else {
          p.classList.remove('is-active');
        }
      });
      applyFilter();
    });
  }

  // Smooth jump anchor
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId && targetId !== '#') {
        const targetElem = document.querySelector(targetId);
        if (targetElem) {
          e.preventDefault();
          targetElem.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  });

  // Initial pass
  applyFilter();
});
