document.addEventListener('DOMContentLoaded', function () {
    // Cek apakah elemen pencarian ada & di mobile
    const searchTab = document.getElementById('search-tab');
    const searchModal = document.getElementById('searchModal');
    const closeSearch = document.getElementById('closeSearch');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    if (!searchTab || window.innerWidth >= 768) return;

    // === Buka/Tutup Modal ===
    function openSearchModal(e) {
        e.preventDefault();
        searchModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => searchInput.focus(), 100);
    }

    function closeSearchModal() {
        searchModal.classList.remove('active');
        document.body.style.overflow = '';
        searchInput.value = '';
        searchResults.innerHTML = '';
    }

    searchTab.addEventListener('click', openSearchModal);
    closeSearch?.addEventListener('click', closeSearchModal);
    searchModal?.addEventListener('click', function (e) {
        if (e.target === searchModal) closeSearchModal();
    });

    // === LIVE SEARCH ===
    let searchTimeout;

    searchInput?.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.innerHTML = '';
            return;
        }

        // Tampilkan loading
        searchResults.innerHTML = '<p class="text-center py-3">Mencari...</p>';

        searchTimeout = setTimeout(() => {
            // 🔑 Gunakan window.Laravel.assetUrl untuk path storage
            const searchUrl = `/search/users?q=${encodeURIComponent(query)}`;

            fetch(searchUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(users => {
                if (!Array.isArray(users) || users.length === 0) {
                    searchResults.innerHTML = '<p class="text-muted text-center py-3">Tidak ada pengguna ditemukan</p>';
                    return;
                }

                let html = '';
                users.forEach(user => {
                    // ✅ Gunakan window.Laravel.assetUrl jika tersedia
                    const baseUrl = window.Laravel?.assetUrl || '/';
                    const avatar = user.profile_photo
                        ? baseUrl + 'storage/' + user.profile_photo
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=0d47a1&color=fff&size=128`;

                    html += `
                        <a href="${baseUrl}profile/${user.id}" class="user-item">
                            <img src="${avatar}" alt="${escapeHtml(user.name)}" loading="lazy">
                            <span>${escapeHtml(user.name)}</span>
                        </a>
                    `;
                });
                searchResults.innerHTML = html;
            })
            .catch(err => {
                console.error('Pencarian gagal:', err);
                searchResults.innerHTML = '<p class="text-danger text-center py-3">Gagal memuat hasil</p>';
            });
        }, 400);
    });

    // === Helper: Escape HTML ===
    function escapeHtml(text) {
        if (typeof text !== 'string') return '';
        const map = {
            '&': '&amp;',
            '<': '<',
            '>': '>',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
});



// =============== DESKTOP LIVE SEARCH ===============
document.addEventListener('DOMContentLoaded', function () {
    const desktopSearchInput = document.getElementById('desktopSearchInput');
    const desktopSearchResults = document.getElementById('desktopSearchResults');
    const spinner = document.getElementById('desktopSearchSpinner');

    if (!desktopSearchInput) return;

    let desktopDebounce;

    desktopSearchInput.addEventListener('input', function () {
        clearTimeout(desktopDebounce);
        const query = this.value.trim();

        if (query.length < 2) {
            desktopSearchResults.style.display = 'none';
            return;
        }

        // Tampilkan loading
        spinner?.classList.remove('d-none');
        desktopSearchResults.innerHTML = '<div class="p-2 text-center">Mencari...</div>';
        desktopSearchResults.style.display = 'block';

        desktopDebounce = setTimeout(() => {
            const searchUrl = `/search/users?q=${encodeURIComponent(query)}`;

            fetch(searchUrl, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(users => {
                spinner?.classList.add('d-none');
                if (!Array.isArray(users) || users.length === 0) {
                    desktopSearchResults.innerHTML = '<div class="p-2 text-muted text-center">Tidak ada hasil</div>';
                    return;
                }

                let html = '';
                const baseUrl = window.Laravel?.assetUrl || '/';

                users.forEach(user => {
                    const avatar = user.profile_photo
                        ? baseUrl + 'storage/' + user.profile_photo
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=0d47a1&color=fff&size=128`;

                    html += `
                        <a href="${baseUrl}profile/${user.id}"
                           class="d-flex align-items-center p-2 text-decoration-none text-dark border-bottom"
                           style="text-decoration: none;">
                            <img src="${avatar}" width="36" height="36" class="rounded-circle me-2" style="object-fit: cover;">
                            <div>
                                <div class="fw-semibold">${escapeHtml(user.name)}</div>
                                <div class="text-muted small">@${user.id}</div>
                            </div>
                        </a>`;
                });

                desktopSearchResults.innerHTML = html;
            })
            .catch(() => {
                spinner?.classList.add('d-none');
                desktopSearchResults.innerHTML = '<div class="p-2 text-danger text-center">Error</div>';
            });
        }, 300);
    });

    // Sembunyikan dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        if (
            !desktopSearchInput.contains(e.target) &&
            !desktopSearchResults.contains(e.target)
        ) {
            desktopSearchResults.style.display = 'none';
        }
    });
});

// Pastikan escapeHtml tetap tersedia (jika belum ada di scope global)
function escapeHtml(text) {
    if (typeof text !== 'string') return '';
    const map = {
        '&': '&amp;',
        '<': '<',
        '>': '>',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}