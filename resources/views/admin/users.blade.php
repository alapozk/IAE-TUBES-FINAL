@extends('layouts.app')

@section('content')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .page-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 40px 20px;
  }

  .page-container {
    max-width: 1200px;
    margin: 0 auto;
  }

  /* Header Section */
  .section-header {
    background: white;
    border-radius: 15px;
    padding: 35px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: slideDown 0.5s ease-out;
  }

  .section-header h1 {
    font-size: 2rem;
    font-weight: 800;
    color: #1a202c;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  #result-caption {
    color: #718096;
    font-size: 0.95rem;
  }

  /* Toolbar */
  .toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 25px;
    align-items: center;
  }

  #quick-search {
    flex: 1;
    min-width: 250px;
    padding: 12px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f7fafc;
  }

  #quick-search:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .button-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .btn {
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .btn-ghost {
    background: #f7fafc;
    color: #4a5568;
    border: 1px solid #cbd5e0;
  }

  .btn-ghost:hover {
    background: #edf2f7;
    border-color: #a0aec0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  }

  .btn-ghost.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-color: transparent;
  }

  /* Table Container */
  .table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: slideUp 0.5s ease-out;
  }

  .table {
    width: 100%;
    border-collapse: collapse;
  }

  .table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .table thead th {
    padding: 18px;
    text-align: left;
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .table tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.3s ease;
  }

  .table tbody tr:hover {
    background: #f7fafc;
    transform: scale(1.01);
  }

  .table tbody td {
    padding: 18px;
    color: #2d3748;
    font-size: 0.95rem;
  }

  .table tbody tr.hidden {
    display: none;
  }

  /* User Avatar */
  .avatar {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .avatar-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    flex-shrink: 0;
  }

  .user-info h3 {
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 4px;
  }

  .user-info p {
    font-size: 0.85rem;
    color: #718096;
  }

  .text-name, .text-email {
    transition: all 0.2s ease;
  }

  .text-name:hover, .text-email:hover {
    color: #667eea;
  }

  mark {
    background: #ffd700;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: 600;
  }

  /* Email Link */
  .email-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .email-link:hover {
    color: #764ba2;
    text-decoration: underline;
  }

  /* Status Badge */
  .badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .badge-green {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(132, 250, 176, 0.3);
  }

  .badge-yellow {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
  }

  .badge-red {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
  }

  /* Empty State */
  .empty-row td {
    padding: 60px 20px !important;
    text-align: center;
  }

  .empty-state {
    text-align: center;
  }

  .empty-state svg {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    color: #cbd5e0;
    opacity: 0.6;
  }

  .empty-state h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
  }

  .empty-state p {
    color: #718096;
    font-size: 0.95rem;
  }

  /* Footer */
  .table-footer {
    background: #f7fafc;
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    text-align: center;
    font-size: 0.95rem;
    color: #4a5568;
  }

  .table-footer .highlight {
    font-weight: 700;
    color: #667eea;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .page-wrapper {
      padding: 20px 10px;
    }

    .section-header {
      padding: 20px;
    }

    .section-header h1 {
      font-size: 1.5rem;
    }

    .toolbar {
      flex-direction: column;
    }

    #quick-search {
      min-width: 100%;
    }

    .table {
      font-size: 0.85rem;
    }

    .table thead th,
    .table tbody td {
      padding: 12px;
    }

    .avatar-circle {
      width: 36px;
      height: 36px;
      font-size: 0.9rem;
    }

    .user-info h3 {
      font-size: 0.9rem;
    }

    .button-group {
      width: 100%;
    }

    .btn {
      flex: 1;
      padding: 8px 12px;
      font-size: 0.85rem;
    }
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<div class="page-wrapper">
  <div class="page-container">
    
    {{-- Header --}}
    <div class="section-header">
      <div>
        <h1>👨‍🏫 Daftar Guru Aktif</h1>
        <p id="result-caption" class="mt-2">
          Menampilkan <span class="font-semibold">{{ $users->count() }}</span> guru yang terdaftar di platform EduConnect
        </p>
      </div>

      {{-- Toolbar: search & sort (client-side) --}}
      <div class="toolbar">
        <input
          id="quick-search"
          type="text"
          placeholder="🔍 Cari nama atau email…"
          autocomplete="off"
        />
        <div class="button-group">
          <button id="sort-name" type="button" class="btn btn-ghost" title="Urutkan berdasarkan nama">Urut Nama</button>
          <button id="sort-email" type="button" class="btn btn-ghost" title="Urutkan berdasarkan email">Urut Email</button>
          <button id="reset" type="button" class="btn btn-ghost" title="Reset ke kondisi awal">Reset</button>
        </div>
      </div>
    </div>

    {{-- Table --}}
    <div class="table-container">
      <table class="table" id="users-table">
        <thead>
          <tr>
            <th class="w-16">No</th>
            <th>Nama Guru</th>
            <th>Email</th>
            <th class="w-24">Status</th>
          </tr>
        </thead>
        <tbody id="users-tbody">
          @forelse($users as $index => $u)
            <tr>
              <td class="text-center font-semibold text-gray-600" data-col="no">
                {{ $index + 1 }}
              </td>
              <td data-col="name">
                <div class="avatar">
                  <div class="avatar-circle">
                    {{ strtoupper(mb_substr($u->name ?? 'U', 0, 1, 'UTF-8')) }}
                  </div>
                  <div class="user-info">
                    <h3 class="text-name">{{ $u->name }}</h3>
                    <p>ID: {{ $u->id }}</p>
                  </div>
                </div>
              </td>
              <td data-col="email">
                <a href="mailto:{{ $u->email }}" class="email-link text-email">
                  {{ $u->email }}
                </a>
              </td>
              <td data-col="status">
                <span class="badge badge-green">✓ Aktif</span>
              </td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="4">
                <div class="empty-state">
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                  </svg>
                  <h3>Tidak ada data</h3>
                  <p>Belum ada guru yang terdaftar di platform.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
      
      {{-- Footer Info --}}
      @if($users->count() > 0)
        <div class="table-footer">
          Total <span class="highlight" id="total-count">{{ $users->count() }}</span> guru aktif terdaftar
        </div>
      @endif
    </div>

  </div>
