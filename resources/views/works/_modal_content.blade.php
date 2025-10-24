<div class="container-fluid px-3">
    <!-- Header Pengunggah -->
    <div class="d-flex align-items-center gap-3 mb-3">
        <img src="{{ $work->user->profile_photo_url }}"
             alt="Foto Profil"
             class="rounded-circle"
             style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #0d6efd;">

        <div>
            <strong>{{ $work->user->name }}</strong>
            <p class="mb-0 text-muted small">
                {{ $work->user->nis ?? 'NIS tidak tersedia' }} •
                <span class="badge bg-info">{{ ucfirst($work->user->role ?? 'siswa') }}</span>
            </p>
        </div>
    </div>

    <!-- Judul -->
    <h5 class="fw-bold mb-2">{{ $work->title }}</h5>

    <!-- Jenis & Tanggal -->
    @php
        $typeLabels = [
            'karya' => 'Karya Siswa',
            'mading' => 'Mading Digital',
            'berita' => 'Berita Sekolah',
            'pengumuman' => 'Pengumuman',
            'pelajaran' => 'Materi Pelajaran'
        ];
    @endphp
    <small class="text-muted d-block mb-3">
        {{ $typeLabels[$work->type] ?? 'Konten' }} •
        {{ \Carbon\Carbon::parse($work->created_at)->format('d M Y, H:i') }}
    </small>

    <!-- Preview File -->
    @php
        $ext = strtolower($work->file_type ?? pathinfo($work->file_path, PATHINFO_EXTENSION));
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        $isVideo = in_array($ext, ['mp4', 'webm', 'mov', 'avi']);
    @endphp

    <div class="text-center mb-3">
        @if($isImage)
            <img src="{{ asset('storage/' . $work->file_path) }}"
                 class="img-fluid rounded shadow-sm"
                 style="max-height: 350px; object-fit: cover;">
        @elseif($isVideo)
            <video controls class="w-100 rounded shadow-sm" style="max-height: 350px;">
                <source src="{{ asset('storage/' . $work->file_path) }}" type="video/{{ $ext }}">
                Browser tidak mendukung video.
            </video>
        @else
            <img src="{{ $work->thumbnail_path
                ? asset('storage/' . $work->thumbnail_path)
                : asset('storage/icons/file.png') }}"
                 class="img-fluid rounded shadow-sm"
                 style="max-height: 250px;">
        @endif
    </div>

    <!-- Deskripsi -->
    <div class="mb-3">
        <small class="text-muted">Deskripsi:</small>
        <div class="bg-light p-3 rounded" style="white-space: pre-wrap; font-size: 0.95rem;">
            {!! preg_replace(
                '/(https?:\/\/[^\s]+)/',
                '<a href="$1" target="_blank" class="text-primary text-decoration-underline">$1</a>',
                htmlspecialchars($work->description ?? 'Tidak ada deskripsi.')
            ) !!}
        </div>
    </div>

    <!-- Like & Komentar Interaktif -->
    <div class="mb-3">
        <!-- Like Button -->
        @if(auth()->check())
            <form method="POST" action="{{ route('likes.toggle', $work) }}" class="d-inline like-form" data-work-id="{{ $work->id }}">
                @csrf
                <button type="submit" class="btn {{ $userLiked ? 'btn-danger' : 'btn-outline-primary' }} btn-sm d-flex align-items-center gap-1">
                    <i class="fas fa-heart"></i>
                    <span class="like-count">{{ $likesCount }}</span> Like
                </button>
            </form>
        @else
            <button class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1" disabled>
                <i class="fas fa-heart"></i> {{ $likesCount }} Like
            </button>
        @endif

        <!-- Form Komentar -->
        @if(auth()->check())
            <form class="comment-form mt-2" data-work-id="{{ $work->id }}">
                @csrf
                <div class="input-group">
                    <input type="text" name="content" class="form-control form-control-sm" placeholder="Tulis komentar..." maxlength="500" required>
                    <button class="btn btn-primary btn-sm" type="submit">Kirim</button>
                </div>
                <div class="text-danger mt-1 comment-error" style="display:none;"></div>
            </form>

            <!-- Daftar Komentar -->
            <div class="mt-2 comment-list">
                @foreach($comments as $comment)
                    <div class="small mb-1" data-comment-id="{{ $comment->id }}">
                        <strong>{{ $comment->user->name }}:</strong>
                        {{ $comment->content }}
                        @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                            <button class="btn btn-xs btn-outline-danger delete-comment-btn ms-2" data-comment-id="{{ $comment->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <small class="text-muted mt-1 d-block">Login untuk like & komentar.</small>
        @endif
    </div>

    <!-- Tombol Aksi -->
    <div class="d-flex justify-content-center gap-2">
        <!-- Unduh / Buka File -->
        @if($isImage || $isVideo)
            <a href="{{ asset('storage/' . $work->file_path) }}" class="btn btn-success btn-sm" target="_blank" download>
                <i class="fas fa-download me-1"></i> Unduh
            </a>
        @else
            <a href="{{ asset('storage/' . $work->file_path) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="fas fa-file me-1"></i> Buka File
            </a>
        @endif

        @if(auth()->check() && (auth()->id() === $work->user_id || auth()->user()->isAdmin() || auth()->user()->isGuru()))
            <form method="POST" action="{{ route('work.destroy', $work->id) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </form>
                         @if(Auth::check() && ($work->user_id == Auth::id() || Auth::user()->isAdmin()))
                            <a href="{{ route('work.edit', $work->id) }}" class="btn btn-warning px-4">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                        @endif
        @endif
    </div>
</div>

<style>
.small {
    font-size: 0.875rem;
}
.btn-xs {
    padding: 0.1rem 0.4rem;
    font-size: 0.75rem;
}
</style>

<!-- SCRIPT UNTUK EDIT MODAL -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle tombol edit
    document.querySelectorAll('.btn-edit-in-modal').forEach(button => {
        button.addEventListener('click', function() {
            const workId = this.getAttribute('data-work-id');
            loadEditForm(workId);
        });
    });
});

function loadEditForm(workId) {
    console.log('🔄 Loading edit form untuk work ID:', workId);

    // Show loading spinner
    const editModalBody = document.getElementById('editModalBody');
    editModalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading form edit...</p>
        </div>
    `;

    // Load form edit via AJAX
    fetch(`/works/${workId}/edit/form`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.text();
        })
        .then(html => {
            editModalBody.innerHTML = html;
            console.log('✅ Edit form loaded successfully');
        })
        .catch(error => {
            console.error('❌ Error loading edit form:', error);
            editModalBody.innerHTML = `
                <div class="alert alert-danger">
                    <h5>Error!</h5>
                    <p>Gagal memuat form edit: ${error.message}</p>
                    <button class="btn btn-secondary" onclick="loadEditForm(${workId})">Coba Lagi</button>
                </div>
            `;
        });
}
</script>