</div>

{{-- Client-side enhancers (tidak mengubah fungsi back-end) --}}
<script>
(function(){
    const qInput = document.getElementById('quick-search');
    const tbody  = document.getElementById('users-tbody');
    const rows   = Array.from(tbody.querySelectorAll('tr')).filter(tr => !tr.classList.contains('empty-row'));
    const capEl  = document.getElementById('result-caption');
    const totalEl= document.getElementById('total-count');
    const btnSortName  = document.getElementById('sort-name');
    const btnSortEmail = document.getElementById('sort-email');
    const btnReset     = document.getElementById('reset');

    let sortState = { col: null, dir: 1 }; // 1 asc, -1 desc

    function norm(s){ return (s || '').toString().toLowerCase().trim(); }

    function clearHighlights(){
        rows.forEach(tr => {
            tr.querySelectorAll('.text-name, .text-email').forEach(el => {
                el.innerHTML = el.textContent;
            });
        });
    }

    function highlight(el, needle){
        if(!needle) return;
        const text = el.textContent;
        const re = new RegExp('(' + needle.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'ig');
        el.innerHTML = text.replace(re, '<mark>$1</mark>');
    }

    function renumber(){
        const visible = rows.filter(r => !r.classList.contains('hidden'));
        visible.forEach((tr, i) => {
            const noCell = tr.querySelector('[data-col="no"]');
            if(noCell) noCell.textContent = i + 1;
        });
        const total = rows.length;
        const shown = visible.length;
        if (capEl) capEl.innerHTML = `Menampilkan <span class="font-semibold">${shown}</span> dari <span class="font-semibold">${total}</span> guru yang terdaftar di platform EduConnect`;
        if (totalEl) totalEl.textContent = total;
    }

    function applySearch(){
        const needle = norm(qInput.value);
        clearHighlights();
        rows.forEach(tr => {
            const name  = norm(tr.querySelector('.text-name')?.textContent);
            const email = norm(tr.querySelector('.text-email')?.textContent);
            const match = !needle || name.includes(needle) || email.includes(needle);
            tr.classList.toggle('hidden', !match);
            if (match && needle){
                highlight(tr.querySelector('.text-name'), needle);
                highlight(tr.querySelector('.text-email'), needle);
            }
        });
        renumber();
    }

    function sortBy(col){
        const dir = (sortState.col === col) ? -sortState.dir : 1;
        sortState = { col, dir };
        const key = (tr) => {
            if(col === 'name'){
                return norm(tr.querySelector('.text-name')?.textContent);
            } else if(col === 'email'){
                return norm(tr.querySelector('.text-email')?.textContent);
            }
            return '';
        };
        const visible = rows.filter(r => !r.classList.contains('hidden'));
        visible.sort((a,b) => key(a) > key(b) ? dir : key(a) < key(b) ? -dir : 0);
        visible.forEach(tr => tbody.appendChild(tr));
        renumber();
    }

    qInput?.addEventListener('input', applySearch);
    btnSortName?.addEventListener('click', () => sortBy('name'));
    btnSortEmail?.addEventListener('click', () => sortBy('email'));
    btnReset?.addEventListener('click', () => {
        qInput.value = '';
        rows.forEach(tr => tr.classList.remove('hidden'));
        clearHighlights();
        rows.forEach(tr => tbody.appendChild(tr));
        renumber();
    });

    applySearch();
})();
</script>

@endsection